<?php
require('../vendor/autoload.php');

class Login extends PHPUnit\Framework\TestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://localhost:9000'
        ]);

    }

    public function testLogin(){
        $response = $this->client->post('/api/login',[
        'json' => [
            'username'     => 'john',
            'password'    => 'doe'
        ]]);
        $this->assertEquals(200, $response->getStatusCode());

		$data = (string) $response->getBody();

		$this->assertNotEmpty($data);
	}

	public function testLoginInvalid(){
        $response = $this->client->post('/api/login',['http_errors' => false,
        'json' => [
            'username'     => 'john',
            'password'    => 'xxxxx'
        ]]);
        $this->assertEquals(401, $response->getStatusCode());
	}

	public function testLoginInvalidUser(){
        $response = $this->client->post('/api/login',['http_errors' => false,
        'json' => [
            'username'     => 'john222',
            'password'    => 'doe'
        ]]);
        $this->assertEquals(401, $response->getStatusCode());
	}

	public function testLoginEmpty(){
        $response = $this->client->post('/api/login',['http_errors' => false]);
        $this->assertEquals(401, $response->getStatusCode());
	}


}
