<?php

namespace Core\Page;

require_once __DIR__."/../Loader.php";
\Loader::load("all");

use Core\Model\Page as Page;
use Core\Model\Template as ModelTemplate;
use Core\Service as Service;

/**
 * --- This class is dedicated to actions of making and handling pages
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Page
 */
class PageCore{

    /**
     * --- empty constructor of class
     */
    function __construct(){}

    /**
     * --- This function makes the TemplateHandler object for the considered page
     * @param string $page The name of the considered page
     * @param string $additional_info The additional information which is used if there is a special query in it and will be delivered to the template
     * @return TemplateHandler The TemplateHandler object for handling template of page
     */
    public function CreatePageFromModelObject($page,$additional_info){
        $template_handler = new Template\TemplateHandler();
        if (array_key_exists("template_data_query", $additional_info)) {
            if (is_string($additional_info['template_data_query'])) {
                $page->set_template_data_query($additional_info['template_data_query']);
            }else if (get_class($additional_info['template_data_query'])) {
                $page->set_template_data_query($additional_info['template_data_query']->serialize_page_querys());
            }
        }
        return $template_handler->getTemplateHtml(ModelTemplate::construct_from_db($page->get_template_id()),$this->PageQueryToData($page->get_template_data_query(),$additional_info),$additional_info);
    }

    /**
     * --- This function makes the TemplateHandler object for the considered page
     * @param string $id The id of the considered page
     * @param string $additional_info The additional information which is used if there is a special query in it and will be delivered to the template
     * @return TemplateHandler The TemplateHandler object for handling template of page
     */
    public function CreatePageWithId($id,$additional_info){
        $page = Page::construct_from_db($id);
        return $this->CreatePageFromModelObject($page);
    }

    /**
     * --- This function makes the TemplateHandler object for the considered page
     * @param string $url_name The unique name of the considered page
     * @param string $additional_info The additional information which is used if there is a special query in it and will be delivered to the template
     * @return TemplateHandler The TemplateHandler object for handling template of page
     */
    public function CreatePageWithUrlName($url_name,$additional_info){
        $db = new Service\DB();
        $page_db_data_array = $db->exec_select_sql("SELECT * FROM Page WHERE url_name='".$url_name."';");
        if (!$page_db_data_array) {
            throw new \Core\Service\KonjedException('Page Exception: '.Service\Error::get_message("page_record_not_found"));
        }
        $page = Page::construct_from_array($page_db_data_array[0]);
        return $this->CreatePageFromModelObject($page,$additional_info);
    }

    /**
     * @param $pageQueryArray 'in array with format : Array("query_name"=>"dataname","table_name"=>"t1","table_columns"=>Array(0=>"col1",1=>"col2"))'
     * @param $conditions_array
     * @return string
     */
    private function PageQueryArrayToSql($pageQueryArray,$special_conditions_string,$additional_info){
        $sql = "SELECT ";
        $sql.= getWhereValuesString($pageQueryArray['table_columns'])." FROM ".$pageQueryArray['table_name']." ";
        if (array_key_exists("where_clauses", $pageQueryArray)) {
            if ($pageQueryArray['where_clauses']!="none") {
                if (!array_key_exists("0",$pageQueryArray['where_clauses'])) {
                    $sql.="WHERE ". getWhereClauseArrayAsString($pageQueryArray['where_clauses'],$additional_info)." ";      
                }
            }
        }
        $sql.=$special_conditions_string.";";
        return $sql;
    }

    /**
     * @param $page_data_query in format: 'in json with style:{queries:{"0":{"query_name":"dataname1","table_name":"t1","table_columns":{"0":"col1","1":"col2"},"where_clauses":{"col1":":value_from_array"}},"1":{"query_name":"dataname2","table_name":"t2","table_columns":{"0":"col3","1":"col4"}}}}'
     * @return array of database page data
     */
    private function PageQueryToData($page_data_query,$additional_info){
        if (($page_data_query=="")||($page_data_query=="null")||($page_data_query==null)) {
            return array();
        }
        $output_data_array = array();
        $db = new Service\DB();
        $page_data_array = json_decode($page_data_query,TRUE);
        foreach($page_data_array['queries'] as $key=>$value){
           $output_data_array[$value['query_name']] = $db->exec_select_sql($this->PageQueryArrayToSql($value,$this->DetermineQuerySpecialConditions($value['query_name'],$additional_info),$additional_info));
        }
        return $output_data_array;
    }

    /**
     * --- Just some special queries
     */
    private function DetermineQuerySpecialConditions($query_name,$additional_info){//check if additional info has pagination and implement special conditions in db queies
        if (array_key_exists("special_conditions", $additional_info)) {
            if (array_key_exists($query_name, $additional_info['special_conditions'])) {
                return $additional_info['special_conditions'][$query_name];
            }
        }
        if ($query_name=='pageposts') {
            return "ORDER BY id DESC ";
        }else if ($query_name=='ninelastposts') {
            return "ORDER BY id DESC LIMIT 9 ";
        }else if( substr($query_name,-7) =="inverse"){
            return "ORDER BY id DESC ";
        }else if(($query_name=="slider")||($query_name=="posts")||($query_name=="allgalleries")||($query_name=="galleryimageitem")){
            return "ORDER BY id DESC ";
        }else if($query_name=="allimages"){
            return "ORDER BY id DESC ";
        }
        return "";
    }

    private function QueryConditionsToSql($queryconditions){
        return $queryconditions;
    }


}

?>