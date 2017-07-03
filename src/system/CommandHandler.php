<?php

namespace Core\Command;

require_once __DIR__."/core/Loader.php";
\Loader::load("cores");

/**
 * --- CommandHandler class is a set of tools for controller's function command handling
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Command
 */
class CommandHandler{
	
	/** @var FALSE|TRUE The status of command */
	private $status = FALSE;
	/** @var array The stack of commands */
	private $commands = array();

	/**
	 * --- empty constructor of class
	 */
	function __construct(){}

	public function get_status(){
		return $this->status;
	}
	public function get_commands(){
		return $this->commands;
	}
	public function set_status($status){
		$this->status=$status;
	}
	public function set_commands($commands){
		$this->commands=$commands;
	}

	/**
	 * --- Returns the state of class fields to the default values
	 */
	public function clear(){
		$this->status=FALSE;
		$this->commands=array();
	}

	/**
	 * --- Gets all of information for making a new command and then makes command and puts it in the stack
	 */
	public function create_and_add_command($name,$module_name,$action_name,$additional_data){
		$this->commands[] = new Command($name,$module_name,$action_name,$additional_data);
	}

	/**
	 * --- Gets a new command and then puts it in the stack
	 */
	public function add_command($command){
		$this->commands[] = $command;
	}

	/**
	 * --- Gets all of information for making a new command and then executes it
	 */
	public function doInTime($name,$module_name,$action_name,$additional_data){
		$command = new Command($name,$module_name,$action_name,$additional_data);
		$commandparser = new CommandParser($this);
		return $commandparser::execute($command);
	}

	/**
	 * --- Gets a new command and then executes it
	 */
	public function doInTime_Command($command){
		$commandparser = new CommandParser($this);
		return $commandparser::execute($command);
	}

	/**
	 * --- Gets the name parameters of a command and then makes it and adds it to the stack
	 */
	public function __call($method, $params){
		$method_parts = explode("_", $method);
		if ($method_parts[0]=="AddCommand"||$method_parts[0]=="addcommand") {
			if (sizeof($method_parts)==3) {
				$this->create_and_add_command("dynamic_command",$method_parts[1],$method_parts[2],$params[0]);
			}else{
				throw new \Core\Service\KonjedException('CommandHandler Exception: unacceptable dynamic command! Command String : '.$method);
			}
		}
	}

}

?>