<?php

namespace Core\Command;

/**
 * --- Command class is an object whitch holds all of the information of cosidered system command
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Command
 */
class Command
{
	
	private $name = "";	
	private $module_name = "";	
	private $action_name = "";
	private $additional_data = array();	

	function __construct($name,$module_name,$action_name,$additional_data)
	{
		$this->name = $name;
		$this->module_name = $module_name;
		$this->action_name = $action_name;
		$this->additional_data = $additional_data;
	}
	
	public function get_name(){
		return $this->name;
	}

	public function get_module_name(){
		return $this->module_name;
	}

	public function get_action_name(){
		return $this->action_name;
	}

	public function get_additional_data(){
		return $this->additional_data;
	}

	public function set_name($name){
		$this->name = $name;
	}

	public function set_module_name($module_name){
		$this->module_name = $module_name;
	}

	public function set_action_name($action_name){
		$this->action_name = $action_name;
	}

	public function set_additional_data($additional_data){
		$this->additional_data = $additional_data;
	}

	/**
	 * --- This function sets the additional_data and then return the object
	 * @param array $additional_data the information of additional data of class
	 * @return object The class
	 */
	public function make($additional_data){
		$this->set_additional_data($additional_data);
		return $this;
	}

}

?>