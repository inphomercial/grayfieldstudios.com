<?php
/*
* Contact Form Class
*/

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 2015 05:00:00 GMT');
header('Content-type: application/json');

$admin_email = 'sean@greyfieldstudios.com'; // Your Email
$message_min_length = 5; // Min Message Length


class Contact_Form{
	function __construct($details, $email_admin, $message_min_length){

		$this->name    = stripslashes($details['name']);
		$this->email   = trim($details['email']);
		$this->subject = 'Contact from Greyfield Studios form';
		$this->message = stripslashes($details['message']);

		$this->email_admin        = $email_admin;
		$this->message_min_length = $message_min_length;

		$this->response_status = 1;
		$this->response_html = Array();
	}


	private function validateEmail(){
		$regex = '/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i';

		if($this->email == '') {
			return false;
		}

		$string = preg_replace($regex, '', $this->email);

		return empty($string) ? true : false;
	}


	private function validateFields(){
		// Check name
		if(!$this->name) {
            $this->response_html["name"] = "Please enter a name";
			$this->response_status = 0;
		}

		// Check email
		if(!$this->email) {
            $this->response_html["email"] = "Please enter an email address";
			$this->response_status = 0;
		}

		// Check valid email
		if($this->email && !$this->validateEmail()) {
            $this->response_html["email"] = "Please enter a valid email address";
			$this->response_status = 0;
		}

		// Check message length
		if(!$this->message || strlen($this->message) < $this->message_min_length) {
            $this->response_html["message"] = "Please enter a message that is at least " . $this->message_min_length . " characters.";
           	$this->response_status = 0;
		}
	}

	private function sendEmail(){
		$mail = mail($this->email_admin, $this->subject, $this->message,
			 "From: ".$this->name." <".$this->email.">\r\n"
			."Reply-To: ".$this->email."\r\n"
		    ."X-Mailer: PHP/" . phpversion());

		if($mail) {
			$this->response_status = 1;
			$this->response_html['name'] = '<p>Thank You!</p>';
		}
	}

	public function sendRequest(){
		$this->validateFields();

		if($this->response_status) {
			$this->sendEmail();
		}

		$response = array();
		$response['status'] = $this->response_status;
		$response['html'] = $this->response_html;

		echo json_encode($response);
	}
}

$contact_form = new Contact_Form($_POST, $admin_email, $message_min_length);
$contact_form->sendRequest();
