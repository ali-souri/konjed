<?php

namespace Core\Test;

require_once __DIR__ . "/../Loader.php";
\Loader::load("all");

/**
 * --- This class contains the core modules for unit testing
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Test
 */
class TestAPI
{

	/**
     * --- This function makes a test with executing shell script of phpunit source
     * @param string $class_name The name of the class which contains the test method
     * @return bool Just FALSE in case of error
     */
	public function ControllerCompleteTest($class_name){
		if (class_exists("\\Controller\\".$class_name)) {
			echo "\n". shell_exec("php vendor/bin/phpunit --bootstrap vendor/autoload.php src/controller/".$class_name) ."\n";
			return true;
		}else {
			echo "\n no such class \n";
			return false;
		}
	}

	/**
     * --- This function makes a test with executing shell script of phpunit source
     * @param string $class_file_name The name of the file containing the class which contains the test method
     * @return bool Just FALSE in case of error
     */
	public function ControllerFileCompleteTest($class_file_name){
		if (file_exists("src/controller/".$class_file_name)) {
			echo "\n". shell_exec("php vendor/bin/phpunit --bootstrap vendor/autoload.php src/controller/".$class_file_name) ."\n";
			return true;
		}else {
			echo "\n no such class file \n";
			return false;
		}
	}

	/**
     * --- This function makes a test with executing shell script of phpunit source
	 * @param string $class_name The name of the controller class which contains the test method
     * @param string $class_file_name The name of the file containing the controller class which contains the test method
     * @param string $method_name The name of the name of method for executing test on
     * @return bool Just FALSE in case of error
     */
	public function ControllerFileMethodTest($class_name,$class_file_name,$method_name){
		//phpunit --filter testSaveAndDrop EscalationGroupTest escalation/EscalationGroupTest.php
		if (class_exists("\\Controller\\".$class_name)&&file_exists("src/controller/".$class_file_name)) {
			echo "\n". shell_exec("php vendor/bin/phpunit --filter ".$method_name." ".$class_name." src/controller/".$class_file_name) ."\n";
			return true;
		}else {
			echo "\n no such class file or class \n";
			return false;
		}
	}

}