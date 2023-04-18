<?php
declare(strict_types=1);
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
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
                "refId" => uniqid()
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
                "refId" => uniqid()
        ];

        //Makes Payment Once
        $client->post('http://loans.test/api/payment', ['body' => json_encode($data)]);

        //Makes Same Payment Twice expect ClientException
        $this->expectException(ClientException::class);
        $response_second = $client->post('http://loans.test/api/payment', ['body' => json_encode($data)]);
        $this->assertSame(409, $response_second->getStatusCode(), 'Mistmatched status codes , expected 409 got '.$response_second->getStatusCode());
    }
}
