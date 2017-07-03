<?php

namespace Core\Object;

require_once __DIR__."/../Loader.php";
\Loader::load("all");

use Core\Model as Model;

/**
 * --- This class is designed for handling objects specially Models
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Object
 */
class Object{

	//available objects
	/**
	 * --- empty constructor of class
	 */
	function __construct(){}

	/**
	 * --- This function makes the specific model objects From DB
	 * @param string $objects_name The name of considered model object
	 * @return array The Objects retrieved from Models
	 */
	public function getAllObjects($objects_name){

		$db = new \Core\Service\DB();
		$objects_db_array = $db->getAllTableRecords($objects_name);
		$return_objects = array();

		foreach ($objects_db_array as $key => $value) {
			$object_name = "Core\\Model\\$objects_name";
			$object = new $object_name();
			$return_objects[$key] = $object::construct_from_db($value['id']);
		}
		return $return_objects;
	}

	/**
	 * --- This function makes the specific model object From DB by its id
	 * @param string $objects_name The name of considered model object
	 * @param string $id The id of considered model object
	 * @return object The Object retrieved from Models
	 */
	public function getModelObjectWithId($object_name,$id){

		$object_name = "Core\\Model\\".$object_name;
		$object = new $object_name();
		return $object::construct_from_db($id);

	}

	/**
	 * --- This function makes the specific empty model object
	 * @param string $objects_name The name of considered model object
	 * @return array The Object from Models
	 */
	public static function CreateModelObject($object_name){
        $object_name = "Core\\Model\\".$object_name;
        return new $object_name();
	}

	/**
	 * --- This function returns all of the classes in a specified php file
	 * @param string $filepath The path of php file
	 * @return array Classes in the considered php file
	 */
	public function file_get_php_classes($filepath) {
	  $php_code = file_get_contents($filepath);
	  $classes = $this->get_php_classes($php_code);
	  return $classes;
	}

	/**
	 * --- This function returns all of the classes in a text of php file
	 * @param string $php_code The code of php file
	 * @return array Classes in the considered php file
	 */
	private function get_php_classes($php_code) {
	  $classes = array();
	  $tokens = token_get_all($php_code);
	  $count = count($tokens);
	  for ($i = 2; $i < $count; $i++) {
	    if (   $tokens[$i - 2][0] == T_CLASS
	        && $tokens[$i - 1][0] == T_WHITESPACE
	        && $tokens[$i][0] == T_STRING) {

	        $class_name = $tokens[$i][1];
	        $classes[] = $class_name;
	    }
	  }
	  return $classes;
	}


}

?>