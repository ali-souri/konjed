<?php

namespace Core\Object;

/**
 * Class Register
 * provides the files of each layer(package) for accessing.
 *
 * @package Core\Object
 */
class Register {

    function __construct() {
        
    }

    private $models = array("class.page.php",
        "class.template.php",
        "class.user.php",
        "class.Post.php",
        "class.Category.php",
        "class.Image.php",
        "class.Gallery.php",
        "class.ImageGallery.php",
        "class.firstpageslider.php",
        "class.Comment.php",
        "class.Contact.php",
        "class.SocialNetworksInfo.php",
        "class.SocialPostInfo.php",
    );
    private $services = array("class.db.php",
        "functions.php",
        "Error.php");
    private $cores = array("object/Crud.php",
        "object/Object.php",
        "object/AnnotationAPI.php",
        "Loader.php",
        "page/PageCore.php",
        "page/TemplateHandler.php",
        "command/Command.php",
        "command/CommandAPI.php",
        "command/CommandParser.php",
        "command/CommandRules.php",
        "command/CommandFactory.php",
        "response/ResponseAPI.php",
        "session/SessionAPI.php",
        "user/UserCore.php",
        "user/AuthenticationAPI.php",
        "time/TimeAPI.php",
        "tools/TwigHelper.php",
        "search/SearchAPI.php",
        "unit-test/TestAPI.php",
        "sculptor/SculptorAPI.php",
        "webservice/WebServiceAPI.php",
        "webservice/Instagram.php",
        "exception/KonjedException.php"
    );
    private $available_modules = array(
        "CRUD" => array("name" => "CRUD", "api_class_name" => "Core\\API", "address_from_root" => "/src/system/")
    );
    private $controllers = array(
        "IndexPageController.php",
    );
    private $write_method_allowed = array("Create", "Update", "Delete");

    public function get_modules() {
        return $this->available_modules;
    }

    public function get_models() {
        return $this->models;
    }

    public function get_services() {
        return $this->services;
    }

    public function get_cores() {
        return $this->cores;
    }

    public function get_controllers() {
        return $this->controllers;
    }

    public function check_write_permissions($write_action_key) {
        return in_array($write_action_key, $this->write_method_allowed);
    }

    public function model_object_name_is_valid($object_name) {
        foreach ($this->models as $value) {
            $class_name_array = explode(".", $value);
            $class_pure_name = $class_name_array[1];
            if ($class_pure_name == $object_name) {
                return TRUE;
            }
        }
        return FALSE;
    }

}

?>