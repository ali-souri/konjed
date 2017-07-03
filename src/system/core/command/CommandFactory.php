<?php

namespace Core\Command;

require_once __DIR__ . "/../Loader.php";
\Loader::load("all");

class CommandFactory
{
	
	public static function get_command_object($command_object_name){
		return new $command_object_name();
	}
	
}