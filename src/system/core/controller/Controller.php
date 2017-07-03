<?php

namespace Core\Controller;

require_once __DIR__."/../../../../vendor/autoload.php";
require_once __DIR__."/../Loader.php";
\Loader::load("all");
require_once __DIR__."/../../CommandHandler.php";

/**
 * --- Controller class sets a bunch of abilities for controller classes
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Controller
 */
class Controller extends \PHPUnit_Framework_TestCase{
// class Controller{

	// protected $action = \Core\Command\CommandHandler;

	/** @var CommandHandler the CommandHandler object for every controller function */
	protected $action = null;

	/** @var Sculptor the Sculptor object for every controller */
	protected $sculptor = null;

	/**
     * --- Just initiate the action field of class
     */
	function __construct()
	{
		$this->action = new \Core\Command\CommandHandler();
		$this->sculptor = new \Sculptor\Sculptor();
	}

	/**
     * --- Makes a system command
     */
	protected static function build_command($name,$module_name,$action_name,$additional_data){
		return new Command($name,$module_name,$action_name,$additional_data);
	}

	/**
     * --- This function makes an access to all commands according to command rules
     * @return object Of the all commands information
     */
	protected function Command(){
		$commands_usable_array = array();
		$command_rules = new \Core\Command\CommandRules();
		$command_rules_array = $command_rules->get_command_modules_info();
		foreach ($command_rules_array as $module_name => $module_array) {
			$actions_array = $module_array["actions"];
			foreach ($actions_array as $action_api_array) {
				$ins_array = array();
				foreach ($action_api_array["functions"] as $key => $action_method_array) {
					$ins_array[$action_method_array['name']] = new \Core\Command\Command("Implicit_Method_Call",$module_name,$action_method_array['name'],array());
				}
				$commands_usable_array[$module_name] = (object)$ins_array;
			}
		}
		return (object)$commands_usable_array;
	}

}
