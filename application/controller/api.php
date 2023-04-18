<?php

class Api extends Controller
{

    public function index(): void
    {
        echo 'No action provided for api';
    }

    public function payment(): void
    {
        if (isset($_POST)) {

            $data = json_decode(file_get_contents('php://input'), true);
            try {

                $this->model->makePayment(
                    $data['firstname'],
                    $data['lastname'],
                    $data['paymentDate'],
                    $data['amount'],
                    $data['description'],
                    $data['refId']
                );

            } catch (\Exception $e) {
                echo $e;
                http_response_code(409);
                return;
            }


        }

        http_response_code(201);
        return;
    }

}

