<?php

namespace Core\Object;

include_once __DIR__."/../../../../vendor/autoload.php";
require_once __DIR__.'/../Loader.php';
\Loader::load("all");

/**
 * --- This class is designed for reading metadata of classes and functions
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Object
 */
class Annotation{

	/**
	 * --- empty constructor of class
	 */
	function __construct(){}

	/**
	 * --- This function gets the metadata of a considered class
	 * @param string $class_path The path address of class
	 * @param string $class_file_name The name of the class
	 * @return array the parameters of class
	 */
	public function get_class_metadata_array($class_path,$class_file_name,$namespace){
		$object = new Object();
		$class_object = $object->file_get_php_classes($class_path.$class_file_name);
		$reader = new \DocBlockReader\Reader($namespace.$class_object[0]);
		return $reader->getParameters();
	}

	/**
	 * --- This function gets the metadata of a considered function
	 * @param string $class_path The path address of class
	 * @param string $class_file_name The name of the class
	 * @param string $class_function_name The name of function in class
	 * @param string $namespace The namespace of class
	 * @return array the parameters of function
	 */
	public function get_class_function_metadata_array($class_path,$class_file_name,$class_function_name,$namespace){
		$object = new Object();
		$class_object = $object->file_get_php_classes($class_path.$class_file_name);
		$reader = new \DocBlockReader\Reader($namespace.$class_object[0],$class_function_name);
		return $reader->getParameters();
	}

}