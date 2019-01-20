<?php
require('../vendor/autoload.php');

class History extends PHPUnit\Framework\TestCase
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

		//add one message
        $this->client->post('/api/message',[
        'headers' => ['Authorization' => 'Bearer '. $this->validTokens['joe']],
        'json' => [
            'message'     => 'testing123',
        ]]);

    }
    public function testGetHistory(){

        $response = $this->client->get('/api/history',[
        'headers' => ['Authorization' => 'Bearer '. $this->validTokens['joe']],
        ]);
        $this->assertEquals(200, $response->getStatusCode());

		$data = (string) $response->getBody();

		$this->assertNotEmpty($data);

	}


    public function testGetHistoryEmpty(){

        $response = $this->client->get('/api/history',[
		'http_errors' => false,
        'headers' => ['Authorization' => 'Bearer '. $this->validTokens['jack']],
        ]);
        $this->assertEquals(404, $response->getStatusCode());

		$data = (string) $response->getBody();

		$this->assertEmpty($data);
	}

    public function testRetrieve(){

        $response = $this->client->get('/api/history',[
        'headers' => ['Authorization' => 'Bearer '. $this->validTokens['joe']],
        ]);

		$data = (string) $response->getBody();

		$json = json_decode($data, true);

		$retrieveOne = $json['retrieved'];

        $response = $this->client->get('/api/history',[
        'headers' => ['Authorization' => 'Bearer '. $this->validTokens['joe']],
        ]);

		$data = (string) $response->getBody();

		$json = json_decode($data, true);

		$retrieveTwo = $json['retrieved'];

		$this->assertEquals($retrieveOne+1, $retrieveTwo);

	}

}
