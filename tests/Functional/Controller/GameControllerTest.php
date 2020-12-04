<?php

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\ApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameControllerTest extends ApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testReset()
    {
        $response = $this->request(
            Request::METHOD_GET,
            '/api/v1/reset'
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testGameFailedValidation()
    {
        $longString = str_repeat('a', 101);

        $response = $this->request(
            Request::METHOD_POST,
            '/api/v1/game',
            [
                'value' => $longString
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('errors', $responseData);
    }

    public function testGameWrongValue()
    {
        $response = $this->request(
            Request::METHOD_POST,
            '/api/v1/game',
            [
                'value' => 'test_case'
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('ok', $responseData);
        $this->assertFalse($responseData['ok']);
    }

    public function testGameCorrectValue()
    {
        $response = $this->request(
            Request::METHOD_POST,
            '/api/v1/game',
            [
                'value' => 'aeiou'
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('ok', $responseData);
        $this->assertTrue($responseData['ok']);
    }

    public function testGameGameStateEnd()
    {
        $this->request(
            Request::METHOD_POST,
            '/api/v1/game',
            [
                'value' => 'aeiou'
            ]
        );

        $response = $this->request(
            Request::METHOD_POST,
            '/api/v1/game',
            [
                'value' => 'oeui'
            ]
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('errors', $responseData);
    }
}
