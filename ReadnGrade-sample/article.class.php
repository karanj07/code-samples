<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once('database.class.php');

class Article extends DatabaseObject {
	
	protected static $table_name="articles";
	protected static $db_fields = array('id', 'user_id', 'title', 'html', 'simple_text', 'creation_date','comment_system','rating_system', 'params', 'unique_url');

	public $id;
	public $user_id;
	public $title;
	public $html;
	public $simple_text;
	public $creation_date;
	public $comment_system;
	public $rating_system;	
	public $params;		
	public $unique_url;
	
	
    public static function find_all_by_user($id = ""){
		global $user, $security;
		empty($id)? $id = $user->id:false;
		$id = $security->escape_value($id);
		//echo $id; die;
		$sql  = "SELECT * FROM ".self::$table_name." WHERE  `user_id` = '{$id}' ORDER BY `id` DESC";
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? $result_array  : false;
	}
	
	public function fetch_article_by_id($id="", $user_id=""){
		global $user, $session, $security;
        empty($user_id)?$user_id = $session->user_id:false;
		$id = $security->escape_value($id);
		$sql  = "SELECT * FROM ".self::$table_name." WHERE `user_id` = '{$user_id}' AND id = '{$id}' LIMIT 1";
		$result_array = self::find_by_sql($sql);
		$result_array=array_shift($result_array);
		return !empty($result_array) ? $result_array  : false;
	}
	
	public function verify_rating_submission( $article_id, $author_id){
	global $security;
	$author_id = $security->escape_value($author_id);
	$article_id = $security->escape_value($article_id);
	$sql  = "SELECT * FROM ".self::$table_name." WHERE `user_id` = '{$author_id}' AND id = '{$article_id}' LIMIT 1";
	$result_array = self::find_by_sql($sql);
	$result_array=array_shift($result_array);
	return !empty($result_array) ? true  : false;
	}
	
	public function fetch_article_by_url($url=""){
		global $user, $security;
		$url = $security->escape_value($url);
		$sql  = "SELECT * FROM ".self::$table_name." WHERE  `unique_url` = '{$url}' LIMIT 1";
		$result_array = self::find_by_sql($sql);
		$result_array=array_shift($result_array);
		return !empty($result_array) ? $result_array  : false;
	}
	
	public function fetch_pagination_data($pno=""){
		global $pagination, $session, $security;
		$id = $session->user_id;
		$id = $security->escape_value($id);
		$offset = ($pno-1)*$pagination->per_page;
		$pno = $security->escape_value($pno); 	
		//$data = $pagination->all_data(self::$table_name, $pno);
		$sql  = "SELECT * FROM ".self::$table_name." WHERE  `user_id` = '{$id}' LIMIT ".$pagination->per_page." OFFSET ".$offset;
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? $result_array  : false;
	}
	
	public function update_unique_url($id) {	  
		// Don't forget your SQL syntax and good habits:
		// - UPDATE table SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$sql = "UPDATE ".static::$table_name." SET `unique_url` = '".$this->unique_url."' WHERE id=". $this->security->escape_value($id);
	  $this->database->query($sql);
	  return ($this->database->affected_rows() == 1) ? true : false;
	}
	
}

$article = new Article();
?>