<?php

require_once __DIR__.'/Register.php';

use Core\Object\Register as Register;

/**
 * Class Loader
 *
 * load all the files of each layer(package) you want, every where you use it.
 */
class Loader{

	private static $models = array();

	private static $services = array();

	private static $cores = array();

    private static $controllers = array();

    function __construct(){
		$this->fill_fields();
	}

	private static function fill_fields(){
		$register = new Register();
		self::$models = $register->get_models();
		self::$services = $register->get_services();
        self::$cores = $register->get_cores();
        self::$controllers = $register->get_controllers();
    }

    /**
     * make model files, ready to access.
     */
    public static function loadModels(){
		self::fill_fields();
		foreach (self::$models as $classname) {
			require_once __DIR__."/../model/".$classname;
		}
	}

    /**
     * make core files, ready to access.
     */
    public static function loadCores(){
		self::fill_fields();
		foreach (self::$cores as $classname) {
			require_once __DIR__."/".$classname;
		}
	}

    /**
     * make service files, ready to access.
     */
    public static function loadServices(){
		self::fill_fields();
		foreach (self::$services as $classname) {
			require_once __DIR__."/../service/".$classname;
		}
	}

    /**
     * make controller files, ready to access.
     */
    public static function loadControllers(){
        self::fill_fields();
        foreach (self::$controllers as $classname) {
            require_once __DIR__."/../../controller/".$classname;
        }
    }

    /**
     * custom access.with this method you can access to each package you want.
     *  'models', 'cores' , 'services' or 'all' of your packages.
     *
     * @param $package_name
     */
    public static function load(){
		self::fill_fields();
        $input_variables = func_get_args();
        foreach ($input_variables as $variable) {
            if ($variable=="all") {
                self::loadCores();
                self::loadModels();
                self::loadServices();
                self::loadControllers();
            }elseif ($variable=="models") {
                self::loadModels();
            }elseif ($variable=="cores") {
                self::loadCores();
            }elseif ($variable=="services") {
                self::loadServices();
            }elseif ($variable=="controllers") {
                self::loadControllers();
            }
        }
	}	

}

?>