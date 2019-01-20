<?php
require('../vendor/autoload.php');

class Message extends PHPUnit\Framework\TestCase
{
    protected $client;
    protected $validTokens;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://localhost:9000'
        ]);

        $this->validTokens['joe'] = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1NDgwMzAzNTAsImlzcyI6InBoYWxjb24tand0LWF1dGgiLCJzdWIiOiI1YzQzYTY1OTI1NzAwZjBlZDA2NjkxY2MiLCJ1c2VybmFtZSI6ImpvaG4iLCJpYXQiOjE1NDc5NDM5NTB9.XO9MKRlIt7XUKG2FuY1RpbMExZLUUxxAcuA7mDO4JhQ';
        $this->validTokens['jack'] = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1NDgwMzMwMTUsImlzcyI6InBoYWxjb24tand0LWF1dGgiLCJzdWIiOiI1YzQzYTZlODI1NzAwZjBlZDA2NjkxY2QiLCJ1c2VybmFtZSI6ImphY2siLCJpYXQiOjE1NDc5NDY2MTV9.54vaf0PgRkL92ScnFmuS6rEpFDGOqwO4czlgUmXaR94';
    }

    public function testAddMessage(){

        $response = $this->client->post('/api/message',[
        'headers' => ['Authorization' => 'Bearer '. $this->validTokens['joe']],
        'json' => [
            'message'     => 'testing123',
        ]]);
        $this->assertEquals(202, $response->getStatusCode());

	}

    public function testAddMessageEmptyBody(){

        $response = $this->client->post('/api/message',[
		'http_errors' => false,
        'headers' => ['Authorization' => 'Bearer '. $this->validTokens['joe']],
        ]);
        $this->assertEquals(400, $response->getStatusCode());

	}


    public function testAddMessageEmpty(){

        $response = $this->client->post('/api/message',[
		'http_errors' => false,
        'headers' => ['Authorization' => 'Bearer '. $this->validTokens['joe']],
        'json' => [
            'message'     => '',
        ]]);
        $this->assertEquals(400, $response->getStatusCode());

	}

}
