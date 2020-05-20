<?php

namespace App\controllers;
use App\libraries\Controller;

class Posts extends Controller
{
	public function __construct()
	{
		$this->postModel = $this->model('Post');
		$this->userModel = $this->model('User');
	}

	public function index()
	{
		//fetch all posts
		$posts = $this->postModel->getPosts();
		//assign all posts to array
		$data = [
			'posts' => $posts
		];
		//return view
		$this->view('posts/index',$data);
	}

	public function add()
	{
		//if user is not logged in, redirect to posts index page
		if(!isset($_SESSION['user_id'])){
			redirect('posts');
		}
		if($_SERVER['REQUEST_METHOD'] == 'POST'&& !empty($_POST)){
			//handle CSRF token
			if($_POST['token'] != $_SESSION['token']){
				die('An error occured.');
			}
			//sanitize data
			$_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
			//assign data to array
			$data = [
				'post_title' => $_POST['post_title'],
				'post_body' => $_POST['post_body'],
				'user_id' => $_SESSION['user_id'],
				'post_title_err' => '',
				'post_body_err' => ''
			];
			//validate data
			if(empty($data['post_title'])) {
				$data['post_title_err'] = 'Please enter a title';
			}

			if(empty($data['post_body'])){
				$data['post_body_err'] = 'Please fill the body';
			}
			//If there are no errors...
			if(empty($data['post_title_err']) && empty($data['post_body_err'])) {
				//if Post was inserted...
				if($this->postModel->insertPost($data)){
					flash('post_success','Your Post was added!');
					redirect('posts');
				} else {//or not...
					flash('post_failure','There was an error in uploading your post','failure');
					redirect('posts');
				}

			} else { //Return view with errors
				$this->view('posts/add',$data);
			}

		} else { //if request was not of type POST
			$data = [
				'title' => '',
				'body' => '',
				'post_title_err' => '',
				'post_body_err' => ''
			];
			//return view
			$this->view('posts/add',$data);
		}
	}//end of function add

	public function show($id)
	{
		$post = $this->postModel->getPostById($id);
		$user = $this->userModel->getUserById($post->user_id);

		$data = [
			'post' => $post,
			'user' => $user
		];
		$this->view('posts/show', $data);
	}//end of function show

	public function edit($id){
		$post = $this->postModel->getPostbyId($id);
		// Check for owner
		if ($post->user_id != $_SESSION['user_id']) {
			flash('post_edit_warning', 'You are not allowed to edit this post','warning');
			redirect('posts');
			exit();
		}

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			// Sanitize POST array
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

			$data = [
				'post_id' => $id,
				'post_title' => trim($_POST['post_title']),
				'post_body' => trim($_POST['post_body']),
				'user_id' => $_SESSION['user_id'],
				'post_title_err' => '',
				'post_body_err' => ''
			];

			// Validate data
			if(empty($data['post_title'])){
				$data['post_title_err'] = 'Please enter title';
			}
			if(empty($data['post_body'])){
				$data['post_body_err'] = 'Please enter body text';
			}

			// Make sure no errors
			if(empty($data['post_title_err']) && empty($data['post_body_err'])){
				// Validated
				if($this->postModel->updatePost($data)){
					flash('post_edit_warning', 'Post Updated');
					redirect('posts');
				} else {
					flash('post_edit_failure', 'Failed to update post');
					redirect('posts');
				}
			} else {
				// Load view with errors
				$this->view('posts/edit', $data);
			}

		} else {
			// Get existing post from model
			$post = $this->postModel->getPostById($id);


			$data = [
				'post_id' => $id,
				'post_title' => $post->title,
				'post_body' => $post->body
			];

			$this->view('posts/edit', $data);
		}
	}

	public function delete($id)
	{
		$post = $this->postModel->getPostbyId($id);
		// Check for owner
		if ($post->user_id != $_SESSION['user_id']) {
			flash('post_delete_warning', 'You are not allowed to delete this post','warning');
			redirect('posts');
			exit();
		}
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			// Sanitize POST array
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

			if($this->postModel->deletePost($id)){
				flash('post_delete_success','Post deleted','success');
				redirect('posts');
				exit();
			} else {
				flash('post_delete_failure','Something went wrong','failure');
				redirect('posts');
				exit();
			}


		}
	}//end of function delete
}//end of class
