<?php
namespace Core\Model;

use Core\Service\DB as DB;


class Contact
{
  private $id = 0; public function get_id(){return $this->id;}
					  public function set_id($id){ $this->id=$id; }  private $name = ""; public function get_name(){return $this->name;}
					  public function set_name($name){ $this->name=$name; }  private $email = ""; public function get_email(){return $this->email;}
					  public function set_email($email){ $this->email=$email; }  private $phone = ""; public function get_phone(){return $this->phone;}
					  public function set_phone($phone){ $this->phone=$phone; }  private $subject = ""; public function get_subject(){return $this->subject;}
					  public function set_subject($subject){ $this->subject=$subject; }  private $text = ""; public function get_text(){return $this->text;}
					  public function set_text($text){ $this->text=$text; } /**
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
} public function __constructAll($id, $name, $email, $phone, $subject, $text ){ $this->id=$id;$this->name=$name;$this->email=$email;$this->phone=$phone;$this->subject=$subject;$this->text=$text; }
public static function construct_from_array($fields_array) {return new Contact($fields_array['id'], $fields_array['name'], $fields_array['email'], $fields_array['phone'], $fields_array['subject'], $fields_array['text'] );} public static function construct_from_db($id)
{
$db = new DB();
$object_array = $db->select_by_id("Contact", $id);
$db->close();
$object = new Contact($object_array['id'], $object_array['name'], $object_array['email'], $object_array['phone'], $object_array['subject'], $object_array['text'] );
return $object;
}
public function save()
{
$db = new DB();
if (($db->check_record_exists("Contact", $this->id)) && ($this->id != "")) {
$return_value = $db->update("Contact", array('id' => $this->id, 'name' => $this->name, 'email' => $this->email, 'phone' => $this->phone, 'subject' => $this->subject, 'text' => $this->text));

$db->close();
return $return_value;

} else {
$return_value = $db->insert("Contact" ,array('id' => $this->id, 'name' => $this->name, 'email' => $this->email, 'phone' => $this->phone, 'subject' => $this->subject, 'text' => $this->text));

$db->close();
return $return_value;
}
}public function delete()
{
$db = new DB();
if (($db->check_record_exists("Contact", $this->id)) && ($this->id != "")) {
$return_value = $db->delete("Contact", $this->id);

$db->close();
return $return_value;
} else {
return FALSE;
}
}}