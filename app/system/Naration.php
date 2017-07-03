<?php

require_once __DIR__.'/../resource/SystemCommand.php';

function system_handle_command($command_values){

	if ($command_values[1]=="Command") {
		$module_name = $command_values[2];
		if (array_key_exists(3, $command_values)) {
			$func_name = $command_values[3];
		}else{
			echo "please enter an action for command module.\n";
			return;
		}
		$command_name = "";
		$command_inputes_info_array = array();
		$module_info = get_command_module_info($module_name);
		if ($module_info) {
			$actions_array = $module_info['actions'];
			foreach ($actions_array as $api_array) {
				$api_functions_array = $api_array['functions'];
				foreach ($api_functions_array as $index => $functions_info_array) {
					if ($functions_info_array['name']==$func_name) {
						$command_name = $functions_info_array['name'];
						$command_inputes_info_array = $functions_info_array['inputs'];
						break;
					}
				}
			}

			if ($command_name==""){
				echo "there is no such action in your considered module.\n";
				return;
			}else{
				$input_filled_array = array();
				if (sizeof($command_inputes_info_array)) {
					echo "there is some inputs needed to be provided for this command by you: \n";
					foreach ($command_inputes_info_array as $index => $input_info_array) {
						echo "there is an input you must provide it's content with name : '".$input_info_array['name']."' ,";
						if ($input_info_array['type']=="string") {
								$input_filled_array[$input_info_array['name']] = readline("Please enter a value with styled type 'string' : \n");
						}elseif ($input_info_array['type']=="int") {
							$input_filled_array[$input_info_array['name']] = intval(readline("Please enter a value with styled type 'int' : \n"));
						}elseif ($input_info_array['type']=="bool"){
							$input_filled_array[$input_info_array['name']] = boolval(readline("Please enter a value with styled type 'bool' : \n"));
						}elseif ($input_info_array['type']=="array"){
							echo "you must enter string value of an array. ";
							$array_data_type = readline("which one of json or serialized typed data do you want to use for array values? [available json and serialize]: \n");
							if ($array_data_type=="json") {
								$input_filled_array[$input_info_array['name']] = json_decode(readline("Please enter a value with styled type 'json' : \n"),true);
							}elseif ($array_data_type=="serialize") {
								$input_filled_array[$input_info_array['name']] = unserialize(readline("Please enter a value with styled type 'serilized_array' : \n"));
							}else{
								echo "there is no support for this type of string data for array input please try again. \n";
							}
						}
					}
				}

			$result = execute_command("From Console",$module_name,$command_name,$input_filled_array);
				echo "The result of command is in type of ".gettype($result)." with value of : \n";
				if (gettype($result)=="string"||gettype($result)=="integer"||gettype($result)=="bool") {
					echo $result;
				}else{
					print_r($result);
				}
			echo "\n";
			}

			
		}else{
			echo "there is no such module of command in the system!\n";	
		}
	}else{
		echo "there is no such functionality in the system!\n";
	}
}

?>