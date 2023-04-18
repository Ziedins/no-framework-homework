<?php

class Model
{
    public const DATE_TIME_FORMAT = "Y-m-d H:i:s";
    public const ISSUE_LABELS = [
        1 => '1 - Duplicate Payment',
        2 => '2 - Negative Payment Amount',
        3 => '3 - Invalid Payment Date'
    ];
    private const PAYMENT_ASSIGNED = "ASSIGNED";
    private const PAYMENT_PARTIALY_ASSIGNED = "PARTIALLY_ASSIGNED";
    private const LOAN_PAYED = "PAYED";

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

    public function makePayment(string $firstname, string $lastname, string $paymentDate, string $amount, string $description, string $refId) : bool|array
    {
        $problems = $this->validatePayment($refId, $amount, $paymentDate);
        $loan_reference = $this::pregMatchLoanReference($description);

        if(count($problems) == 0) {
            $this->insertPayment($firstname, $lastname, $paymentDate, $amount, $description, $loan_reference, $refId, $this::PAYMENT_ASSIGNED);
            $this->checkStatusAfterPayment($loan_reference);

            return true;
        }

        return $problems;
    }

    public function getPaymentsByDate(string $paymentDate): array {
        $sql = "SELECT * FROM payments WHERE DATE( paymentDate ) = :paymentDate";
        $query = $this->db->prepare($sql);
        $parameters = [":paymentDate" => $paymentDate];
        $query->execute($parameters);

        $payments = $query->fetchAll();

        if(!$payments) return [];

        return $payments;

    }

    private function validatePayment(string $refId, string $amount, string $paymentDate) : array {
        $problems = [];

        if($this->checkIfPaymentExists($refId)) $problems[] = 1;

        if($this->isNegativeAmount($amount)) $problems[] = 2;

        if($this->isDateInValid($paymentDate)) $problems[] = 3;

        return $problems;
    }

    private function isDateInValid(string $paymentDate): bool  {
        $datetime = strtotime($paymentDate);
        $datetimeFormated = date($this::DATE_TIME_FORMAT, $datetime);

        if($datetime === false || $datetimeFormated === false) return true;

        return false;
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

    private function checkStatusAfterPayment(string $loan_reference): void {
        $amountSum = $this->getPayedAmountByLoanReference($loan_reference);
        $toPay = $this->getLoanToPayByLoanReference($loan_reference);

        if($amountSum >= $toPay) {
            $this->markLoanByLoanReferenceAsPaid($loan_reference);
        }

        if($amountSum > $toPay) {
            $this->setPaymentStatusesByLoanReference($loan_reference, $this::PAYMENT_PARTIALY_ASSIGNED);
        }
    }

    private function markLoanByLoanReferenceAsPaid(string $loan_reference): void {
        $sql = "UPDATE loans SET state = :payed WHERE reference = :loan_reference";
        $query = $this->db->prepare($sql);
        $parameters = [
            ":loan_reference" => $loan_reference,
            ':payed' => $this::LOAN_PAYED
        ];

        $query->execute($parameters);
    }


    private function setPaymentStatusesByLoanReference(string $loan_reference, string $status): void {
        $sql = "UPDATE payments SET status = :status WHERE loan_reference = :loan_reference";
        $query = $this->db->prepare($sql);
        $parameters = [
            ':loan_reference' => $loan_reference,
            ':status' => $status
        ];

        $query->execute($parameters);
    }

    private function  getLoanToPayByLoanReference(string $loan_reference): float {
        $sql = "SELECT amount_to_pay FROM loans WHERE reference = :loan_reference";
        $query = $this->db->prepare($sql);
        $parameters = [":loan_reference" => $loan_reference];

        $query->execute($parameters);

        $toPay = $query->fetch();

        if($toPay) return $toPay->amount_to_pay;

        return 0.0;
    }

    private function getPayedAmountByLoanReference(string $loan_reference) : float {
        $sql = "SELECT SUM(amount) AS amount FROM payments WHERE loan_reference = :loan_reference";
        $query = $this->db->prepare($sql);
        $parameters = [":loan_reference" => $loan_reference];

        $query->execute($parameters);

        $amountSum = $query->fetch()->amount;

        return $amountSum;
    }

    private function insertPayment(string $firstname, string $lastname, string $paymentDate, string $amount, string $description, string $loan_reference, string $refId, string $status) : void {
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
            ':loan_reference' => $loan_reference,
            ':status' => $status
        ];

        $query->execute($paremeters);
    }

    static public function pregMatchLoanReference(string $str): string
    {
        $pattern = '(L+N........)';
        $result = 'NO_LOAN_NUMBER_IN_PAYMENT';
        if (preg_match($pattern, $str, $match)) {
            $result = $match[0];
        }

        return $result;
    }

}
