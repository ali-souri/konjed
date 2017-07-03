<?php
namespace Core\Model;

use Core\Service\DB as DB;


class Gallery
{
  private $id = 0; public function get_id(){return $this->id;}
					  public function set_id($id){ $this->id=$id; }  private $name = ""; public function get_name(){return $this->name;}
					  public function set_name($name){ $this->name=$name; }  private $thumbnail = ""; public function get_thumbnail(){return $this->thumbnail;}
					  public function set_thumbnail($thumbnail){ $this->thumbnail=$thumbnail; }  private $headline = ""; public function get_headline(){return $this->headline;}
					  public function set_headline($headline){ $this->headline=$headline; }  private $title = ""; public function get_title(){return $this->title;}
					  public function set_title($title){ $this->title=$title; }  private $description = ""; public function get_description(){return $this->description;}
					  public function set_description($description){ $this->description=$description; }  private $type = ""; public function get_type(){return $this->type;}
					  public function set_type($type){ $this->type=$type; }  private $views_number = 0; public function get_views_number(){return $this->views_number;}
					  public function set_views_number($views_number){ $this->views_number=$views_number; }  private $time = ""; public function get_time(){return $this->time;}
					  public function set_time($time){ $this->time=$time; } /**
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
} public function __constructAll($id, $name, $thumbnail, $headline, $title, $description, $type, $views_number, $time ){ $this->id=$id;$this->name=$name;$this->thumbnail=$thumbnail;$this->headline=$headline;$this->title=$title;$this->description=$description;$this->type=$type;$this->views_number=$views_number;$this->time=$time; }
public static function construct_from_array($fields_array) {return new Gallery($fields_array['id'], $fields_array['name'], $fields_array['thumbnail'], $fields_array['headline'], $fields_array['title'], $fields_array['description'], $fields_array['type'], $fields_array['views_number'], $fields_array['time'] );} public static function construct_from_db($id)
{
$db = new DB();
$object_array = $db->select_by_id("Gallery", $id);
$db->close();
$object = new Gallery($object_array['id'], $object_array['name'], $object_array['thumbnail'], $object_array['headline'], $object_array['title'], $object_array['description'], $object_array['type'], $object_array['views_number'], $object_array['time'] );
return $object;
}
public function save()
{
$db = new DB();
if (($db->check_record_exists("Gallery", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
$return_value = $db->update("Gallery", array('id' => $this->id, 'name' => $this->name, 'thumbnail' => $this->thumbnail, 'headline' => $this->headline, 'title' => $this->title, 'description' => $this->description, 'type' => $this->type, 'views_number' => $this->views_number, 'time' => $this->time));

$db->close();
return $return_value;

} else {
$return_value = $db->insert("Gallery" ,array('id' => $this->id, 'name' => $this->name, 'thumbnail' => $this->thumbnail, 'headline' => $this->headline, 'title' => $this->title, 'description' => $this->description, 'type' => $this->type, 'views_number' => $this->views_number, 'time' => $this->time));

$db->close();
return $return_value;
}
}public function delete()
{
$db = new DB();
if (($db->check_record_exists("Gallery", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
$return_value = $db->delete("Gallery", $this->id);

$db->close();
return $return_value;
} else {
return FALSE;
}
}}