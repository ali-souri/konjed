<?php
namespace Core\Model;

use Core\Service\DB as DB;


class Category
{
  private $id = 0; public function get_id(){return $this->id;}
					  public function set_id($id){ $this->id=$id; }  private $page_id = 0; public function get_page_id(){return $this->page_id;}
					  public function set_page_id($page_id){ $this->page_id=$page_id; }  private $name = ""; public function get_name(){return $this->name;}
					  public function set_name($name){ $this->name=$name; } /**
* constructor for Page, that can have any number of input arguments,
*  and handle the Page object construction by calling the other constructors.
*/
function __construct()
{
$a = func_get_args();
$i = func_num_args();
if ($i!=0) {
call_user_func_array(array($this,"__constructAll"),$a); 
}
} 
/**
* Construct the Page object by initialize 'Some' of the class field with input array argument.
*
* @param $custom_fields_array in Array format like: Array('name'=>'MyName' , 'template_id'=>'2', 'twig_name'=>'MyTwig') and etc.
*/
public function custom_construct($custom_fields_array){
foreach ($custom_fields_array as $key => $value) {
$this->$key = $value;
}
} 
/**
* make a json from Page fields.
*
* @return string in json format.
*/
public function tojson(){
return json_encode(get_object_vars($this),true);
} public function __constructAll($id, $page_id, $name ){ $this->id=$id;$this->page_id=$page_id;$this->name=$name; }
public static function construct_from_array($fields_array) {return new Category($fields_array['id'], $fields_array['page_id'], $fields_array['name'] );} public static function construct_from_db($id)
{
$db = new DB();
$object_array = $db->select_by_id("Category", $id);
$db->close();
$object = new Category($object_array['id'], $object_array['page_id'], $object_array['name'] );
return $object;
}
public function save()
{
$db = new DB();
if (($db->check_record_exists("Category", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
$return_value = $db->update("Category", array('id' => $this->id, 'page_id' => $this->page_id, 'name' => $this->name));

$db->close();
return $return_value;

} else {
$return_value = $db->insert("Category" ,array('id' => $this->id, 'page_id' => $this->page_id, 'name' => $this->name));

$db->close();
return $return_value;
}
}public function delete()
{
$db = new DB();
if (($db->check_record_exists("Category", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
$return_value = $db->delete("Category", $this->id);

$db->close();
return $return_value;
} else {
return FALSE;
}
}}