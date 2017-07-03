<?php

namespace Core\Sculptor;

include_once __DIR__."/../../../../vendor/autoload.php";

/**
 * --- Sculptor source connector class
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Sculptor
 */
class SculptorAPI
{
	
	/**
     * --- empty constructor of class
     */
	function __construct() {

	}

	/**
     * --- This function validates a command
     * @return Sculptor The Sculptor application object
     */
	public function get_sculptor_app(){
		return new \Sculptor\Sculptor();
	}



}