<?php

namespace Core;

require_once __DIR__.'/core/Loader.php';
\Loader::load("all");
require_once __DIR__.'/service/config.php';
require_once __DIR__.'/CommandHandler.php';

require 'Router.php';

/**
 * --- Core class is the gateway to all Core components
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core
 */
class Core{
	
	/**
	 * --- empty constructor of class
	 */	
	function __construct(){}


	/**
	 * --- This function first gets matched controller data and its metadata and then all selected functions from it and their metadata and finally executes them
	 * @param string[] $rest_data_array Array of all rest request elements in string
	 * @param string[] $additional_data_array Array of all xhr request elements in string
	 * @return null in case of error
	 */
	public function core_controller_logic_init($rest_data_array,$additional_data_array){

		try{
			
			if ($rest_data_array[0][0]=="unit-test") {
				$this->test_controller_init("web",$rest_data_array,$additional_data_array);
				return;
			}
			$router = new Router();
			$rest_data_string = "/".implode($rest_data_array[0],"/");
			$controller_object_name = $router->get_route_matched_controller_object_name($rest_data_string);

			if ($controller_object_name) {
			
				$controller_class_name = "\\Controller\\".$controller_object_name;

				$controller = new $controller_class_name;

				do{
					$controller_function_data = $router->get_route_matched_controller_function_data($controller,$rest_data_string);

					if (array_key_exists("Usage",$controller_function_data["metadata"])) {

						if ($controller_function_data["metadata"]['Usage']=="web") {
							
							if ($controller_function_data) {

								$func_result_commands = $this->controller_function_execute($controller,$controller_function_data,$additional_data_array);

								if (!is_null($func_result_commands)&&($func_result_commands!=FALSE)) {
									$CommandParser = new Command\CommandParser($func_result_commands);
									if ($CommandParser->StackExecute()) {
										// fully commands executed
									}else{
										//error handling
									}
								}else{
									//error handling
								}

							}else{
								throw new \Core\Service\KonjedException('Core Exception: '. Service\Error::get_message("no_such_controller_function"));
							}

						}
					}

					
				}while(!$controller_function_data['full_filled']);

			}else{
				echo Service\Error::get_message("404_not_found");
			}

		}catch(Service\KonjedException $e){
			throw $e;
		}

		

	}

	/**
	 * --- This function handles unit test actions in controller when user enter unit-test in url
	 * @param string $from_usage web in case of development
	 * @param string[] $rest_data_array Array of all rest request elements in string
	 * @param string[] $additional_data_array Array of all xhr request elements in string
	 * @return null in case of error
	 */
	private function test_controller_init($from_usage,$rest_data_array,$additional_data_array){
		if ($from_usage=="web") {
			if (!development_config["web-test"]) {
				echo "you are not allowded to use web test.";
				return;
			}
		}
		$router = new Router();
		$rest_data_string = str_replace("unit-test", "", implode($rest_data_array[0],"/"));
		$controller_object_name = $router->get_route_matched_controller_object_name($rest_data_string);

		if ($controller_object_name) {
		
			$controller_class_name = "\\Controller\\".$controller_object_name;

			$controller = new $controller_class_name;

			$controller_data = $router->get_route_matched_controller_object_metadata($rest_data_string);

			if ($controller_data["metadata"]["BaseRoute"]==$rest_data_string) {
				echo "\n starting complete test of ".$rest_data_string." controller : \n<br/>";
				$CommandHandler = new Command\CommandHandler();
				if ($CommandHandler->doInTime("web unit test","Test","ControllerFileCompleteTest",array("class_file_name"=>$controller_data["class_file_name"]))) {
					echo "<br/> \n complete test of ".$rest_data_string." controller is finished. \n<br/>";
				}else{
					echo "\n <h1>there is an error in complete test of ".$rest_data_string." controller </h1> <br/>\n";
				}
				return;
			}

			do{
				$controller_function_data = $router->get_route_matched_controller_function_data($controller,$rest_data_string);
				if ($controller_function_data) {
					$controller_tests_function_data = $router->get_route_matched_controller_function_test_data($controller,$rest_data_string);
					// var_dump($controller_tests_function_data);
					if (sizeof($controller_tests_function_data)&&$controller_tests_function_data) {
						$CommandHandler = new Command\CommandHandler();
						foreach ($controller_tests_function_data as $method) {
							if ($CommandHandler->doInTime("web unit test","Test","ControllerFileMethodTest",array("class_name"=>$controller_data["class_name"],"class_file_name"=>$controller_data["class_file_name"],"method_name"=>$method['name']))) {
								echo "<br/> \n complete test of ".$method['name']." method in ".$rest_data_string." controller is finished. \n<br/>";
							}else{
								echo "\n <h1>there is an error in method test of ".$rest_data_string." controller </h1> <br/>\n";
							}
						}
					}

					// complete with /test in rest data
					// if (array_key_exists("OptionalRoute", $controller_function_data["metadata"])||($controller_function_data["metadata"]["Usage"]!="test")) {
						
					// }

				}else{
					throw new \Core\Service\KonjedException('Core Exception: '. Service\Error::get_message("no_such_controller_function"));
				}
			}while(!$controller_function_data['full_filled']);

		}else{
			echo Service\Error::get_message("404_not_found");
		}
	}


