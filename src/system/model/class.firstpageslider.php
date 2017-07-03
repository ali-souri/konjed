<?php

namespace Core\Model;

use Core\Service\DB as DB;

class firstpageslider {

    private $id = 0;

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    private $slidertexttitle = "";

    public function get_slidertexttitle() {
        return $this->slidertexttitle;
    }

    public function set_slidertexttitle($slidertexttitle) {
        $this->slidertexttitle = $slidertexttitle;
    }

    private $slidertextcaption = "";

    public function get_slidertextcaption() {
        return $this->slidertextcaption;
    }

    public function set_slidertextcaption($slidertextcaption) {
        $this->slidertextcaption = $slidertextcaption;
    }

    private $slidertextdetail = "";

    public function get_slidertextdetail() {
        return $this->slidertextdetail;
    }

    public function set_slidertextdetail($slidertextdetail) {
        $this->slidertextdetail = $slidertextdetail;
    }

    private $slidertextdate = "";

    public function get_slidertextdate() {
        return $this->slidertextdate;
    }

    public function set_slidertextdate($slidertextdate) {
        $this->slidertextdate = $slidertextdate;
    }

    private $image_address = "";

    public function get_image_address() {
        return $this->image_address;
    }

    public function set_image_address($image_address) {
        $this->image_address = $image_address;
    }

    private $link_address = "";

    public function get_link_address() {
        return $this->link_address;
    }

    public function set_link_address($link_address) {
        $this->link_address = $link_address;
    }

   
/**
     * constructor for Page, that can have any number of input arguments,
     *  and handle the Page object construction by calling the other constructors. link_address
     */

    function __construct() {
        $a = func_get_args();
        $i = func_num_args();
        if ($i != 0) {
            call_user_func_array(array($this, "__constructAll"), $a);
        }
    }

    /**
     * Construct the Page object by initialize 'Some' of the class field with input array argument.
     *
     * @param $custom_fields_array in Array format like: Array('name'=>'MyName' , 'template_id'=>'2', 'twig_name'=>'MyTwig') and etc.
     */
    public function custom_construct($custom_fields_array) {
        foreach ($custom_fields_array as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * make a json from Page fields.
     *
     * @return string in json format.
     */
    public function tojson() {
        return json_encode(get_object_vars($this), true);
    }

    public function __constructAll($id, $slidertexttitle , $slidertextcaption , $slidertextdetail , $slidertextdate , $image_address ,$link_address) {
        $this->slidertexttitle = $slidertexttitle;
        $this->slidertextcaption = $slidertextcaption;
        $this->slidertextdetail = $slidertextdetail;
        $this->slidertextdate = $slidertextdate;
        $this->image_address = $image_address;
        $this->id = $id;
        $this->link_address = $link_address;
    }

    public static function construct_from_array($fields_array) {
        return new firstpageslider($fields_array['id'], $fields_array['slidertexttitle'], $fields_array['slidertextcaption'], $fields_array['slidertextdetail'], $fields_array['slidertextdate'], $fields_array['image_address'],$fields_array['link_address']);
    }

    public static function construct_from_db($id) {
        $db = new DB();
        $object_array = $db->select_by_id("firstpageslider", $id);
        $db->close();
        $object = new firstpageslider($object_array['id'], $object_array['slidertexttitle'], $object_array['slidertextcaption'], $object_array['slidertextdetail'], $object_array['slidertextdate'], $object_array['image_address'],$object_array['link_address']);
        return $object;
    }

    public function save() {
        $db = new DB();
        if (($db->check_record_exists("firstpageslider", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
            $return_value = $db->update("firstpageslider", array('id' => $this->id, 'slidertexttitle' => $this->slidertexttitle, 'slidertextcaption' => $this->slidertextcaption, 'slidertextdetail' => $this->slidertextdetail, 'slidertextdate' => $this->slidertextdate, 'image_address' => $this->image_address,'link_address' => $this->link_address));

            $db->close();
            return $return_value;
        } else {
            $return_value = $db->insert("firstpageslider", array('id' => $this->id, 'slidertexttitle' => $this->slidertexttitle, 'slidertextcaption' => $this->slidertextcaption, 'slidertextdetail' => $this->slidertextdetail, 'slidertextdate' => $this->slidertextdate, 'image_address' => $this->image_address,'link_address' => $this->link_address));

            $db->close();
            return $return_value;
        }
    }

    public function delete() {
        $db = new DB();
        if (($db->check_record_exists("firstpageslider", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
            $return_value = $db->delete("firstpageslider", $this->id);

            $db->close();
            return $return_value;
        } else {
            return FALSE;
        }
    }

}