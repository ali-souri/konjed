<?php

namespace Core\Model;

use Core\Service\DB as DB;


class User
{
    private $id = 0;

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    private $name = "";

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($name)
    {
        $this->name = $name;
    }

    private $user_name = "";

    public function get_user_name()
    {
        return $this->user_name;
    }

    public function set_user_name($user_name)
    {
        $this->user_name = $user_name;
    }

    private $email = "";

    public function get_email()
    {
        return $this->email;
    }

    public function set_email($email)
    {
        $this->email = $email;
    }

    private $password = "";

    public function get_password()
    {
        return $this->password;
    }

    public function set_password($password)
    {
        $this->password = $password;
    }

    private $age = "";

    public function get_age()
    {
        return $this->age;
    }

    public function set_age($age)
    {
        $this->age = $age;
    }

    private $phone = "";

    public function get_phone()
    {
        return $this->phone;
    }

    public function set_phone($phone)
    {
        $this->phone = $phone;
    }

    private $access_level = "";

    public function get_access_level()
    {
        return $this->access_level;
    }

    public function set_access_level($access_level)
    {
        $this->access_level = $access_level;
    }

    /**
     * constructor for Page, that can have any number of input arguments,
     *  and handle the Page object construction by calling the other constructors.
     */
    function __construct()
    {
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
    public function custom_construct($custom_fields_array)
    {
        foreach ($custom_fields_array as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * make a json from Page fields.
     *
     * @return string in json format.
     */
    public function tojson()
    {
        return json_encode(get_object_vars($this), true);
    }

    public function __constructAll($id, $name, $user_name, $email, $password, $age, $phone, $access_level)
    {
        $this->id = $id;
        $this->name = $name;
        $this->user_name = $user_name;
        $this->email = $email;
        $this->password = $password;
        $this->age = $age;
        $this->phone = $phone;
        $this->access_level = $access_level;
    }

    public static function construct_from_array($fields_array)
    {
        return new user($fields_array['id'], $fields_array['name'], $fields_array['user_name'], $fields_array['email'], $fields_array['password'], $fields_array['age'], $fields_array['phone'], $fields_array['access_level']);
    }

    public static function construct_from_db($id)
    {
        $db = new DB();
        $object_array = $db->select_by_id("user", $id);
        $db->close();
        $object = new user($object_array['id'], $object_array['name'], $object_array['user_name'], $object_array['email'], $object_array['password'], $object_array['age'], $object_array['phone'], $object_array['access_level']);
        return $object;
    }

    public function save()
    {
        $db = new DB();
        if (($db->check_record_exists("user", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
            $return_value = $db->update("user", array('id' => $this->id, 'name' => $this->name, 'user_name' => $this->user_name, 'email' => $this->email, 'password' => $this->password, 'age' => $this->age, 'phone' => $this->phone, 'access_level' => $this->access_level));

            $db->close();
            return $return_value;

        } else {
            $return_value = $db->insert("user", array('id' => $this->id, 'name' => $this->name, 'user_name' => $this->user_name, 'email' => $this->email, 'password' => $this->password, 'age' => $this->age, 'phone' => $this->phone, 'access_level' => $this->access_level));

            $db->close();
            return $return_value;
        }
    }

    public function delete()
    {
        $db = new DB();
        if (($db->check_record_exists("user", $this->id)) && (($this->id != 0)&&($this->id != ""))) {
            $return_value = $db->delete("user", $this->id);

            $db->close();
            return $return_value;
        } else {
            return FALSE;
        }
    }
}