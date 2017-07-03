<?php 

function write_class($class_template,$action_type){
	if ($action_type=="create") {
		$php_text = $class_template->render();
		$classfile = fopen(__DIR__."/../../src/system/model/class.".$class_template->class_name.".php", "w");
		$result = fwrite($classfile, $php_text);
		$close_result = fclose($classfile);
		if ($result!=FALSE) {
			return $close_result;
		}else {
			return FALSE;
		}
	}else{
		echo "unknown filesystem action!";
	}
}