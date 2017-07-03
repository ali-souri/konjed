<?php
/**
 * Created by PhpStorm.
 * User: pooria
 * Date: 10/22/15
 * Time: 2:02 PM
 */

namespace Core\Model;

use Core\Service\DB as DB;

class Template {

    private $id = "";

    private $name = "";

    private $twig_name = "";


    /**
     * constructor for Template, that can have any number of input arguments,
     *  and handle the Template object construction by calling the other constructors.
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
     * @param $twig_name
     */
    public function __constructAll($id, $name, $twig_name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->twig_name = $twig_name;

    }

    /**
     * create a new Template object from input array argument.
     *
     * @param $fields_array in Array format like: Array('id'=>4 , 'name'=>'MyName' , 'twig_name'=>'MyTwig')
     * @return Template
     */
    public static function construct_from_array($fields_array){
        return new Template($fields_array['id'], $fields_array['name'], $fields_array['twig_name']);
    }

    /**
     * construct a new Template from it's database record.
     *
     * @param $id
     * @return Template
     */
    public static function construct_from_db($id)
    {
        $db = new DB();
        $object_array = $db->select_by_id("Template", $id);
        $db->close();
        $template = new Template($object_array['id'], $object_array['name'], $object_array['twig_name']);
        return $template;
    }

    /**
     * Construct the Template object by initialize 'Some' of the class field with input array argument.
     *
     * @param $custom_fields_array in Array format like: Array('name'=>'MyName' , 'twig_name'=>'MyTwig') and etc.
     */
    public function custom_construct($custom_fields_array){
        foreach ($custom_fields_array as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * make a json from Template fields.
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
    public function ge_name()
    {
        return $this->name;
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
     * Save a new template record in database or update it, if exist.
     *  then return true for success or false if failed.
     *
     * @return bool
     */
    public function save()
    {
        $db = new DB();
        if (($db->check_record_exists("template", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
            $return_value = $db->update("template", array('id' => $this->id, 'name' => $this->name,
                'twig_name' => $this->twig_name));

            $db->close();
            return $return_value;

        } else {
            $return_value = $db->insert("template", array('id' => $this->id, 'name' => $this->name,
                'twig_name' => $this->twig_name));

            $db->close();
            return $return_value;
        }
    }

    /**
     * Delete a template record from database if exist.
     *  then return true for success or false if failed.
     *
     * @return bool
     */
    public function delete()
    {
        $db = new DB();
        if (($db->check_record_exists("template", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
            $return_value = $db->delete("template", $this->id);

            $db->close();
            return $return_value;
        } else {
            return FALSE;
        }
    }

}