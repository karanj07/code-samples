<?php
require_once('database.class.php');
    // Include the PHPMailer classes
	// If these are located somewhere else, simply change the path.
	require_once("phpMailer/class.phpmailer.php");
	require_once("phpMailer/class.smtp.php");
	//require_once("phpMailer/language/phpmailer.lang-en.php");
	$phpmailer = new PHPMailer();
	class MyMail extends DatabaseObject{
	protected static $db_fields = array('id', 'user_id', 'to_name', 'to_mail', 'subject', 'message', 'from_name','from_mail','send_on');
	protected static $table_name="mail_temp";
	// mostly the same variables as before
	// ($to_name & $from_name are new, $headers was omitted)
	public $id;
	public $user_id;
	public $to_name = "Site User";
	public $to_mail;
	public $subject = "Password Reset Request";
	public $message ;
	public $from_name = "Website Support";
	public $from_mail = "noreply@website.com";
	public $send_on;
	// PHPMailer's Object-oriented approach
	
	/* public function get_contents(){
	return
	} */
	
	public function send_email(){
		global $phpmailer;
		$phpmailer->FromName = $this->from_name;
		$phpmailer->From     = $this->from_mail;
		$phpmailer->AddAddress($this->to_mail);
		$phpmailer->Subject  = $this->subject;
		$phpmailer->Body     = $this->message;
		$phpmailer->IsHTML(true);  
		//$this->headers = "X-Mailer: PHP/".phpversion()."\n";
		//$this->headers .= "MIME-Version: 1.0\n";
		//$this->headers .= "Content-Type: text/html; charset=iso-8859-1";
		//$result = mail($this->to, $this->subject, $this->message, $this->headers);
		//echo $result ? 'Sent' : 'Error';
		return ($phpmailer->Send()? true: false);
	}
	
	
	public function destroy($user_id){
		$sql  = "DELETE FROM ".self::$table_name." WHERE  `user_id` = '{$user_id}' ";
		$this->database->query($sql);
		return ($this->database->affected_rows() == 1) ? true : false;
	}
	
	// Can use SMTP
	// comment out this section and it will use PHP mail() instead
	/* $mail->IsSMTP();
	$mail->Host     = "your.host.com";
	$mail->Port     = 25;
	$mail->SMTPAuth = false;
	$mail->Username = "your_username";
	$mail->Password = "your_password"; */
	
	// Could assign strings directly to these, I only used the 
	// former variables to illustrate how similar the two approaches are.
}
$mail = new MyMail();
  
?>