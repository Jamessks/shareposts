<?php

namespace App\libraries;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
	private $mail;
	private $host;
	private $from_address;
	private $password;
	private $username = '';
	private $port;
	private $enctype;

	public function __construct()
	{
		$array = require CONFIG_PATH.'mailconfig.php';
		$mail = $array['mail'];

		$this->host = $mail['MAIL_HOST'];
		$this->from_address = $mail['MAIL_FROM_ADDRESS'];
		$this->password = $mail['MAIL_PASSWORD'];
		$this->username = $mail['MAIL_USER'];
		$this->port = $mail['MAIL_PORT'];
		$this->enctype = $mail['MAIL_ENCTYPE'];

		$this->mail = new PHPMailer(TRUE);

		try {
			$this->mail->isSMTP();
			$this->mail->isHTML(true);
			$this->mail->Host = $this->host;
			$this->mail->setFrom($this->from_address);
			$this->mail->Username = $this->username;
			$this->mail->Password = $this->password;
			$this->mail->Port = $this->port;
			$this->mail->SMTPAuth = TRUE;
			$this->mail->SMTPSecure = $this->enctype;
			$this->mail->SMTPDebug = 0;
		}
		catch (\Exception $e)
		{
			echo $e->errorMessage();
		}
	}

	public function sendTo($address,$name='')
	{
		$this->mail->addAddress($address, $name);
	}

	public function setSubject($subject_title)
	{
		$this->mail->Subject = $subject_title;
	}

	public function setBody($body)
	{
		$this->mail->Body = $body;
	}

	public function sendMail()
	{
		$this->mail->send();
	}
}
