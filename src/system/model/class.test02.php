<?php
namespace Core\Model;

use Core\Service\DB as DB;


class test02
{
  private $id = 0; public function get_id(){return $this->id;}
					  public function set_id($id){ $this->id=$id; }  private $name = ""; public function get_name(){return $this->name;}
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
} public function __constructAll($id, $name ){ $this->id=$id;$this->name=$name; }
public static function construct_from_array($fields_array) {return new test02($fields_array['id'], $fields_array['name'] );} public static function construct_from_db($id)
{
$db = new DB();
$object_array = $db->select_by_id("test02", $id);
$db->close();
$object = new test02($object_array['id'], $object_array['name'] );
return $object;
}
public function save()
{
$db = new DB();
if (($db->check_record_exists("test02", $this->id)) && ($this->id != "")) {
$return_value = $db->update("test02", array('id' => $this->id, 'name' => $this->name));

$db->close();
return $return_value;

} else {
$return_value = $db->insert("test02" ,array('id' => $this->id, 'name' => $this->name));

$db->close();
return $return_value;
}
}public function delete()
{
$db = new DB();
if (($db->check_record_exists("test02", $this->id)) && ($this->id != "")) {
$return_value = $db->delete("test02", $this->id);

$db->close();
return $return_value;
} else {
return FALSE;
}
}}