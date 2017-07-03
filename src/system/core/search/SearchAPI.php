<?php

namespace Core\Search;

require_once __DIR__ . "/../Loader.php";
\Loader::load("all");

/**
 * --- Some tools for search
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Search
 */
class SearchAPI{

	function __construct(){

	}

	public function get_searched_posts_data($query_string){
		$posts_array = $this->get_searched_posts($query_string);
		if ((is_null($posts_array))||($posts_array==FALSE)||(sizeof($posts_array)==0)) {
			return FALSE;
		}
		$return_data_array = array();
		foreach ($posts_array as $number => $post) {
			$return_data_array[$number]=array("id"=>$post['id'],"headline"=>$post['headline'],"title"=>$post['title'],"image_address"=>$post['image_address']);
		}
		return $return_data_array;
	}

	public function get_searched_posts($query_string){
		$db = new \Core\Service\DB();
		$post_objs = $db->exec_select_sql("SELECT * FROM Post Where headline LIKE '%".$query_string."%' OR title LIKE '%".$query_string."%' OR content LIKE '%".$query_string."%' ;");
		return $post_objs;
	}

}

//'%".$query_string."%'

?>