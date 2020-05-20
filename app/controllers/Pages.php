<?php

namespace App\controllers;
use App\libraries\Controller;

class Pages extends Controller {
    public function __construct(){

    }

    public function index(){
        $data = [
          'title' => 'SharePosts',
          'description' => 'Test Description'
        ];

        $this->view('pages/index', $data);
      }

      public function about(){
        $data = [
          'title' => 'About Us',
          'description' => 'App to share posts with other users'
        ];

        $this->view('pages/about', $data);
      }
  }
