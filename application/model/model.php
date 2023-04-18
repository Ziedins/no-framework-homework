<?php

class Model
{
    public const DATE_TIME_FORMAT = "Y-m-d H:i:s";

    private ?PDO $db = null;
    /**
     * @param object $db A PDO database connection
     */
    function __construct(PDO $db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    public function makePayment(string $firstname, string $lastname, string $paymentDate, string $amount, string $description, string $refId) : void
    {
        $problems = $this->validatePayment($refId, $amount, $paymentDate);
        if(count($problems) == 0) {
            $this->insertPayment($firstname, $lastname, $paymentDate, $amount, $description, $refId, 'test');
            return;
        }

        print_r($problems);
        //throw new Exception('Theres issues with the payment entry : '. print_r($problems));
    }

    public function getPaymentsByDate(string $paymentDate): mixed {
        $sql = "SELECT * FROM payments WHERE DATE( paymentDate ) = :paymentDate";
        $query = $this->db->prepare($sql);
        $parameters = [":paymentDate" => $paymentDate];
        $query->execute($parameters);

        return $query->fetchAll();
    }

    private function validatePayment(string $refId, string $amount, string $paymentDate) : array {
        $problems = [];

        if($this->checkIfPaymentExists($refId)) $problems[] = "duplicate refId";

        if($this->isNegativeAmount($amount)) $problems[] = "negative amount";

        if($this->isDateInValid($paymentDate)) $problems[] = "invalid date";

        return $problems;
    }

    private function isDateInValid(string $paymentDate): bool  {
        return date($this::DATE_TIME_FORMAT, strtotime($paymentDate)) == false;
    }

    private function isNegativeAmount(string $amount): bool {
        $amount = floatval($amount);

        return $amount < 0;
    }

    private function checkIfPaymentExists(string $refId): bool
    {
        $sql = "SELECT 1 FROM payments WHERE refId = :refId";
        $query = $this->db->prepare($sql);
        $paremeters = [":refId" => $refId];

        $query->execute($paremeters);

        return $query->fetchColumn() != false;
    }

    private function checkStatusAfterPayment() {

    }

    private function insertPayment(string $firstname, string $lastname, string $paymentDate, string $amount, string $description, string $refId, string $status) : void {
        $sql = "INSERT INTO payments (refId, firstname, lastname, paymentDate, amount, description, loan_reference, status) VALUES (:refId, :firstname, :lastname, :paymentDate, :amount, :description, :loan_reference, :status)";
        $query = $this->db->prepare($sql);
        $paymentDate = date($this::DATE_TIME_FORMAT, strtotime($paymentDate));

        $paremeters = [
            ':refId' => $refId,
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':paymentDate' => $paymentDate,
            ':amount' => $amount,
            ':description' => $description,
            ':loan_reference' => $description,
            ':status' => $status
        ];

        $query->execute($paremeters);
    }

}
