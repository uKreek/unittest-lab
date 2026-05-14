<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ApiTest extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost'
        ]);
    }

    public function testMockRequest()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"status": "success"}')
        ]);

        $handlerStack = HandlerStack::create($mock);
        $mockClient = new Client(['handler' => $handlerStack]);

        $response = $mockClient->get('/api/students');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"status": "success"}', (string) $response->getBody());
    }

    public function testRealHttpRequestAddAndGet()
    {
        $responsePost = $this->client->post('/index.php', [
            'form_params' => [
                'name' => 'GuzzleStudent'
            ]
        ]);
        
        $this->assertEquals(200, $responsePost->getStatusCode());
        $postData = json_decode((string)$responsePost->getBody(), true);
        $this->assertEquals('success', $postData['status']);
        $this->assertEquals('Student GuzzleStudent added', $postData['message']);

        $responseGet = $this->client->get('/index.php');
        $this->assertEquals(200, $responseGet->getStatusCode());
        $getData = json_decode((string)$responseGet->getBody(), true);
        
        $this->assertEquals('success', $getData['status']);
        $this->assertGreaterThanOrEqual(1, $getData['count']); 
        $this->assertIsArray($getData['data']);
    }
}
