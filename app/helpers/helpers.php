<?php

function redirect($url)
{
	header('Location:'.URLROOT.'/'.$url);
}

function flash($name = '',$message = '',$class = '')
{
	switch($class){
		case 'failure':
			$class = 'alert alert-danger';
			break;
		case 'warning':
			$class = 'alert alert-warning';
			break;
		default:
		$class = 'alert alert-success';
	}
	if(!empty($name)){
		if(!empty($message) && empty($_SESSION[$name])){

			if(!empty($_SESSION[$name])){
				unset($_SESSION[$name]);
			}
			if(!empty($_SESSION[$name. '_class'])){
				unset($_SESSION[$name. '_class']);
			}

			$_SESSION[$name] = $message;
			$_SESSION[$name. '_class'] = $class;
		}	elseif(empty($message) && !empty($_SESSION[$name])){
			$class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
			echo
			'<div class="'.$class.'" role="alert" id="msg-flash">'.$_SESSION[$name].'
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>';
			unset($_SESSION[$name]);
			unset($_SESSION[$name. '_class']);
		}
	}
}
function generateMD5Hash($complement)
{
	$hash = md5(time().$complement);
	return $hash;
}

function generateCSRFToken()
{
	$_SESSION['token'] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32);
}

function dd($var)
{
	die(print_r($var));
}
function createUserSession($user)
{
	$_SESSION['user_id'] = $user->id;
	$_SESSION['user_email'] = $user->email;
	$_SESSION['user_name'] = $user->name;
}

function userLogout()
{
	unset($_SESSION['user_id']);
	unset($_SESSION['user_email']);
	unset($_SESSION['user_name']);
	session_destroy();
}

function isLoggedIn()
{
	if(isset($_SESSION['user_id'])){
		return true;
	} else {
		return false;
	}
}
