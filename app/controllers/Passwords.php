<?php

namespace App\controllers;
use App\libraries\Controller;

class Passwords extends Controller
{
	public function __construct()
	{
		$this->passwordsModel = $this->model('User');
	}
	public function index()
	{

	}
	public function reset()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)){

			//CSRF check
			if($_POST['token'] != $_SESSION['token']){
				die('An error occured.');
			}

			//sanitize data
			$_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

			//pass data into array
			$data = [
				'email' => trim($_POST['email']),
				'email_err' => ''
			];

			//Validate email existence
			if(!$this->passwordsModel->findUserByEmail($data['email'])){
				$data['email_err'] = 'This email address is not registered with our services';
			}
			//Validate email field
			if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
				$data['email_err'] = 'Please enter a valid email';
			}

			//if there are no errors
			if(empty($data['email_err'])){
				//Generate md5 hash
				$passwordForgetToken = generateMD5Hash($data['email']);
				$data['pftoken'] = $passwordForgetToken;

				//if user has already requested password recovery then update, else initialize
				if($this->passwordsModel->PasswordForgotTokenExistsByEmail($data)){
					if($this->passwordsModel->PasswordForgotTokenMailTimeoutByMinutes($data,'1')){
						$this->passwordsModel->updatePasswordForgotToken($data);
						//send mail
						$this->passwordsModel->sendPasswordForgotMail($data);
						//flash success here
						flash('mail_success','We\'ve successfully sent a mail to your inbox regarding your request to reset your password');
					} else {
						flash('mail_failure','Could not send request. Try again later.','warning');
					}
				} else {
					if($this->passwordsModel->PasswordForgotTokenMailTimeoutByMinutes($data,'1')){
						$this->passwordsModel->initPasswordForgotToken($data);
						//send mail
						$this->passwordsModel->sendPasswordForgotMail($data);
						//flash success here
						flash('mail_success','We\'ve successfully sent a mail to your inbox regarding your request to reset your password');
					}	else {
						flash('mail_failure','Could not send request. Try again later.','failure');
					}
				}
				$this->view('passwords/reset',$data); //flash email sent successfully
			} else { //load view with errors
				$this->view('passwords/reset',$data);
			}

		} else { //if request was not POST...
			$data = [
				'email' => '',
				'email_err' => ''
			];
			$this->view('passwords/reset');
		}
	}//end of reset function

	public function passwordReset()
	{
		if($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET)){
			if(isset($_GET['pftoken'])){
				if(strlen($_GET['pftoken']) == 32 && ctype_xdigit($_GET['pftoken'])){ //if pftoken is md5 hash
					if($email = $this->passwordsModel->PasswordForgotTokenExistsByToken($_GET['pftoken'])){
						$data = [
							'password' => '',
							'confirm_password' => '',
							'password_err' => '',
							'confirm_password_err' => '',
							'pftoken' => $_GET['pftoken']
						];
						$this->view('passwords/passwordReset',$data);
					}
				}
			}
		} else if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)){
			if(isset($_POST['pftoken'])){
				if(strlen($_POST['pftoken']) == 32 && ctype_xdigit($_POST['pftoken'])){ //if pftoken is md5 hash
					$_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
					if($email = $this->passwordsModel->PasswordForgotTokenExistsByToken($_POST['pftoken'])){
						//Validate Password
						$data = [
							'password' => $_POST['password'],
							'confirm_password' => $_POST['confirm_password'],
							'password_err' => '',
							'confirm_password_err' => '',
							'pftoken' => $_POST['pftoken'],
							'email' => $email
						];
						//Validate password field
						if(strlen($data['password']) < 6){
							$data['password_err'] = 'Password must be 6 or more characters';
							//Validate Confirm password
						} else if($data['confirm_password'] != $data['password']){
							$data['confirm_password_err'] = 'Passwords do not match';
						}



						if(empty($data['password_err']) && empty($data['confirm_password_err'])) {
							//hash password
							$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

							if($this->passwordsModel->updatePasswordByEmail($data)){
								if($this->passwordsModel->deletePasswordForgotEntryByEmail($data)){
									flash('register_success','You successfully reset your password. You may now use it to login');
									redirect('users/login');
								}
							} else {
								flash('register_success','There was in error when updating your password','failure');
								$this->view('passwords/passwordReset',$data);
							}
						} else { //if there exist errors...
							$this->view('passwords/passwordReset',$data);
						}
					}//if token doesn't exist in db
				} //if token is not hash
			}//if token is not set
		}//if request is not of type POST
	}//end of passwordRequest function

}//end of class Passwords
