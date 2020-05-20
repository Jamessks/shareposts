<?php
 session_start();


require '../vendor/autoload.php';
require_once 'helpers/helpers.php';
require 'config/config.php';

if(!isset($_SESSION['token'])){
     generateCSRFToken();
}
