<?php

require_once __DIR__.'/../../src/system/core/command/CommandRules.php';
require_once __DIR__.'/../../src/system/CommandHandler.php';

function get_command_module_info($module_name){
	$command_rules = new \Core\Command\CommandRules();
	if (array_key_exists($module_name, $command_rules->get_command_modules_info())) {
		return $command_rules->get_command_modules_info()[$module_name];
	}else {
		return false;
	}
}

function execute_command($name,$module_name,$action_name,$additional_data){
	$command_handler = new \Core\Command\CommandHandler();
	return $command_handler->doInTime($name,$module_name,$action_name,$additional_data);
}