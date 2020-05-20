<?php

namespace App\libraries;

class Controller
{
	public function model($model)
	{
		$models = require_once MODELS_PATH . $model . '.php';
		$namespace = 'App\models\\';
		$namespace .=$model;

		return new $namespace;
	}

	public function view($view, $data=[])
	{
		if(file_exists(VIEWS_PATH . $view . '.php')){
			require_once VIEWS_PATH . $view . '.php';
		} else {
			die('View does not exist');
		}
	}
}
