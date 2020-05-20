<?php

namespace App\libraries;
use App\controllers;

class Core
{
	protected $currentController = 'Pages';
	protected $currentMethod = 'index';
	protected $currentParams = [];
	public static $currentURL = '';

	public function __construct()
	{
		$url = $this->getURL();

		 if(isset($url[0])){
			 if(file_exists(CONTROLLER_PATH.ucwords($url[0]).'.php')){
			 $this->currentController = ucwords($url[0]);
		 }
	 }
		 $namespace = 'App\controllers\\';
		 $namespace .=$this->currentController;
		 $this->currentController = new $namespace;
		 unset($url[0]);

		 if(isset($url[1]))
		 {
			 if(method_exists($this->currentController,$url[1]))
			 {
				 $this->currentMethod = $url[1];
				 unset($url[1]);
			 }
		 }

		 $this->currentParams = $url ? array_values($url) : [];

		 call_user_func_array([$this->currentController,
		 			$this->currentMethod],$this->currentParams);
	}

	public function getURL()
	{
		if(isset($_GET['url']))
		{
		self::$currentURL = explode('/',$_GET['url']);
		$url = rtrim($_GET['url'],'/');
		$url = filter_var($url, FILTER_SANITIZE_URL);
		$url = explode('/',$url);

		return $url;
		}
	}
}
