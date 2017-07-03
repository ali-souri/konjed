<?php

class Field{

	private $field_name = "";
	private $field_type = "";

	function __construct($field_name,$field_type){
		$this->field_name = $field_name;
		$this->field_type = $field_type;
	}

	public function get_field_name(){
		return $this->field_name;
	}

	public function get_field_type(){
		return $this->field_type;
	}

	public function set_field_name($field_name){
		$this->field_name = $field_name;
	}

	public function set_field_type($field_type){
		$this->field_type = $field_type;
	}

}