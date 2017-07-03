<?php
/**
 * Created by PhpStorm.
 * User: pooria
 * Date: 10/21/15
 * Time: 2:59 PM
 */

namespace Core\Service;

require 'config.php';

use \mysqli as mysqli;

class DB
{

    private $connection = null;

    function __construct()
    {

        // $config = $this->get_db_config();
        $conn = new mysqli(DB_config['host'], DB_config['username'], DB_config['password'], DB_config['database']);
        if ($conn->connect_error) {
            throw new \Core\Service\KonjedException('DB Exception - Connection failed:  '.$conn->connect_error);
        }
        $conn->set_charset("utf8");
        $this->connection = $conn;
    }

    public function close()
    {
        $this->connection->close();
    }

    public function get_db_config()
    {

        $configs_json = get_system_config();

        $json = json_decode($configs_json, true);

        return $json['DB-config'];
    }

    public function exec_select_sql($sql)
    {
        $result = $this->connection->query($sql);
        if ($result->num_rows > 0) {
            $result_select = array();
            $index=0;
            while($row = $result->fetch_assoc()){
                $result_select[$index]=$row;
                $index++;
            }
            return $result_select;
        } else {
            return FALSE;
        }
    }

    

    public function exec_write_sql($sql)
    {
        if ($this->connection->query($sql) === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getAllTableRecords($table_name)
    {
        $sql = "SELECT * FROM $table_name";
        return $this->exec_select_sql($sql);
    }

    public function getAllTableRecordsId($table_name)
    {
        $sql = "SELECT id FROM $table_name";
        return $this->exec_select_sql($sql);
    }

    public function select_by_id($table_name, $id)
    {
        $sql = "SELECT * FROM " . $table_name . " WHERE id='" . $id . "';";
        $all_selected_data = $this->exec_select_sql($sql);
        return $all_selected_data[0];
    }

    public function check_record_exists($table_name, $id)
    {
        $result = self::select_by_id($table_name, $id);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function insert($table_name, $values)
    {
        $sql = "INSERT INTO " . $table_name . " ";
        $columns = "(";
        $data_values = "(";
        $j = count($values);
        foreach ($values as $key => $value) {
            if (($key != "id") || (!is_null($value))) {
                $columns .= $key;
                $data_values .= "'" . $value . "'";
            }
            if ($j > 1) {//maybe error
                if (($key != "id") || (!is_null($value))) {
                    $columns .= ",";
                    $data_values .= ",";
                }
                $j--;
            }
        }
        $columns .= ")";
        $data_values .= ")";
        $sql .= $columns . " VALUES " . $data_values . ";";

        var_dump($sql);

        if ($this->connection->query($sql) === TRUE) {
            return TRUE;
        } else {
            printf("Errormessage: %s\n", $this->connection->error);
            return FALSE;
        }
    }

    public function delete($table_name, $id)
    {
        $sql = "DELETE FROM " . $table_name . " WHERE id='" . $id . "';";
        return $this->exec_write_sql($sql);
    }

    public function update($table_name, $values)
    {

        $sql = "UPDATE " . $table_name . " SET ";
        $data_update = "";
        $data_id = $values['id'];
        $values_length = sizeof($values);
        foreach ($values as $key => $value) {
            if ($key != "id") {
                if ($value != "") {
                    $data_update .= $key . "='" . $value . "'";
                    if ($values_length > 2) {
                        $data_update .= ",";
                    }
                }
                $values_length--;
            }
        }
        if (substr($data_update, -1)==",") {
            $data_update = substr($data_update,0,-1);
        }
        $sql .= $data_update . " where id=$data_id";
        return $this->exec_write_sql($sql);

    }
    public function security_data_check($str){
        
    }
}