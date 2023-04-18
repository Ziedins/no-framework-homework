<?php

use function PHPUnit\Framework\isFalse;

class Api extends Controller
{

    public function index(): void
    {
        echo 'No action provided for api';
    }

    public function payment(): void
    {
        if (!isset($_POST)) {
            http_response_code(404);
            echo 'payment endpoint only has POST operation';
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->model->makePayment(
                    $data['firstname'],
                    $data['lastname'],
                    $data['paymentDate'],
                    $data['amount'],
                    $data['description'],
                    $data['refId']
        );

        if($result === true) {
            http_response_code(201);
            echo 'Payment added successfully';
            return;
        }

        http_response_code(400);
        echo 'Payment not added , issues:'.PHP_EOL;

        foreach ($result as $issue) {
            if($issue === 1) http_response_code(409);
            echo $this->model::ISSUE_LABELS[$issue].PHP_EOL;
        }
    }

}

