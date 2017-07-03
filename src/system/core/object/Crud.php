<?php

namespace Core\Object;

require_once __DIR__ . "/../Loader.php";
\Loader::load("all");

/**
 * class CRUD do 'Create', 'Read' , 'Update', and 'Delete' method for any Object.
 *
 * Class CRUD
 * @package Core\Object
 */
class CRUD
{

    private $object = "";

    function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * @return Boolean result of save model object.
     */
    public function Create()
    {
        $arguments = func_get_args();
        // $arguments[0]['id'] = "";
        $this_object = $this->object;
        $this->object = $this_object::construct_from_array($arguments[0]);
        return $this->object->save();
    }

    /**
     * @param $read_array
     * @return set of objects like set of Pages or set of Templates,... in array format.
     */
    public function Read($read_array)
    {
        if (array_key_exists('id', $read_array)) {
            if ($read_array['id'] != "") {
                $this_object = $this->object;
                return $this_object::construct_from_db($read_array['id']);
            }
        }
        $db = new \Core\Service\DB();
        $db_array = $db->exec_select_sql(create_read_query(get_class($this->object), "*", $read_array));
        if ($db_array == FALSE) {
            return null;
        }
        $return_objects_array = array();
        foreach ($db_array as $key => $value) {
            $this_object = $this->object;
            $return_objects_array[$key] = $this_object::construct_from_array($value);
        }
        return $return_objects_array;
    }

    /**
     * Update an object attributes,that come in input argument.
     *
     * @return bool
     */
    public function Update()
    {
        $arguments = func_get_args();
        $this->object->custom_construct($arguments[0]);
        $result = $this->object->save();
        return $result;
    }

    /**
     * Delete an object record from database (or some), by calling its delete method,
     * if we have any of its attribute that comes in input argument.
     *
     * @return bool
     */
    public function Delete()
    {
        $arguments = func_get_args();
        if (array_key_exists("id", $arguments[0])) {
            $this_object = $this->object;
            $this->object = $this_object::construct_from_db($arguments[0]['id']);
            return $this->object->delete();
        }
        $db = new \Core\Service\DB();
        $sql = "SELECT * FROM " . get_class($this->object) . " WHERE " . where_clause_array_to_string($arguments[0]);
        $db_array = $db->exec_select_sql($sql);
        if ($db_array == FALSE) {
            return FALSE;
        }
        $this_object = $this->object;
        $this->object = $this_object::construct_from_array($db_array[0]);
        return $this->object->delete();
    }

}

?>