<?php

require_once 'model/Naration.php';
require_once 'system/Naration.php';

function route_command($command_route_key,$command_values){
	$command_values_array = explode("::",$command_values);
	$command_action = $command_values_array[1];

	if($command_route_key=="model"){
		model_handle_command($command_action);
	}else if ($command_route_key=="System") {
		system_handle_command($command_values_array);
	}else {
		echo "there is no module like ".$command_values_array[0]." or action like ".$command_action."\n";
	}

}