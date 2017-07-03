<?php
/**
 * Created by PhpStorm.
 * User: pooria
 * Date: 11/2/15
 * Time: 3:49 PM
 */

namespace Core\User;

require_once __DIR__ . "/../Loader.php";
\Loader::load("all");

use Core\Model\User as User;
use Core\Service as Service;

/**
 * --- Just some typical tools for user
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\User
 */
class UserCore
{
    function __construct()
    {
    }

    public function get_user_by_username($username)
    {
        $db = new Service\DB();

        $user = $db->exec_select_sql("SELECT * FROM User WHERE user_name='".$username."';");

        if(!$user){

            echo Service\Error::get_message("user_not_exist");
            return false;
        }
        else
            return User::construct_from_array($user[0]);
    }


}