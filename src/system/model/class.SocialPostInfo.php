<?php

namespace Core\Model;

use Core\Service\DB as DB;

class SocialPostInfo {

    private $id = 0;

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    private $network_id = "";

    public function get_network_id() {
        return $this->network_id;
    }

    public function set_network_id($network_id) {
        $this->network_id = $network_id;
    }

    private $network_post_id = "";

    public function get_network_post_id() {
        return $this->network_post_id;
    }

    public function set_network_post_id($network_post_id) {
        $this->network_post_id = $network_post_id;
    }

    private $media_type = "";

    public function get_media_type() {
        return $this->media_type;
    }

    public function set_media_type($media_type) {
        $this->media_type = $media_type;
    }

    private $media_address = "";

    public function get_media_address() {
        return $this->media_address;
    }

    public function set_media_address($media_address) {
        $this->media_address = $media_address;
    }

    private $thumbnail_img_address = "";

    public function get_thumbnail_img_address() {
        return $this->thumbnail_img_address;
    }

    public function set_thumbnail_img_address($thumbnail_img_address) {
        $this->thumbnail_img_address = $thumbnail_img_address;
    }

    private $low_res_img_address = "";

    public function get_low_res_img_address() {
        return $this->low_res_img_address;
    }

    public function set_low_res_img_address($low_res_img_address) {
        $this->low_res_img_address = $low_res_img_address;
    }

    private $caption = "";

    public function get_caption() {
        return $this->caption;
    }

    public function set_caption($caption) {
        $this->caption = $caption;
    }

    private $comment_count = "";

    public function get_comment_count() {
        return $this->comment_count;
    }

    public function set_comment_count($comment_count) {
        $this->comment_count = $comment_count;
    }

    private $likes_count = "";

    public function get_likes_count() {
        return $this->likes_count;
    }

    public function set_likes_count($likes_count) {
        $this->likes_count = $likes_count;
    }

    private $created_time = "";

    public function get_created_time() {
        return $this->created_time;
    }

    public function set_created_time($created_time) {
        $this->created_time = $created_time;
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

    public function __constructAll($id, $network_id, $network_post_id, $media_type, $media_address, $thumbnail_img_address, $low_res_img_address, $caption, $comment_count, $likes_count, $created_time) {
        $this->id = $id;
        $this->network_id = $network_id;
        $this->network_post_id = $network_post_id;
        $this->media_type = $media_type;
        $this->media_address = $media_address;
        $this->thumbnail_img_address = $thumbnail_img_address;
        $this->low_res_img_address = $low_res_img_address;
        $this->caption = $caption;
        $this->comment_count = $comment_count;
        $this->likes_count = $likes_count;
        $this->created_time = $created_time;
    }

    public static function construct_from_array($fields_array) {
        return new SocialPostInfo($fields_array['id'], $fields_array['network_id'], $fields_array['network_post_id'], $fields_array['media_type'], $fields_array['media_address'], $fields_array['thumbnail_img_address'], $fields_array['low_res_img_address'], $fields_array['caption'], $fields_array['comment_count'], $fields_array['likes_count'], $fields_array['created_time']);
    }

    public static function construct_from_db($id) {
        $db = new DB();
        $object_array = $db->select_by_id("SocialPostInfo", $id);
        $db->close();
        $object = new SocialPostInfo($object_array['id'], $object_array['network_id'], $object_array['network_post_id'], $object_array['media_type'], $object_array['media_address'], $object_array['thumbnail_img_address'], $object_array['low_res_img_address'], $object_array['caption'], $object_array['comment_count'], $object_array['likes_count'], $object_array['created_time']);
        return $object;
    }

    public function save() {
        $db = new DB();
        if (($db->check_record_exists("SocialPostInfo", $this->id)) && ($this->id != "")) {
            $return_value = $db->update("SocialPostInfo", array('id' => $this->id, 'network_id' => $this->network_id, 'network_post_id' => $this->network_post_id, 'media_type' => $this->media_type, 'media_address' => $this->media_address, 'thumbnail_img_address' => $this->thumbnail_img_address, 'low_res_img_address' => $this->low_res_img_address, 'caption' => $this->caption, 'comment_count' => $this->comment_count, 'likes_count' => $this->likes_count, 'created_time' => $this->created_time));

            $db->close();
            return $return_value;
        } else {
            $return_value = $db->insert("SocialPostInfo", array('id' => $this->id, 'network_id' => $this->network_id, 'network_post_id' => $this->network_post_id, 'media_type' => $this->media_type, 'media_address' => $this->media_address, 'thumbnail_img_address' => $this->thumbnail_img_address, 'low_res_img_address' => $this->low_res_img_address, 'caption' => $this->caption, 'comment_count' => $this->comment_count, 'likes_count' => $this->likes_count, 'created_time' => $this->created_time));

            $db->close();
            return $return_value;
        }
    }

    public function delete() {
        $db = new DB();
        if (($db->check_record_exists("SocialPostInfo", $this->id)) && ($this->id != "")) {
            $return_value = $db->delete("SocialPostInfo", $this->id);

            $db->close();
            return $return_value;
        } else {
            return FALSE;
        }
    }

}
