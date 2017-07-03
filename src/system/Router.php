<?php

namespace Core;

require_once __DIR__.'/core/Register.php';
require_once __DIR__.'/core/Loader.php';
\Loader::load("all");

/**
 * --- Router class is the main tool of finding appropriate controller class and then function for every rest request
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core
 */
class Router{

	/** @var array Of the names of controllers */
	private $controller_names_array = array();
	/** @var string Name of the appropriate class for request */
	private $routed_class_name = "";
	/** @var string Of the base part of route */
	private $baseroute = "";
	/** @var string the analyzed part of route in every special moment */
	private $filled_route = "";
	/** @var string Name the file which contains the appropriate controller class */
	private $controller_file_name = "";

	/**
	 * --- Gets all of controllers names and then fills it to the considered variable
	 */	
	function __construct(){
		$system_repository = new Object\Register();
		$this->controller_names_array = $system_repository->get_controllers();
	}

	/**
	 * --- This function gets the string of route and then returns the name of corresponding class
	 * @param string $route_string The string of considered route
	 * @return FALSE in case of error
	 * @return string The name of selected class
	 */
	public function get_route_matched_controller_object_name($route_string){
		$metadata_handler = new Object\Annotation();
		foreach ($this->controller_names_array as $controller) {
			$class_metadata_array = $metadata_handler->get_class_metadata_array(__DIR__."/../controller/",$controller,"Controller\\");
			if ($this->route_match($route_string,$class_metadata_array['BaseRoute'])) {
				$this->baseroute = $class_metadata_array['BaseRoute'];
				$this->filled_route = $class_metadata_array['BaseRoute'];
				$object_handler = new Object\Object();
				$php_selected_classes_name = $object_handler->file_get_php_classes(__DIR__."/../controller/".$controller);
				$this->routed_class_name = $php_selected_classes_name[0];
				$this->controller_file_name = $controller;
				return $php_selected_classes_name[0];
			}
		}
		return FALSE;
	}

	/**
	 * --- This function gets the string of route and then returns the information of the selected class 
	 * @param string $route_string The string of considered route
	 * @return string[] The array which contains the information of the selected class
	 */
	public function get_route_matched_controller_object_metadata($route_string){
		$metadata_handler = new Object\Annotation();
		return ["class_file_name"=>$this->controller_file_name,"class_name"=>$this->routed_class_name,"metadata"=> $metadata_handler->get_class_metadata_array(__DIR__."/../controller/",$this->routed_class_name.".php","Controller\\")];
	}

	/**
	 * --- This function gets the string of route and also an instantiated controller class and then find the function of route and then returns the information of the selected function 
	 * @param object $controller_object The selected controller class object
	 * @param string $route_string The string of considered route
	 * @return string[] The array of function data
	 */
	public function get_route_matched_controller_function_data($controller_object,$route_string){
		$usable_route_string = str_replace($this->filled_route,"",$route_string);
		$reflector = new \ReflectionClass($controller_object);
		$controller_class_name = $reflector->getFileName();
		$routed_class_methods = get_class_methods($controller_object);
		$metadata_handler = new Object\Annotation();
		foreach ($routed_class_methods as $method) {
			// $function_metadata_array = $metadata_handler->get_class_function_metadata_array(__DIR__."/../controller/",$controller_class_name,$method,"Controller\\");
			if ($method!="__construct") {
				$function_metadata_array = $metadata_handler->get_class_function_metadata_array("",$controller_class_name,$method,"Controller\\");
				if ($function_metadata_array['Route']=="None") {
					if (($usable_route_string=="")&&($route_string==$this->baseroute)) {
						return $this->getFunctionData($method,$function_metadata_array,null,true);
					}elseif (array_key_exists("OptionalRoute", $function_metadata_array)) {
						if ($this->route_match($usable_route_string,$function_metadata_array['OptionalRoute'])) {
							$rest_routed_data = $this->route_pattern_substr($usable_route_string,$function_metadata_array['OptionalRoute']);
							$this->filled_route.=$rest_routed_data;
							return $this->getFunctionData($method,$function_metadata_array,$rest_routed_data,$this->filled_route==$route_string);
						}
					}
					continue;
				}
				if($this->route_match($usable_route_string,$function_metadata_array['Route'])){
					$rest_routed_data = $this->route_pattern_substr($usable_route_string,$function_metadata_array['Route']);
					$this->filled_route.=$rest_routed_data;
					return $this->getFunctionData($method,$function_metadata_array,$rest_routed_data,$this->filled_route==$route_string);
				}
			}
		}
		return FALSE;
	}

