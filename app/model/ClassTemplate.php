<?php

class CT{
	public $class_name = "";
	public $class_fields = array();
	public $class_default_functions_string =
<<<EOT
/**
* constructor for Page, that can have any number of input arguments,
*  and handle the Page object construction by calling the other constructors.
*/
function __construct()
{
\$a = func_get_args();
\$i = func_num_args();
if (\$i!=0) {
call_user_func_array(array(\$this,"__constructAll"),\$a); 
}
} 
/**
* Construct the Page object by initialize 'Some' of the class field with input array argument.
*
* @param \$custom_fields_array in Array format like: Array('name'=>'MyName' , 'template_id'=>'2', 'twig_name'=>'MyTwig') and etc.
*/
public function custom_construct(\$custom_fields_array){
foreach (\$custom_fields_array as \$key => \$value) {
\$this->\$key = \$value;
}
} 
/**
* make a json from Page fields.
*
* @return string in json format.
*/
public function tojson(){
return json_encode(get_object_vars(\$this),true);
}
EOT;
	public $class_functions = "";
	public $class_custom_functions = "";

	function __construct(){}

	public function render(){
		$template = 
"<?php
namespace Core\Model;

use Core\Service\DB as DB;


class ".$this->class_name."
{
 ";
		foreach ($this->class_fields as $value) {
			$name = $value->get_field_name();
			$type = $value->get_field_type();
			$template.=" private \$".$name." = ";
			if ($type=="string") {
				$template.="\"\";";
			}elseif ($type=="int") {
				$template.="0;";
			}
			$template.=$this->getSetterAndGetterFunctionString($value->get_field_name());
		}
		$template.=$this->class_functions;
		$template.=$this->class_default_functions_string;
		$template.=$this->generate_custom_functions($this->class_fields,$this->class_name);
		$template.="}";
		return $template;
	}

	public function getSetterAndGetterFunctionString($field_name){
		$template = " public function get_".$field_name."(){return \$this->$field_name;}
					  public function set_".$field_name."(\$".$field_name."){ \$this->$field_name=\$".$field_name."; } ";
		return $template;
	}

	public function generate_custom_functions($this_class_fields,$object_name){
$constructalldefenition = 
" public function __constructAll(";
$constructallbody =
" ){ ";
$construct_from_array_def = 
" }\npublic static function construct_from_array(\$fields_array) ";
$construct_from_array_body = 
"{return new ".$object_name."(";
$construct_from_db =
" );} public static function construct_from_db(\$id)
{
\$db = new DB();
\$object_array = \$db->select_by_id(\"".$object_name."\", \$id);
\$db->close();
\$object = new ".$object_name."(";
		$save = 
" );
return \$object;
}
public function save()
{
\$db = new DB();
if ((\$db->check_record_exists(\"".$object_name."\", \$this->id)) && (\$this->id != \"\")) {
\$return_value = \$db->update(\"".$object_name."\", array(";
$save2 = "));

\$db->close();
return \$return_value;

} else {
\$return_value = \$db->insert(\"".$object_name."\" ,array(";
$save_last = "));

\$db->close();
return \$return_value;
}
}";
$delete = "public function delete()
{
\$db = new DB();
if ((\$db->check_record_exists(\"".$object_name."\", \$this->id)) && (\$this->id != \"\")) {
\$return_value = \$db->delete(\"".$object_name."\", \$this->id);

\$db->close();
return \$return_value;
} else {
return FALSE;
}
}";
		$constructalldefenition_values = "";
		$constructallbody_values = "";
		$construct_from_array_body_values = "";
		$construct_from_db_values = "";
		$save_values = "";
		$save2_values = "";
		$count = count($this_class_fields);
			foreach ($this_class_fields as $value) {
				$name = $value->get_field_name();
				$constructalldefenition_values.="\$".$name;
				$constructallbody_values .= "\$this->".$name."=\$".$name.";";
				$construct_from_array_body_values.= "\$fields_array['".$name."']";
				$construct_from_db_values.="\$object_array['".$name."']";
				$save_values.="'".$name."' => \$this->".$name;
				$save2_values.="'".$name."' => \$this->".$name;
				if($count>1){
					$constructalldefenition_values.=", ";
					$construct_from_array_body_values.=", ";
					$construct_from_db_values.=", ";
					$save_values.=", ";
					$save2_values.=", ";
					$count--;
				}
			}
		return $constructalldefenition.$constructalldefenition_values.$constructallbody.$constructallbody_values.$construct_from_array_def.$construct_from_array_body.$construct_from_array_body_values.$construct_from_db.$construct_from_db_values.$save.$save_values.$save2.$save2_values.$save_last.$delete;
	}

}