	/**
	 * --- This function execute every selected function from controllers
	 * @param Object $controller An instanse of corresponding controller class
	 * @param string[] $function_data Metadata of Corresponding function of controller
	 * @param string[] $additional_data Array of all xhr request elements in string
	 * @return CommandHandler The CommandHandler from controller class function 
	 */
	private function controller_function_execute($controller,$function_data,$additional_data){
		$function_name = $function_data['name'];
		$function_route = $function_data['metadata']['Route'];
		$rest_data_array_named = null;
		$function_input_names_array = null;
		$function_input_array = array();
		if (!is_null($function_data['rest_data'])) {
			$rest_data_array_named = $this->getNamedDataByRouteArray($function_route,$function_data['rest_data']);
			if (!$rest_data_array_named) {
				if (array_key_exists('OptionalRoute', $function_data['metadata'])) {
					$rest_data_array_named = $this->getNamedDataByRouteArray($function_data['metadata']['OptionalRoute'],$function_data['rest_data']);	
				}
			}	
			$function_input_names_array = $this->getClassFunctionArguments(get_class($controller),$function_name);
		}else if (!empty($additional_data)) {
			$function_input_names_array = $this->getClassFunctionArguments(get_class($controller),$function_name);
		}
		if (($rest_data_array_named!=null)&&($function_input_names_array!=null)) {
			foreach ($function_input_names_array as $input_name) {
				if ($input_name=="extra_data") {
					$function_input_array[] = $additional_data;
					continue;
				}
				$function_input_array[] = $rest_data_array_named[$input_name];
			}
		}else if ($function_input_names_array!=null) {
			$function_input_array[] = $additional_data;
		}
		return call_user_func_array(array($controller,$function_name),$function_input_array);
	}

	/**
	 * --- this function makes an array of routs as keys and their corresponding value from rest as pairs
	 * @param string $route_string The string of route
	 * @param string $rest_string The string of mad rest by user
	 * @return False if the route and rest strings dont mach
	 * @return string[] $data_routed_array The array with keys from routes and their corresponding values from input rest data
	 */
	private function getNamedDataByRouteArray($route_string,$rest_string){
		$data_routed_array=array();
		$route_vars = explode("/",$route_string);
		$rest_vars = explode("/",$rest_string);
		if (count($route_vars)>count($rest_vars)) {
			return FALSE;
		}
		for ($i=0; $i < count($route_vars); $i++) { 
			if (substr($route_vars[$i],0,1)==":") {
				$data_routed_array[substr($route_vars[$i],1)]=$rest_vars[$i];
			}
		}
		return $data_routed_array;
	}

	/**
	 * --- This function retrieves all metadata from a controller function
	 * @param string $class_name_with_namespace Controller class name with its namespace
	 * @param string $func_name The name of selected function of controller
	 * @return null if there is no metadata for considered function
	 * @return string[] $func_input_variable_names The array with metadata of function
	 */
	private function getClassFunctionArguments($class_name_with_namespace,$func_name){
		$func_input_variable_names = array();
		$ref = new \ReflectionMethod($class_name_with_namespace,$func_name);
		if (count($ref->getParameters())==0) {
			return null;
		}
		foreach ($ref->getParameters() as $number => $input_variable_array) {
			$func_input_variable_names[] = $input_variable_array->name;
		}
		return $func_input_variable_names;
	}

}

?>