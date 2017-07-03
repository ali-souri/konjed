<?php
namespace Core\Model;

use Core\Service\DB as DB;


class Comment
{
  private $id = 0; public function get_id(){return $this->id;}
					  public function set_id($id){ $this->id=$id; }  private $author = ""; public function get_author(){return $this->author;}
					  public function set_author($author){ $this->author=$author; }  private $text = ""; public function get_text(){return $this->text;}
					  public function set_text($text){ $this->text=$text; }  private $post_id = 0; public function get_post_id(){return $this->post_id;}
					  public function set_post_id($post_id){ $this->post_id=$post_id; }  private $time = ""; public function get_time(){return $this->time;}
					  public function set_time($time){ $this->time=$time; } 
					  private $confirm = ""; public function get_confirm(){return $this->confirm;}
					  public function set_confirm($time){ $this->confirm=$confirm; } 
					  /**
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
} public function __constructAll($id, $author, $text, $post_id, $time ,$confirm){ $this->id=$id;$this->author=$author;$this->text=$text;$this->post_id=$post_id;$this->time=$time;$this->confirm=$confirm; }
public static function construct_from_array($fields_array) {return new Comment($fields_array['id'], $fields_array['author'], $fields_array['text'], $fields_array['post_id'], $fields_array['time'] ,$fields_array['confirm'] );} public static function construct_from_db($id)
{
$db = new DB();
$object_array = $db->select_by_id("Comment", $id);
$db->close();
$object = new Comment($object_array['id'], $object_array['author'], $object_array['text'], $object_array['post_id'], $object_array['time'] , $object_array['confirm'] );
return $object;
}
public function save()
{
$db = new DB();
if (($db->check_record_exists("Comment", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
$return_value = $db->update("Comment", array('id' => $this->id, 'author' => $this->author, 'text' => $this->text, 'post_id' => $this->post_id, 'time' => $this->time , 'confirm' => $this->confirm));

$db->close();
return $return_value;

} else {
$return_value = $db->insert("Comment" ,array('id' => $this->id, 'author' => $this->author, 'text' => $this->text, 'post_id' => $this->post_id, 'time' => $this->time , 'confirm' => $this->confirm));

$db->close();
return $return_value;
}
}public function delete()
{
$db = new DB();
if (($db->check_record_exists("Comment", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
$return_value = $db->delete("Comment", $this->id);

$db->close();
return $return_value;
} else {
return FALSE;
}
}}