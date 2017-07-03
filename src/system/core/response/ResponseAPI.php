<?php

namespace Core\Response;

require_once __DIR__ . "/../Loader.php";
\Loader::load("all");

require_once __DIR__ . "/../../service/config.php";

/**
 * --- This class is used for making responses for client
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Response
 */
class ResponseAPI
{
	
	 /**
     * --- empty constructor of class
     */
	function __construct(){}

	/**
     * --- This function make a simple echo of html
     * @param string $html The html to be echoed
     */
	public function echo_html($html){
		print_r($html);
	}

	/**
     * --- This function makes a simple echo of string
     * @param string $input_string The string to be echoed
     */
	public function echo_string($input_string){
		echo($input_string);
	}	

	/**
     * --- This function makes a simple echo of file with its specific content type
     * @param string $view_folder_path The path of view folder
     * @param string $fils_name The name of the file
     */
	public function echo_view_file($view_folder_path,$fils_name){
		// $mime_type = mime_content_type (__DIR__."/../../../view/".$view_folder_path."/".$fils_name);
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime_type = finfo_file($finfo,__DIR__."/../../../view/".$view_folder_path."/".$fils_name);
		finfo_close($finfo);
		header("Content-Type: ".$mime_type);
		$file = file_get_contents(__DIR__."/../../../view/".$view_folder_path."/".$fils_name);
		// echo($mime_type);
		echo($file);
		exit;
	}

	/**
     * --- This function makes a customized json of konjed
     * @param bool $status_boolean The bool which shows the status
     * @param string $fils_name The name of the file
     */
	public function status_json_echo($status_boolean,$message){
		$output_array = array('status'=>$status_boolean,'message'=>$message);
		$this->json_echo_from_array($output_array);
	}

	/**
     * --- This function makes a json from array
     * @param array $input_array The array to be json_encoded
     */
	public function json_echo_from_array($input_array){
		header('Content-Type: application/json');
		echo json_encode($input_array);
		exit;
	}

	/**
     * --- Page regresh
     */
	public function refresh_page(){
		header("Refresh:0");
	}

	/**
     * --- Page redirect
     */
	public function redirect_to_path($route){
		$json_variables = get_system_config();
		$array_variables = json_decode($json_variables,true);
		$main_route = system_config['index_address'];
		$this->redirect($main_route.$route);
	}

	/**
     * --- Page redirect
     */
	public function redirect($address){
		header('Location: '.$address);
		exit;
	}

}

?>