<?php
/**
 * Created by PhpStorm.
 * User: pooria
 * Date: 10/21/15
 * Time: 6:43 PM
 */

error_reporting(-1);
ini_set('display_errors', 'On');


require_once __DIR__."/../core/Loader.php";
\Loader::load("all");

use Core\Model\Page as Page ;
use Core\Model\Template as Template ;
//$page = Core\Model\Page::construct_from_db(1);
//
//var_dump($page->getName());
// $page = new page();
// $page::palaghche("ali");


$page = new Page(2,"pooria",60,'',"twigTwig");

var_dump($page->getName());




echo "<br/>-----------------------------------------------------------------<br/>";

$template = new Template();

$template->custom_construct(array('id'=>3,'name'=>"saghi",'twig_name'=>"mahmood"));

var_dump($template->tojson());

