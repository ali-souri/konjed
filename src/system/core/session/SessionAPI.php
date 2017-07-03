<?php

namespace Core\Session;

/**
 * --- Some tools for handling session
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Session
 */
class SessionAPI{

	function __construct(){}

	public function SessionStart(){
		session_start();
		exit;
	}

	public function SessionDestroy(){
		session_destroy();
		exit;
	}

	public function SessionSetVariable($key,$value){
		$_SESSION[$key]=$value;
		exit;
	}

	public function SessionGetVariable($key){
		return $_SESSION[$key];
	}



}