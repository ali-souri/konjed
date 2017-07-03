<?php
/*
 * Created by PhpStorm.
 * User: pooria
 * Date: 11/2/15
 * Time: 5:13 PM
 */

namespace Core\User;

require_once __DIR__ . "/../Loader.php";
\Loader::load("all");

use Core\Service as Service;

/**
 * --- This class contains the core modules for authentication
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\User
 */
class Authentication
{

    function __construct()
    {
    }

    public function user_authentication($username, $password)
    {
        $user = new UserCore();
        $user = $user->get_user_by_username($username);
        if ($user)
            if ($this->check_password($user->get_password(), $password))
                return true;
            else {
                echo Service\Error::get_message("password_incorrect");
                return false;
            }
        else
            return false;
    }

    public static function check_password($user_password, $input_password)
    {
        if (md5($input_password) == md5($user_password)){
            return true;
        }else{
            return false;
        }
    }
}