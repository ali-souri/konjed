<?php

require_once 'ClassTemplate.php';
require_once 'ClassField.php';
require_once 'ClassFunction.php';
require_once 'DbHandler.php';
require_once __DIR__.'/../resource/db.php';
require_once __DIR__.'/../resource/classfile.php';

function model_handle_command($command_action){
	$class_template = new CT();
	$continue_of_query = TRUE;
	if ($command_action=="create") {
		echo "OK! lets create an amazing model object:\n";
		do{
			if ($class_template->class_name=="") {
				echo "---------------------------- Model Name: ----------------------------------\n";
				$class_template->class_name = readline("Please enter a name for model object and database table:  ");//check corrent name
				echo "Nice! You will have a greate class with name: ".$class_template->class_name."\n Lets go for define class fields and database column names: \n";
			}else{
				echo "---------------------------- Field Define: ----------------------------------\n";
				$field_name = readline("Please enter a name for model class field and database table column name:  ");//check corrent and not empty name
				$field_type = readline("Please enter type of field data for model class field and database table column [available values:'string' and 'int']:  ");//check corrent and not empty name
				$class_template->class_fields[] = new Field($field_name,$field_type);
				$continue = readline("Do you want to continue to define new fields?[available 'y' for yes and 'n' for no]:  ");//check corrent and not empty name
				if ($continue=="n") {
					$continue_of_query = FALSE;
				}
			}
		}while ($continue_of_query);

			$save = readline("Definition of model ".$class_template->class_name." is completed. Do you want to save it on DB and make its model object?[available 'y' for yes and 'n' for no]:  ");//check corrent and not empty name
			if ($save=="y") {
				$functions_string = "";
				// foreach ($class_template->class_fields as $value) {
				// 	$functions_string.=$class_template->getSetterAndGetterFunctionString($value->get_field_name());
				// }
				// $class_template->$class_functions = $functions_string;
				if (write_on_db($class_template,"create")) {
					if(write_class($class_template,"create")){
						echo "Congratulations! the object and table successfully installed.\n";	
					}else{
						echo "Sorry! can't save the model file.\n";	
					}
				}else{
					echo "Unable to write object on db.\n";
				}

			}

	}else{
		echo "unknown action detected!\n";
	}
}

?>