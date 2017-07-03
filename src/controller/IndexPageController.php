<?php

namespace Controller;

require_once __DIR__ . '/../system/core/controller/Controller.php';

/**
 * @ControllerType page
 * @BaseRoute /page
 * @MultiViews True
 * @Description this is a controller file for index page.
 */
class IndexPageController extends \Core\Controller\Controller {

    function __construct() {
        parent::__construct();
    }
   
    /**
     * @Route None
     * @OptionalRoute /index
     * @Usage web
     * @Description this is the main function of index page controller.
     */
    public function Index($extra_data = NULL) {
        $sys = $this->action;
        $sys->clear();
        $sys->set_status(TRUE);

        $sys->AddCommand_Response_EchoString(array("input_string"=>"<h3>Hi!</h3>"));
        // throw new \Core\Service\KonjedException('Controller Exception!');

        $app = $this->sculptor;

        $app->setCssSource("bootstrap");
        $app->setJsSource("jquery");
        $app->setCssTheme("cosmo");

        $btn1 = $app->makeElement("b","This is the Konjed!",["class"=>"btn1",
                                        "id"=>"btn_1"]);

        $sys->AddCommand_Response_EchoString(array("input_string"=>$btn1->render()));   

        return $sys;
    }


    /**
     * @Route /testindex
     * @Usage test
     * @Description this is the main function of index page controller.
     */
    public function testIndex($extra_data = NULL) {
        $sys = $this->action;
        $sys->clear();
        $sys->set_status(TRUE);
        $sys->AddCommand_Response_EchoString(array("input_string"=>"Hi!"));

        $this->assertEquals($sys,$this->Index());

        // return $sys;
    }
   
}
