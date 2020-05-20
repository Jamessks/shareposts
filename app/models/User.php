<?php

namespace App\models;
use App\libraries\Database;
use App\libraries\Mail;

 class User {
	 private $db;
      private $mail;

	 public function __construct()
	 {
		 $this->db = new Database;
	 }

	 public function findUserByEmail($email)
	 {
		$this->db->query('SELECT email FROM users WHERE email = :email' );
		$this->db->bind(':email', $email);

		$row = $this->db->single();

		if($this->db->rowCount() > 0){
			return true;
		} else {
			return false;
		}
	}

     public function getUserById($id)
     {
          $this->db->query('SELECT * FROM users WHERE id = :id');
          $this->db->bind(':id', $id);

          $row = $this->db->single();

          return $row;
     }

     public function registerUser($data)
     {
          //Prepare query
          $this->db->query('INSERT INTO users (name, email, password, vkey)  VALUES(:name, :email, :password, :vkey)');

          //Bind values
          $this->db->bind(':name', $data['name']);
          $this->db->bind(':email', $data['email']);
          $this->db->bind(':password', $data['password']);
          $this->db->bind(':vkey', $data['email_verification_key']);

          //Execute
          if($this->db->execute()){
               return true;
          } else {
               return false;
          }
     }

     public function login($email, $password)
     {
          $this->db->query('SELECT * FROM users WHERE email = :email');
          $this->db->bind(':email', $email);

          $row = $this->db->single();

          $hashed_password = $row->password;
          if(password_verify($password, $hashed_password)){
               return $row;
          } else {
               return false;
          }
     }

     public function sendVerificationMail($address,$vkey) //address and verification key
     {
          $verifyurl = URLROOT."/users/verify?vkey=$vkey";
          $this->mail = new Mail;
          $this->mail->setSubject('Verify your account');
          $this->mail->setBody('Visit this link to verify your account:<a href='.$verifyurl.'>here</a>');
          $this->mail->sendTo($address);
          if(!$this->mail->sendMail()){
               return true;
          } else {
               return false;
          }
     }

     public function emailIsVerified($email)
     {
          $this->db->query('SELECT verified FROM users WHERE email = :email');
          $this->db->bind(':email', $email);

          $row = $this->db->single();

          if($row->verified == 0){
               return false;
          } else {
               return true;
          }
     }

     public function verificationKeyExists($vkey)
     {
          $this->db->query('SELECT * FROM users WHERE vkey = :vkey');
          $this->db->bind(':vkey', $vkey);

          $this->db->execute();

          if($this->db->rowCount() > 0){
               return true;
          } else {
               return false;
          }
     }

     public function verifyUserEmail($vkey)
     {
          $this->db->query('UPDATE users SET verified = 1 WHERE vkey = :vkey');

          $this->db->bind(':vkey', $vkey);

          $this->db->execute();

          if($this->db->rowCount() > 0){
               return true;
          } else {
               return false;
          }
     }

     public function PasswordForgotTokenExistsByEmail($data)
     {
          $this->db->query('SELECT email FROM password_resets WHERE email = :email');

          $this->db->bind(':email',$data['email']);

          $this->db->execute();

          if($this->db->rowCount() > 0){
               return true;
          } else {
               return false;
          }
     }

     public function updatePasswordForgotToken($data)
     {
          $this->db->query('UPDATE password_resets SET token = :token WHERE email = :email');

          $this->db->bind(':email',$data['email']);
          $this->db->bind(':token',$data['pftoken']);

          if($this->db->execute()){
               return true;
          } else {
               return false;
          }
     }

     public function initPasswordForgotToken($data)
     {
          $this->db->query('INSERT INTO password_resets (email, token) VALUES(:email, :token)');

          $this->db->bind(':email',$data['email']);
          $this->db->bind(':token',$data['pftoken']);

          if($this->db->execute()){
               return true;
          } else {
               return false;
          }
     }

     public function sendPasswordForgotMail($data)
     {
          $passwordurl = URLROOT."/passwords/passwordReset/?pftoken=" . $data['pftoken'];
          $this->mail = new Mail;
          $this->mail->setSubject('Reset Password');
          $this->mail->setBody('Visit this link to reset your account\'s password:<a href='.$passwordurl.'>here</a>');
          $this->mail->sendTo($data['email']);
          if(!$this->mail->sendMail()){
               return true;
          } else {
               return false;
          }
     }

     public function PasswordForgotTokenExistsByToken($token)
     {
          $this->db->query('SELECT email FROM password_resets WHERE token = :token');

          $this->db->bind(':token',$token);

          $row = $this->db->single();

          if($row != 0){
               return $row->email;
          } else {
               return false;
          }
     }

     public function updatePasswordByEmail($data)
     {
          $this->db->query('UPDATE users SET password = :password WHERE email = :email');

          $this->db->bind(':email',$data['email']);
          $this->db->bind(':password',$data['password']);

          $this->db->execute();

          if($this->db->rowCount() > 0){
               return true;
          } else {
               return false;
          }
     }

     public function deletePasswordForgotEntryByEmail($data)
     {
          $this->db->query('DELETE FROM password_resets WHERE email = :email');

          $this->db->bind(':email',$data['email']);

          $this->db->execute();

          if($this->db->rowCount() > 0){
               return true;
          } else {
               return false;
          }
     }

     public function PasswordForgotTokenMailTimeoutByMinutes($data,$minutes)
     {
          $this->db->query('SELECT created_at FROM password_resets WHERE email = :email');

          $this->db->bind(':email',$data['email']);

          $row = $this->db->single();


          $gmtTimezone = new \DateTimeZone('GMT');
          $date2 = new \DateTime("now"); //this is server time
          $date2->modify("+1 hours");
          $date3 = new \DateTime($row->created_at); //this is local time
          $date3->modify("+$minutes minutes");

          if($date2 < $date3){
               return false;
          } else {
               return true;
          }
     }
} //end of class
