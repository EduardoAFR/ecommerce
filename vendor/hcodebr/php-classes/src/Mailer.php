<?php

namespace Hcode; 

use Rain\Tpl; 

class Mailer {

	const USERNAME = "cursophp7hcode@gmail.com";
	const PASSWORD = "<?password?>";
	const NAME_FROM = "Hcode Store";  

	private $mail; 

	public function __construct($toAddress,$toName,$subject,$tplName,$data = array())
	{

	$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/email/",
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false // set to false to improve the speed
	);

	Tpl::configure( $config );

	$tpl = new Tpl; 

	foreach ($data as $key => $value) {
		$tpl->assign($key,$value);
	}

	$html = $tpl->draw($tplName,true);

	$this->mail = new \PHPMailer; 

	//Tell PHPMailer to use SMTP
	$this->mail->isSMTP(); 

	//Enable SMTP debuggin 
	//0 = off
	//1 = client messages
	//2 = client and server messages
	$this->mail->SMTPDebug = 0; 

	$this->mail->Debugoutput = 'html'; 

	//Set the hostname of the mail server
	$this->mail->Host = 'smtp.gmail.com'; 

	//Set the SMTP port number - 587 for authenticated TLS, a.k.a RFC4409 SMTP submission
	$this->mail->Port = 587; 

	//Set the encryption system to use - ssl (deprecated)
	$this->mail->SMTPSecure = 'tls';

	//Wheter to use SMTP authentication
	$this->mail->SMTPAuth = true; 

	//Username to use for SMTP authentication use full email adress for gmail
	$this->mail->Username = Mailer::USERNAME;

	//Pawword to use for SMTP authentication
	$this->mail->Password = Mailer::PASSWORD;

	//Set who the message is to be sent from
	$this->mail->setFrom(Mailer::USERNAME,Mailer::NAME_FROM); 

	//Set who the message is to be sent to 
	$this->mail->addAddress($toAddress,$toName);

	//Set the subject line
	$this->mail->Subject =$subject;

	//Read an HTML body from a external file,convert referenced image to embedded,convet HTML into a basic plain-text alternative body
	$this->mail->msgHTML($html); 

	//Replace the plain text body with on create manually
	$this->mail->AltBody = 'This is a plain-text message body';

	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	/*if(!mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo; 
	}else {
		echo "Message Sent!";
	}*/

	}

	public function send()
	{

		return $this->mail->send();
	}

}

?>