<?php
namespace MsgApi;

use Phalcon\Mvc\MongoCollection;

class History extends MongoCollection
{
	public $userid;
	public $username;
	public $messages;
	public $retrieved;
	
	public function addMessage($userid, $username, $text){
		
	}

}