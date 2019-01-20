<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Phalcon\Mvc\Micro;
use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Di\FactoryDefault;
use Dmkit\Phalcon\Auth\Middleware\Micro as AuthMicro;
use Phalcon\Mvc\Collection\Manager;
use Phalcon\Db\Adapter\MongoDB\Client;

$loader = new \Phalcon\Loader();
$loader
    ->registerNamespaces(
        [
		'Phalcon' =>  __DIR__ .'/../vendor/phalcon/incubator/Library/Phalcon',
        'MsgApi' => __DIR__ . '/../models/',
        ]
    )
    ->register();

$di = new FactoryDefault();

$di->setShared('collectionManager', function () {
    return new Manager();
});


$config = new ConfigIni( __DIR__ . '/../config.ini');
$di->set(
    "config",
    function () use($config) {
        return $config;
    }
);

$di->setShared('mongo', function () {

    $config = $this->getShared('config');

    if (!$config->database->username || !$config->database->password) {
        $dsn = 'mongodb://' . $config->database->host;
    } else {
        $dsn = sprintf(
            'mongodb://%s:%s@%s',
            $config->database->username,
            $config->database->password,
            $config->database->host
        );
    }

    $mongo = new Client($dsn);

    return $mongo->selectDatabase($config->database->dbname);

});


$app = new Micro($di);
$app->getRouter()->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);


$auth = new AuthMicro($app);


$app->post(
    '/api/login',
    function ()  use ($app) {

        $authData = $app->request->getJsonRawBody();


		$user = MsgApi\Users::findFirst(
		[
			['username' => $authData->username]
		]);

		if (empty($user)){
			$app->response->setStatusCode(401, 'Unauthorized');
			$app->response->send();
			return false;
		}


		$match = password_verify($authData->password, $user->password);

		if (!$match){
			$app->response->setStatusCode(401, 'Unauthorized');
			$app->response->send();
			return false;
		}

		$payload = [
			'sub'   => $user->getId()->__toString(),
			'username' =>  $user->username,
			'iat' => time(),
		];

		$token = $this->auth->make($payload);
		echo $token;

    }
);

$app->post(
    '/api/message',
    function ()  use ($app) {

        $postdata = $app->request->getJsonRawBody();
		$authdata = $this->auth->data();

		$userid = $authdata['sub'];
		$username = $authdata['username'];

		if ((empty($postdata)) || (empty($postdata->message))){
			$app->response->setStatusCode(400);
			$app->response->send();
			return false;
		}
		$app->response->setStatusCode(202);
		$app->response->send();

		$text = $postdata->message;
		//get history
		$history = MsgApi\History::findFirst(
		[
			['userid' => $userid]
		]);

		if (empty($history)){

			$history = new MsgApi\History();
			$history->userid = $userid;
			$history->username = $username;
			$history->retrieved = 0;
			$history->messages = array();

		}

		$message = new MsgApi\Message($text);
		array_push($history->messages, $message);

		$history->save();


    }
);

$app->get(
    '/api/history',
    function ()  use ($app) {
		$authdata = $this->auth->data();
		$userid = $authdata['sub'];
		$username = $authdata['username'];

		$history = MsgApi\History::findFirst(
		[
			['userid' => $userid]
		]);
		if (empty($history)){
			$app->response->setStatusCode(404);
			$app->response->send();
		} else {
			$history->retrieved++;
			$history->save();
			$app->response->setJsonContent(['retrieved'=>$history->retrieved, 'count'=> count($history->messages), 'messages'=> $history->messages]);
			$app->response->send();
		}
    }
);

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
});

$app->handle();
