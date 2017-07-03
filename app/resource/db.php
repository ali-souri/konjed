<?php

require_once __DIR__.'/../../src/system/service/class.db.php';

function write_on_db($class_template,$action_type){
	if ($action_type=="create") {
		$sql = "CREATE TABLE ".$class_template->class_name." (";
			$count = count($class_template->class_fields);
			foreach ($class_template->class_fields as $value) {
				$sql.=$value->get_field_name();
				if ($value->get_field_type()=="string") {
					$sql.=" varchar(255)";
				}elseif ($value->get_field_type()=="int") {
					$sql.=" int";
				}
				if ($count>1) {
					$sql.=", ";
					$count--;
				}
			}
		$sql.=") ;";
		$db = new Core\Service\DB();
		return $db->exec_write_sql($sql);
	}else{
		echo "unknown database action!\n";
	}
}