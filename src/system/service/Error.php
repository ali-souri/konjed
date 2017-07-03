<?php

namespace Core\Service;

/**
* 
*/
class Error
{
	
	function __construct(){}

	public static function Handle($exception){
		$exception_text = $exception::__toString;
		self::Log($exception_text);
		return self::get_message($exception_text);
	}

	public static function Log($error_text){
		//write log
	}

	public static function get_message($exception_text){
		$return_text = "";
		if ($exception_text=="non_allowed_write_object") {
			$return_text.="there is a problem to write data.";
		}elseif ($exception_text=="incorrect_model_object_name") {
			$return_text.="there is no such model object.";	
		}elseif ($exception_text=="user_not_exist") {
			$return_text.="no user found.";
		}elseif ($exception_text=="password_incorrect") {
			$return_text.="incorrect password.";
		}elseif ($exception_text=="404_not_found") {
			$return_text.="<h1>404 NOT FOUND</h1>";
		}elseif ($exception_text=="no_such_controller_function") {
			$return_text.="<h1>there is no handler for your request!</h1>";
		}elseif ($exception_text=="false_command_handler_status") {
			$return_text.="<h1>there is an error in system commands!</h1>";
		}elseif ($exception_text=="no_system_commands") {
			$return_text.="<h1>there is no available system commands!</h1>";
		}elseif ($exception_text=="page_record_not_found") {
			$return_text.="<h1>there is no such page in the system!</h1>";
		}elseif ($exception_text=="no_such_command") {
			$return_text.="<h1>there is no such command in the system!</h1>";
		}else {
			$return_text=$exception_text;
		}
		return $return_text;
	}

}

?>