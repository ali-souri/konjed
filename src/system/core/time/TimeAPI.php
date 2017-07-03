<?php

namespace Core\Time;

require_once __DIR__ ."/../../../../vendor/autoload.php";
require_once __DIR__ ."/../Loader.php";
\Loader::load("all");

/**
 * --- Some methods for time handling specially for shamsi time
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Time
 */
class TimeAPI{
	
	private $time_type = "jalali";//jalali or gregorian
	private $time_zone = "Asia/Tehran";

	function __construct(){//$type = null,$zone = null
		if (func_num_args()==2) {
			$function_args = func_get_args();
			if (!is_null($function_args[0])) {
				$this->time_type = $function_args[0];
			}
			if (!is_null($function_args[1])) {
				$this->time_zone = $function_args[1];
			}
		}
	}

	public function set_time_type($time_type){
		$this->time_type = $time_type;
	}

	public function set_time_zone($time_zone){
		$this->time_zone = $time_zone;
	}

	public function getDateWithFormat($format){
		if ($this->time_type=="jalali") {
			$time_object = new \jDateTime(true, true, $this->time_zone);
			return $time_object->date($format);
		}elseif ($this->time_type=="gregorian") {
			return date($format);
		}
	}

	public function multiline_date_bold_time(){
		$dashSlashedTime = $this->getDashSlashFormatedTime();
		$dashSlashedTime = str_replace("-*", "<br/><b>", $dashSlashedTime);
		$dashSlashedTime = str_replace("*-", "</b><br/>", $dashSlashedTime);
		return $dashSlashedTime;
	}

	public function getDashSlashFormatedTime(){
		return $this->getDateWithFormat("F -* j *- Y");
	}

	public function ConvertToJalali($time){
		$date = new \jDateTime(true, true, 'Asia/Tehran');
		return $date->gDate("Y-m-d", $time, 'Asia/Tehran');
	}

}

?>