	/**
	 * --- This function gets the string of route and also an instantiated controller test class and then find the function of route and then returns the selected test function 
	 * @param object $controller_object The selected controller class object
	 * @param string $route_string The string of considered route
	 * @return function The test functions
	 */
	public function get_route_matched_controller_function_test_data($controller_object,$route_string){
		$usable_route_string = str_replace($this->filled_route,"",$route_string);
		$reflector = new \ReflectionClass($controller_object);
		$controller_class_name = $reflector->getFileName();
		$routed_class_methods = get_class_methods($controller_object);
		$metadata_handler = new Object\Annotation();
		$return_methods = array();
		// var_dump($routed_class_methods);
		foreach ($routed_class_methods as $method) {
			if ($method!="__construct") {
				$function_metadata_array = $metadata_handler->get_class_function_metadata_array("",$controller_class_name,$method,"Controller\\");
				if (!array_key_exists("Route", $function_metadata_array)){
					continue;
				}
				if ($function_metadata_array['Route']=="None") {
					if (($usable_route_string=="")&&($route_string==$this->baseroute)) {

						return $this->getFunctionData($method,$function_metadata_array,null,true);
					}elseif (array_key_exists("OptionalRoute", $function_metadata_array)) {
						$usable_route_string.=$function_metadata_array['OptionalRoute'];
						if ($this->route_match($usable_route_string,$function_metadata_array['OptionalRoute'])) {
							$rest_routed_data = $this->route_pattern_substr($usable_route_string,$function_metadata_array['OptionalRoute']);
							$this->filled_route.=$rest_routed_data;
							foreach ($routed_class_methods as $test_method) {
								if ($test_method!="__construct") {
									$test_function_metadata_array = $metadata_handler->get_class_function_metadata_array("",$controller_class_name,$test_method,"Controller\\");
									if (!array_key_exists("Route", $test_function_metadata_array)){
										continue;
									}
									// echo "<br/>";
									// echo "inja";
									// echo "<br/>";
									// var_dump(substr($test_function_metadata_array['Route'], 0,5));
									// echo "<br/>";
									// var_dump(substr($test_function_metadata_array['Route'], 5));
									// echo "<br/>";
									// var_dump($function_metadata_array['OptionalRoute']);
									if ((substr($test_function_metadata_array['Route'], 0,5)=="/test")&&("/".substr($test_function_metadata_array['Route'], 5)==$function_metadata_array['OptionalRoute'])) {
										// echo "inja1";
										$return_methods[] = $this->getFunctionData($test_method,$test_function_metadata_array,$rest_routed_data,$this->filled_route==$route_string);
									}
								}
							}
							return $return_methods;
						}
					}
					continue;
				}
				if($this->route_match($usable_route_string,$function_metadata_array['Route'])){
					
					$rest_routed_data = $this->route_pattern_substr($usable_route_string,$function_metadata_array['Route']);
					$this->filled_route.=$rest_routed_data;
					foreach ($routed_class_methods as $test_method) {
								if ($test_method!="__construct") {
									$test_function_metadata_array = $metadata_handler->get_class_function_metadata_array("",$controller_class_name,$test_method,"Controller\\");
									if ((substr($test_function_metadata_array['Route'], 0,5)=="/test")&&(substr($test_function_metadata_array['Route'], 0,5)==$function_metadata_array['Route'])) {
										$return_methods[] = $this->getFunctionData($test_method,$test_function_metadata_array,$rest_routed_data,$this->filled_route==$route_string);
									}
								}
							}
					return $return_methods;
				}
			}
		}
		return FALSE;
	}

	/**
	 * --- This function gets some information from function metadata and organizes it
	 */
	private function getFunctionData($method,$metadata,$rest_data,$full_filled){
		 return array("name"=>$method,"metadata"=>$metadata,"rest_data"=>$rest_data,"full_filled"=>$full_filled);
	}

	/**
	 * --- This function gets a substring from route patern
	 */
	private function route_pattern_substr($route_string,$route_patern){
		$route_patern_slashes_count = substr_count($route_patern,"/");
		return $this->substr_by_nth($route_string,"/",$route_patern_slashes_count+1);
	}

	/**
	 * --- This function determines if the given route matches with annotation route
	 * @param string $route_string The string of considered route
	 * @param object $annotation_route_string The selected element determined route in metadata
	 * @return TRUE|FALSE Whether the Route and annotation rute match or not
	 */
	private function route_match($route_string,$annotation_route_string){
		    $is_match = true;
			$route_parameters_array = explode("/", $route_string);
			$annotation_route_parameters_array = explode("/", $annotation_route_string);
			$annotation_route_parameters_count = count($annotation_route_parameters_array);
			$route_parameters_array_count = count($route_parameters_array);
			if ($annotation_route_parameters_count>$route_parameters_array_count) {
				return FALSE;
			}
			for ($i=0; $i < $annotation_route_parameters_count; $i++) { 
				if (substr($annotation_route_parameters_array[$i],0,1)!=":") {
					$is_match&=($annotation_route_parameters_array[$i]==$route_parameters_array[$i]);
				}
			}
			return $is_match;
	}

	private function get_sub_array_with_number($number_indexed_array,$number){
		$return_array = array();
		for ($i=0; $i < $number; $i++) { 
			$return_array[] = $number_indexed_array[$i];
		}
		return $return_array;
	}

	private function substr_by_nth($string,$needle,$nth){
		$max = strlen($string);
		$n = 0;
		for($i=0;$i<$max;$i++){
		    if($string[$i]==$needle){
		        $n++;
		        if($n>=$nth){
		            break;
		        }
		    }
		}
		$str = substr($string,0,$i);
		return $str;
	}



}

?>