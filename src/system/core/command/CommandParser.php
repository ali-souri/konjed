<?php

namespace Core\Command;

/**
 * --- CommandParser class is a tool of executing commands singular and plural
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Command
 */
class CommandParser{

	/** @var Object The CommandHandler instance in class */
	private $CommandHandler = "";

	function __construct($command_handler){
		$this->CommandHandler = $command_handler;
	}

	/**
	 * --- This function executes all of the commands stored in CommandHandler field
	 * @return TRUE|FALSE The results of all command run situation
	 */
	public function StackExecute(){
		$result = TRUE;
		if ($this->CommandHandler->get_status()) {
			if (($this->CommandHandler->get_commands()!="")&&(!empty($this->CommandHandler->get_commands()))) {
				foreach ($this->CommandHandler->get_commands() as $command) {
					$result&=$this::execute($command);
				}
				return $result;
			}else{
				echo \Core\Service\Error::get_message("no_system_commands");	
				return FALSE;
			}
		}else{
			echo \Core\Service\Error::get_message("false_command_handler_status");
			return FALSE;
		}
	}

	/**
	 * --- This function executes a command and returns the result
	 * @param command $command A system command
	 * @return FALSE in case of error
	 */
	public static function execute($command){
		$command_parsed_data = self::parse($command);
		if ($command_parsed_data) {
			require_once __DIR__."/../../../..".$command_parsed_data['class_address'];
			$class = new $command_parsed_data['class_full_name'];
			$func = $command_parsed_data['func_name'];
			return call_user_func_array(array($class,$func),$command_parsed_data['func_input_array']);
			// return $class->$func($command_parsed_data['func_input_array']);
		}else{
			echo \Core\Service\Error::get_message("no_such_command");
			return FALSE;
		}
	}

	/**
	 * --- This function returns the defined information of a command in system
	 * @param command $command A system command
	 * @return FALSE in case of error
	 */
	private static function parse($command){
		$command_ruler = new CommandRules();
		if ($command_ruler->validate($command)) {
			return $command_ruler->get_command_func_data();
		}else{
			return FALSE;
		}
	}


}

?>