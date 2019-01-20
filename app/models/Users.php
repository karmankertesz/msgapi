<?php

namespace MsgApi;

use Phalcon\Mvc\MongoCollection;

class Users extends MongoCollection
{
	
	public $username;
	public $password;

	
}