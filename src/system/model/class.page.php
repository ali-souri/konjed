<?php

// require_once __DIR__;

/**
 * Created by PhpStorm.
 * User: pooria
 * Date: 10/21/15
 * Time: 3:58 PM
 */

namespace Core\Model;

use Core\Service\DB as DB;


class Page
{

    private $id = "";

    private $name = "";

    private $url_name = "";

    private $template_id = "";

    private $template_data_query = "";

    private $twig_name = "";


    /**
     * constructor for Page, that can have any number of input arguments,
     *  and handle the Page object construction by calling the other constructors.
     */
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if ($i!=0) {
            call_user_func_array(array($this,"__constructAll"),$a); 
        }
    }

    /**
     * Construct the Template object by initialize 'All' the class field with input arguments.
     *
     * @param $id
     * @param $name
     * @param $template_id
     * @param $template_data_query
     * @param $twig_name
     */
    public function __constructAll($id, $name, $url_name, $template_id, $template_data_query, $twig_name)
    {
        
        $this->id = $id;
        $this->name = $name;
        $this->url_name = $url_name;
        $this->template_id = $template_id;
        $this->template_data_query = $template_data_query;
        $this->twig_name = $twig_name;

    }

    /**
     * create a new Page object from input array argument.
     *
     * @param $fields_array in Array format like: Array('id'=>'8' , 'template_id'=>'3' , 'template_data_query'=>'some_query' , 'twig_name'=>'MyTwig')
     * @return Page
     */
    public static function construct_from_array($fields_array){
        return new Page($fields_array['id'], $fields_array['name'], $fields_array['url_name'], $fields_array['template_id'],$fields_array['template_data_query'], $fields_array['twig_name']);
    }

    /**
     * Construct the Page object by initialize 'Some' of the class field with input array argument.
     *
     * @param $custom_fields_array in Array format like: Array('name'=>'MyName' , 'template_id'=>'2', 'twig_name'=>'MyTwig') and etc.
     */
    public function custom_construct($custom_fields_array){
        foreach ($custom_fields_array as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * construct a new Page from it's database record.
     *
     * @param $id
     * @return Page
     */
    public static function construct_from_db($id)
    {
        $db = new DB();
        $object_array = $db->select_by_id("Page", $id);
        $db->close();
        $page = new Page($object_array['id'], $object_array['name'] , $object_array['url_name'], $object_array['template_id'],$object_array['template_data_query'], $object_array['twig_name']);
        return $page;
    }

    /**
     * make a json from Page fields.
     *
     * @return string in json format.
     */
    public function tojson(){
        return json_encode(get_object_vars($this),true);
    }

    /**
     * @return string
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function set_id($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function get_name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function get_url_name()
    {
        return $this->url_name;
    }

    /**
     * @param string $name
     */
    public function set_name($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function set_url_name()
    {
        return $this->url_name;
    }

    /**
     * @return string
     */
    public function get_template_id()
    {
        return $this->template_id;
    }

    /**
     * @param string $template_id
     */
    public function set_template_id($template_id)
    {
        $this->template_id = $template_id;
    }

    /**
     * @return string
     */
    public function get_twig_name()
    {
        return $this->twig_name;
    }

    /**
     * @param string $twig_name
     */
    public function set_twig_name($twig_name)
    {
        $this->twig_name = $twig_name;
    }

    /**
     * @return string
     */
    public function get_template_data_query()
    {
        return $this->template_data_query;
    }

    /**
     * @param string $template_data_query
     */
    public function set_template_data_query($template_data_query)
    {
        $this->template_data_query = $template_data_query;
    }


    /**
     * Save a new page record in database or update it, if exist.
     *  then return true for success or false if failed.
     *
     * @return bool
     */
    public function save()
    {
        $db = new DB();
        if (($db->check_record_exists("Page", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
            $return_value = $db->update("Page", array('id' => $this->id, 'name' => $this->name,'url_name'=>$this->url_name,
                'template_id' => $this->template_id,'template_data_query' => $this->template_data_query, 'twig_name' => $this->twig_name));

            $db->close();
            return $return_value;

        } else {
            $return_value = $db->insert("Page", array('id' => $this->id, 'name' => $this->name,'url_name'=>$this->url_name,
                'template_id' => $this->template_id,'template_data_query' => $this->template_data_query, 'twig_name' => $this->twig_name));

            $db->close();
            return $return_value;
        }
    }

    /**
     * Delete a page record from database if exist.
     *  then return true for success or false if failed.
     *
     * @return bool
     */
    public function delete()
    {
        $db = new DB();
        if (($db->check_record_exists("Page", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
            $return_value = $db->delete("Page", $this->id);

            $db->close();
            return $return_value;
        } else {
            return FALSE;
        }
    }

}