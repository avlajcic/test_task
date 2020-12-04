<?php

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\ApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameHistoryControllerTest extends ApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testGameGameStateEnd()
    {
        $correctValue = 'aeiou';
        $this->request(
            Request::METHOD_POST,
            '/api/v1/game',
            [
                'value' => $correctValue
            ]
        );

        $response = $this->request(
            Request::METHOD_GET,
            '/api/v1/game-history'
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('state', $responseData);
        $this->assertArrayHasKey('game_history', $responseData);
        $this->assertArrayHasKey(0, $responseData['game_history']);
        $this->assertArrayHasKey('value', $responseData['game_history'][0]);
        $this->assertArrayHasKey('created_at', $responseData['game_history'][0]);

        $this->assertTrue($responseData['state']);
        $this->assertEquals($correctValue, $responseData['game_history'][0]['value']);
    }
}
