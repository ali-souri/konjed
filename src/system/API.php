<?php
/**
 * Created by PhpStorm.
 * User: pooria
 * Date: 10/21/15
 * Time: 2:53 PM
 */

namespace Core;

require_once __DIR__."/core/Loader.php";
\Loader::load("all");

use Core\Object\Object as CoreObject;
use Core\Object\CRUD as CRUD;
use Core\Page\PageCore as PageHandler;


/**
 * --- API class is a gateway to some core modules like crud and generate page
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core
 */
class API {


	/**
	 * --- empty constructor of class
	 */
	function __construct(){}


	/**
	 * --- This function executes every write (insert and update and delete) action from CRUD
	 * @param string $action_name The name of CRUD action
	 * @param string $object_name The name of considered object for CRUD
	 * @param mixed[] $data The data for CRUD considered action
	 * @return FALSE in case of error
	 * @return bool from CRUD core module write function
	 */
	public function action_write_init($action_name,$object_name,$data){
		$security = new Object\Register();
		$object_handler = new CoreObject();
		$object = $object_handler->CreateModelObject($object_name);
		$crud = new CRUD($object);
		if(($security->check_write_permissions($action_name))&&($security->model_object_name_is_valid($object_name))) {
			return call_user_func_array(array($crud,$action_name),array(0=>$data));
		}else {
			if (!$security->check_write_permissions($action_name)) {
				echo Service\Error::get_message("non_allowed_write_object")."<br/>";	
			}
			if (!$security->model_object_name_is_valid($action_name)) {
				echo Service\Error::get_message("incorrect_model_object_name")."<br/>";	
			}
			return FALSE;
		}
	}


	/**
	 * --- This function executes every read action from CRUD
	 * @param string $object_name The name of considered object for CRUD
	 * @param mixed[] $data The data for CRUD considered action
	 * @return null in case of error
	 * @return Object[] Objects in result of read from database
	 */
	public function action_read_init($object_name,$data){
		$security = new Object\Register();
		$object_handler = new CoreObject();
		if ($security->model_object_name_is_valid($object_name)) {
			if ($data=="all") {
				return $object_handler->getAllObjects($object_name);
			}elseif (array_key_exists("id", $data)) {
				return $object_handler->getModelObjectWithId($object_name,$data['id']);
			}else{
				$object = $object_handler->CreateModelObject($object_name);
				$crud = new CRUD($object);
				return call_user_func_array(array($crud,"Read"),$data);
			}
		}else{
			if (!$security->model_object_name_is_valid($object_name)) {
				echo Service\Error::get_message("incorrect_model_object_name")."<br/>";	
			}
			return null;
		}
	}


	/**
	 * --- This function generates page from page table of db
	 * @param string $url_name url_name for db table column name of page
	 * @param string $additional_info additional information for creating page
	 * @return string The html of ordred page
	 */
	public function generate_page($url_name,$additional_info){
		$page_object = "";
		$page_generator = new PageHandler();
		if (array_key_exists("id", $additional_info)) {
			$page_html = $page_generator->CreatePageWithId($additional_info['id'],$additional_info);
		}else{//check if there is no page with special url_name
			$page_html = $page_generator->CreatePageWithUrlName($url_name,$additional_info);
		}
		return $page_html;
	}


	//create user, check access , <<user 

}

?>