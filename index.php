<?php
/**
 * --- this is the first component for executing on every request
 * @author Ali Souri ap.alisouri@gmail.com
 * @author Pooria Pahlevani
 * @copyright 2015-2020 Negaran Computer Group
 * @license None
 * @license None
 * @version 1.0.0
 */
error_reporting(-1);
ini_set('display_errors', 'On');
require_once __DIR__."/vendor/autoload.php";
require_once "src/front_controller.php";
date_default_timezone_set("Asia/Tehran");
header("X-Developer-Team-Info: This Project Is Developed By Negaran Team Under The Konjed Source And Desiged By Ali Souri  , Pooria Pahlevani");

\Controller\FrontController::handle_request();



?>