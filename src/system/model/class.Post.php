<?php

namespace Core\Model;

use Core\Service\DB as DB;

class Post {

    private $id = 0;

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    private $page_id = 0;

    public function get_page_id() {
        return $this->page_id;
    }

    public function set_page_id($page_id) {
        $this->page_id = $page_id;
    }

    private $headline = "";

    public function get_headline() {
        return $this->headline;
    }

    public function set_headline($headline) {
        $this->headline = $headline;
    }

    private $title = "";

    public function get_title() {
        return $this->title;
    }

    public function set_title($title) {
        $this->title = $title;
    }

    private $time = "";

    public function get_time() {
        return $this->time;
    }

    public function set_time($time) {
        $this->time = $time;
    }

    private $views_number = 0;

    public function get_views_number() {
        return $this->views_number;
    }

    public function set_views_number($views_number) {
        $this->views_number = $views_number;
    }

    private $content = "";

    public function get_content() {
        return $this->content;
    }

    public function set_content($content) {
        $this->content = $content;
    }

    private $image_address = "";

    public function get_image_address() {
        return $this->image_address;
    }

    public function set_image_address($image_address) {
        $this->image_address = $image_address;
    }

    private $showinfirstpage = "";

    public function get_showinfirstpage() {
        return $this->showinfirstpage;
    }

    public function set_showinfirstpage($showinfirstpage) {
        $this->showinfirstpage = $showinfirstpage;
    }

    private $category_id = 0;

    public function get_category_id() {
        return $this->category_id;
    }

    public function set_category_id($category_id) {
        $this->category_id = $category_id;
    }

/**
     * constructor for Page, that can have any number of input arguments,
     *  and handle the Page object construction by calling the other constructors.
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

    public function __constructAll($id, $page_id, $headline, $title, $time, $views_number, $content  , $image_address , $showinfirstpage , $category_id) {
        $this->id = $id;
        $this->page_id = $page_id;
        $this->headline = $headline;
        $this->title = $title;
        $this->time = $time;
        $this->views_number = $views_number;
        $this->content = $content;
        $this->showinfirstpage = $showinfirstpage;
        $this->image_address = $image_address;
        $this->category_id = $category_id;
    }

    public static function construct_from_array($fields_array) {
        return new Post($fields_array['id'], $fields_array['page_id'], $fields_array['headline'], $fields_array['title'], $fields_array['time'], $fields_array['views_number'], $fields_array['content'], $fields_array['image_address'],$fields_array['showinfirstpage'],$fields_array['category_id']);
    }

    public static function construct_from_db($id) {
        $db = new DB();
        $object_array = $db->select_by_id("Post", $id);
        $db->close();
        $object = new Post($object_array['id'], $object_array['page_id'], $object_array['headline'], $object_array['title'], $object_array['time'], $object_array['views_number'], $object_array['content'], $object_array['image_address'] ,$object_array['showinfirstpage'],$object_array['category_id']);
        return $object;
    }

    public function save() {
        $db = new DB();
        if (($db->check_record_exists("Post", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
            $return_value = $db->update("Post", array('id' => $this->id, 'page_id' => $this->page_id, 'headline' => $this->headline, 'title' => $this->title, 'time' => $this->time, 'views_number' => $this->views_number, 'content' => $this->content , 'image_address' => $this->image_address , 'showinfirstpage' => $this->showinfirstpage , 'category_id' => $this->category_id));

            $db->close();
            return $return_value;
        } else {
            $return_value = $db->insert("Post", array('id' => $this->id, 'page_id' => $this->page_id, 'headline' => $this->headline, 'title' => $this->title, 'time' => $this->time, 'views_number' => $this->views_number, 'content' => $this->content , 'image_address' => $this->image_address , 'showinfirstpage' => $this->showinfirstpage , 'category_id' => $this->category_id));

            $db->close();
            return $return_value;
        }
    }

    public function delete() {
        $db = new DB();
        if (($db->check_record_exists("Post", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
            $return_value = $db->delete("Post", $this->id);

            $db->close();
            return $return_value;
        } else {
            return FALSE;
        }
    }

}