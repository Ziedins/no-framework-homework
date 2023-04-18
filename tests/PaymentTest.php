<?php
declare(strict_types=1);
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase {

    public function testCanMakePaymentFromValidData(): void {

        $client = new Client();
        $data = [
                "firstname" => "Lorem",
                "lastname" => "Ipsum",
                "paymentDate" => "2022-12-12T15:19:21+00:00",
                "amount" => "99.99",
                "description" => "Lorem ipsum dolorLN20221212 sit amet...",
                "refId" => "dda8b637-b2e8-4f79-a4af-testorama"
        ];


        $response = $client->post('http://loans.test/api/payment', ['body' => json_encode($data)]);

        $this->assertSame(201, $response->getStatusCode(), 'Mistmatched status codes , expected 201 got '.$response->getStatusCode());
    }

    public function testCannotCreateDuplicatePayment(): void {

        $client = new Client();
        $data = [
                "firstname" => "Lorem",
                "lastname" => "Ipsum",
                "paymentDate" => "2022-12-12T15:19:21+00:00",
                "amount" => "99.99",
                "description" => "Lorem ipsum dolorLN20221212 sit amet...",
                "refId" => "dda8b637-b2e8-4f79-a4af-testorama"
        ];

        //Makes Payment Once
        $client->post('http://loans.test/api/payment', ['body' => json_encode($data)]);

        //Makes Same Payment Twice
        $response_second = $client->post('http://loans.test/api/payment', ['body' => json_encode($data)]);

        $this->assertSame(409, $response_second->getStatusCode(), 'Mistmatched status codes , expected 409 got '.$response_second->getStatusCode());

    }
}
