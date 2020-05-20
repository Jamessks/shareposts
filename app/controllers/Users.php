<?php

namespace App\controllers;
use App\libraries\Controller;

class Users extends Controller
{
	public function __construct()
	{
		$this->userModel = $this->model('User');
	}
	public function register()
	{

		if($_SERVER['REQUEST_METHOD'] == 'POST'&& !empty($_POST)){
			//handle CSRF token
			if($_POST['token'] != $_SESSION['token']){
				die('An error occured.');
			}
			$_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

			$data = [
				'name' => trim($_POST['name']),
				'email' => trim($_POST['email']),
				'password' => $_POST['password'],
				'confirm_password' => $_POST['confirm_password'],
				'name_err' => '',
				'email_err' => '',
				'password_err' => '',
				'confirm_password_err' => '',
				'email_verification_key' => ''
			];

			//Validate name, field not empty, 3 or more chars,no special chars
			if(empty($data['name'])){
				$data['name_err'] = 'Please enter a name';
			} elseif (preg_match('/[\`^£$%&*()}{@#~?><>,|=_+¬-]/', $data['name'])) {
				$data['name_err'] = 'Special characters are not allowed';
			} elseif (strlen($data['name']) < 3) {
				$data['name_err'] = 'Name must be 6 or more characters';
			}
			//Validate email
			if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
				$data['email_err'] = 'Please enter a valid email';
			}
			//Validate email uniqueness
			if($this->userModel->findUserByEmail($data['email'])){
				$data['email_err'] = 'Already exists';
			}
			//Validate Password
			if(strlen($data['password']) < 6){
				$data['password_err'] = 'Password must be 6 or more characters';
			}
			//Validate Confirm password
			if($data['confirm_password'] != $data['password']){
				$data['confirm_password_err'] = 'Passwords do not match';
			}

			//No errors, proceed with database validation and user insertion
			if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err'])
			&& empty($data['password_confirm_err'])){

				//Generate vkey
				$vkey = generateMD5Hash($data['name']);
				$data['email_verification_key'] = $vkey;
				//Hash password
				$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

				if($this->userModel->registerUser($data)){ //if database inserted user successfully, then send mail
					if($this->userModel->sendVerificationMail($data['email'],$data['email_verification_key'])){
					//send verification key
					flash('register_success','Verify your account by following the link that we sent to your e-mail address');
					redirect('users/login'); //make thank you page?
				} else {
					//error while sending mail
					flash('register_danger','An error was encountered while sending the verification link to your email address.','failure');
				}
				} else {
					//error while connecting to database
					flash('register_danger','An error was encountered while sending your information to our system.','failure');
				}

			} else { //if there exist errors...
				$this->view('users/register', $data);
			}
		} else { //if request is not of type POST...
		$data = [
			'name' => '',
			'email' => '',
			'password' => '',
			'confirm_password' => '',
			'name_err' => '',
			'email_err' => '',
			'password_err' => '',
			'confirm_password_err' => ''
		];
		$this->view('users/register', $data);
	}
} //end of register() function

	public function login()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)){
			//handle CSRF token
			if($_POST['token'] != $_SESSION['token']){
				die('An error occured.');
			}
		$data = [
			'email' => $_POST['email'],
			'password' => $_POST['password'],
			'email_err' => '',
			'password_err' => ''
		];

		if(empty($data['email'])){ //check if mail field is empty
			$data['email_err'] = 'Enter your email';
		} else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){ //check if email is an actual email address
			$data['email_err'] = 'Please enter a valid email';
		} else {
			if(!$this->userModel->findUserByEmail($data['email'])){//check if mail exists in database
				$data['email_err'] = 'Email provided is
				 				not registered with our services';
			} else if(!$this->userModel->emailIsVerified($data['email'])){//check if email has been verified
				$data['email_err'] = 'Email has not yet been verified';
			} else { //only if email field is OK...
				if(empty($data['password'])){ //check if password field is empty
					$data['password_err'] = 'Please, enter your password';
				} else { //check if password matches the email's password
					if(!$user = $this->userModel->login($data['email'],$data['password'])){
						$data['password_err'] = 'Password is incorrect';
					}
				}
			}
		}

		if(empty($data['email_err']) && empty($data['password_err'])){

			createUserSession($user);
			redirect('');

		} else { //return view with triggered errors
			$this->view('users/login', $data);
		}
	}  else { //if request is not of type POST...
		$data = [
			'email' => '',
			'password' => '',
			'email_err' => '',
			'password_err' => '',
		];
		$this->view('users/login', $data);
	}
} //end of login() function
	public function verify()
	{
		if($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET)){
			if(isset($_GET['vkey'])){
				if(strlen($_GET['vkey']) == 32 && ctype_xdigit($_GET['vkey'])){ //if vkey is md5 hash
					//check if verification key exists inside database
					if($this->userModel->verificationKeyExists($_GET['vkey'])){
						if($this->userModel->verifyUserEmail($_GET['vkey'])){
							flash('register_success','Your account was successfully verified! You may now login');
						}
					} else {
						flash('register_danger','No verification request was found','warning');
					}
				}
			}
		} //if method is GET
					redirect('users/login');
	} //end of verify function

	public function logout()
	{
		if(isLoggedIn()){
			userLogout();
		}
		redirect('');
	}
} //end of class
