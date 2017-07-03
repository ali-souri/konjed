<?php

namespace Controller;
require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/system/Core.php";
require_once __DIR__."/system/service/config.php";
use Respect\Rest\Router as RespectRestRouter;

/**
 * --- frontcontroller class which is designed for using slim for all rest request handling
 * --- this class gets all requests and convert it to the usable array and pass it to core
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Controller
 */
class FrontController{
    /**
     * --- handle_request method supports every rest method
     */
    public static function handle_request(){

        $r3 = new RespectRestRouter;

        $r3->any('/**', function ($url) {

            if (sizeof($url)==1) {
                header('Location: '. system_config['index_url']);
            }

            $all_requests = $_REQUEST;

            if ($_FILES) {
                $all_requests["konjed_system_uploades"] = $_FILES;
            }

           $core = new \Core\Core();

           try{
                $core->core_controller_logic_init(self::correct_rest_data($url),$all_requests);
           }catch(\Core\Service\KonjedException $e){
            echo $e->makePage();
           }

        });

        $r3->run();

    }

    private static function correct_rest_data($rest_input_array){

        $rest_output_array = [];

        foreach ($rest_input_array as $key => $rest_value) {

            if (($key!=0)&&($rest_value!="index.php")) {
                $rest_output_array[] = $rest_value;
            }
            
        }

        return [$rest_output_array];

    }

}

?>