<?php
namespace MsgApi;

class Message
{
	public $text;
	public $timestamp;
	
	public function __construct(string $text){
		if (empty($text)){
			throw new \InvalidArgumentException('Text is empty');
		}
		$this->timestamp = time();
		$this->text = $text;
	}

}