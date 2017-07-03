<?php
/**
 * Created by PhpStorm.
 * User: pooria
 * Date: 10/21/15
 * Time: 3:30 PM
 */

error_reporting(-1);
ini_set('display_errors', 'On');

//use Core\Service\DB as db;

require_once "class.db.php";

$db = new Core\Service\DB();
var_dump($db->get_db_config());