<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//error_reporting(0);

class Mailer extends CI_Model {

	public function __construct(){
		$this->load->library('PHPMailer.php');
        $this->owner = new PHPMailer();
        $this->owner->IsSMTP();
        $this->owner->SMTPAuth = true;
        $this->owner->IsHTML(true);
        $this->owner->CharSet = 'UTF-8';
        $this->owner->Host = "mail.emociondeportiva.com"; 
        $this->owner->From = "jobs@jobs.codice.com";
        $this->owner->FromName = "JOBS";
        $this->owner->Username = "registro@emociondeportiva.com";
        $this->owner->Password = "&ru0kR3e@Ee7";

		// $this->owner->Port = 587;
	}

	public function sendEmail($subject,$message,$to)
	{
		$this->owner->Body = $message;
		$this->owner->Subject = $subject;

		for($i=0,$n=count($to);$i<$n;$i++){
			$this->owner->AddAddress($to[$i]);
		}

		if (!$this->owner->send()) {
			echo "Mailer Error: " . $this->owner->ErrorInfo;
		} else {
			echo "Message sent!";
		}	
	}
}