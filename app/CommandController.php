<?php

require_once 'CommandRoute.php';

function command_handle($command_arg_array){
	$command_route_key = determine_command_type($command_arg_array[1]);
	$command_values = strstr($command_arg_array[1],"::");
	route_command($command_route_key,$command_values);
}

function determine_command_type($system_command_argument){
	$command_type = strstr($system_command_argument,"::",true);
	if ($command_type=="") {
		echo "command is not a typical command. \n";
		return FALSE;
	}else{
		return $command_type;
	}
}

?>