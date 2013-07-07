<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once('database.class.php');

class User extends DatabaseObject {
	
	protected static $table_name="users";
	protected static $db_fields = array('id', 'fb_id', 'email', 'password', 'first_name', 'last_name', 'profile_image', 'type', 'level', 'date_of_join_ts', 'last_login', 'pwd_attempt', 'pwd_time_reset');
	
	
	public $id;
	public $fb_id;
	public $email;
	public $password;
	public $first_name ;
	public $last_name;
	public $profile_image;
	public $date_of_join_ts;
    public $last_login;
	public $type;
	public $level;
	public $pwd_attempt;
	public $pwd_time_reset;

    public function full_name() {
      if(isset($this->first_name) && isset($this->last_name)) {
        return $this->first_name . " " . $this->last_name;
      } else {
        return "";
      }
    }

	public static function authenticate($email="", $password=""){
	$object = new self;
    $email = $object->security->escape_value($email);
    $password =	$object->security->escape_value($password);
	$sql  = "SELECT * FROM ".self::$table_name." WHERE  email = '{$email}' LIMIT 1";
	$result_array = self::find_by_sql($sql);
	$result_array=array_shift($result_array);
	$hash = !empty($result_array) ? $result_array->password : null;
		if($object->security->check_password($hash, $password)){
           // !empty($result_array)?  self::last_login($result_array->id):false;
			return !empty($result_array) ? $result_array  : false;
		}
		else{
		return false;
		}
	}
	public static function fb_authenticate($email="", $fb_id=""){
	$object = new self;
    $email = $object->security->escape_value($email);
	$sql  = "SELECT * FROM ".self::$table_name." WHERE  email = '{$email}' AND fb_id ='{$fb_id}'  LIMIT 1";
	$result_array = self::find_by_sql($sql);
	$result_array=array_shift($result_array);
    // !empty($result_array)?  self::last_login($result_array->id):false;
	return !empty($result_array) ? $result_array  : false;

	}
    public function last_login($id){
       $last_login = time();
        return $this->update_fields(array('last_login'), array($last_login), $id)? true:false;
    }
    
    
    
	public function fetch_user_by_email($email=""){
    $email = $this->security->escape_value($email);
	$sql  = "SELECT * FROM ".self::$table_name." WHERE  email = '{$email}' LIMIT 1";
	$result_array = self::find_by_sql($sql);
	$result_array=array_shift($result_array);
	return !empty($result_array) ? $result_array  : false;
	}
	
	public function fetch_user_by_id($id=""){
    $id = $this->security->escape_value($id);
	$sql  = "SELECT * FROM ".self::$table_name." WHERE  id = '{$id}' LIMIT 1";
	$result_array = self::find_by_sql($sql);
	$result_array=array_shift($result_array);
	return !empty($result_array) ? $result_array  : false;
	}
	

	 public function update_pwd($password) {
		$password = $this->security->escape_value($password);;
		$password = $this->security->hash($password);
		$sql = "UPDATE ".static::$table_name." SET password = '{$password}' WHERE id=". $this->id;
	  $this->database->query($sql);
	  return ($this->database->affected_rows() == 1) ? true : false;
	}
	
	 public function isset_fb_id($email) {
		$fb_user = $this->fetch_user_by_email($email);
	  return (isset($fb_user->fb_id) && !empty($fb_user->fb_id)) ? true : false;
	}
	
	 public function update_admin_pwd_attempt($id, $attempt) {
	 
	 switch($attempt){
	  case 0:
		 $this->pwd_attempt =0;
		 $this->pwd_time_reset = 0;
		 $this->update($id);
		 break;
	 case 1:
		 $this->pwd_attempt =1;
		 $this->update($id);
		  break;
	 case 2:
		 $this->pwd_attempt =2;
		 $this->update($id);
		  break;
	 case 3:
		 $this->pwd_attempt =3;
		 $this->update($id);
		  break;
	 case 4:
		 $this->pwd_attempt =0;
		 $this->pwd_time_reset = time() + 60*60*24;
		 $this->update($id);
		  break;
	 }
	}

}

$user = new User();
?>