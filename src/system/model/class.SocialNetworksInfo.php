<?php
namespace Core\Model;

use Core\Service\DB as DB;


class SocialNetworksInfo
{
  private $id = 0; public function get_id(){return $this->id;}
  private $user_id = ""; public function get_user_id(){return $this->user_id;} public function set_user_id($user_id){ $this->user_id=$user_id; }
  public function set_id($id){ $this->id=$id; }  private $social_name = ""; public function get_social_name(){return $this->social_name;}
  public function set_social_name($social_name){ $this->social_name=$social_name; }  private $username = ""; public function get_username(){return $this->username;}
  public function set_username($username){ $this->username=$username; }  private $password = ""; public function get_password(){return $this->password;}
  public function set_password($password){ $this->password=$password; }  private $token = ""; public function get_token(){return $this->token;}
  public function set_token($token){ $this->token=$token; }  private $network_user_id = ""; public function get_network_user_id(){return $this->network_user_id;}
  public function set_network_user_id($network_user_id){ $this->network_user_id=$network_user_id; }  private $network_secret = ""; public function get_network_secret(){return $this->network_secret;}
  public function set_network_secret($network_secret){ $this->network_secret=$network_secret; }  private $post_count = ""; public function get_post_count(){return $this->post_count;}
  public function set_post_count($post_count){ $this->post_count=$post_count; }

  private $folowers_count = "";
  public function get_folowers_count(){return $this->folowers_count;}
  public function set_folowers_count($folowers_count){ $this->folowers_count=$folowers_count; }


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
} public function __constructAll($id , $user_id, $social_name, $username, $password, $token, $network_user_id, $network_secret, $post_count , $folowers_count){ $this->id=$id; $this->user_id = $user_id; $this->social_name=$social_name;$this->username=$username;$this->password=$password;$this->token=$token;$this->network_user_id=$network_user_id;$this->network_secret=$network_secret;$this->post_count=$post_count; $this->folowers_count=$folowers_count;}
public static function construct_from_array($fields_array) {return new SocialNetworksInfo($fields_array['id'],$fields_array['user_id'], $fields_array['social_name'], $fields_array['username'], $fields_array['password'], $fields_array['token'], $fields_array['network_user_id'], $fields_array['network_secret'], $fields_array['post_count'] ,$fields_array['folowers_count']);} public static function construct_from_db($id)
{
$db = new DB();
$object_array = $db->select_by_id("SocialNetworksInfo", $id);
$db->close();
$object = new SocialNetworksInfo($object_array['id'] , $object_array['user_id'] , $object_array['social_name'], $object_array['username'], $object_array['password'], $object_array['token'], $object_array['network_user_id'], $object_array['network_secret'], $object_array['post_count'] ,$object_array['folowers_count']);
return $object;
}
public function save()
{
$db = new DB();
if (($db->check_record_exists("SocialNetworksInfo", $this->id)) && ($this->id != "")) {
$return_value = $db->update("SocialNetworksInfo", array('id' => $this->id, 'user_id' => $this->user_id ,'social_name' => $this->social_name, 'username' => $this->username, 'password' => $this->password, 'token' => $this->token, 'network_user_id' => $this->network_user_id, 'network_secret' => $this->network_secret, 'post_count' => $this->post_count , 'folowers_count' => $this->folowers_count));

$db->close();
return $return_value;

} else {
$return_value = $db->insert("SocialNetworksInfo" ,array('id' => $this->id , 'user_id' => $this->user_id, 'social_name' => $this->social_name, 'username' => $this->username, 'password' => $this->password, 'token' => $this->token, 'network_user_id' => $this->network_user_id, 'network_secret' => $this->network_secret, 'post_count' => $this->post_count , 'folowers_count'=> $this->folowers_count));

$db->close();
return $return_value;
}
}public function delete()
{
$db = new DB();
if (($db->check_record_exists("SocialNetworksInfo", $this->id)) && ($this->id != "")) {
$return_value = $db->delete("SocialNetworksInfo", $this->id);

$db->close();
return $return_value;
} else {
return FALSE;
}
}}