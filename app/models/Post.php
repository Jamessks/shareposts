<?php

namespace App\models;
use App\libraries\Database;

class Post {
	private $db;

	public function __construct()
	{
		$this->db = new Database;
	}

	public function getPosts()
	{
		$this->db->query('SELECT p.title,p.body,p.created_at,u.name,
					p.id AS post_id,
					u.id AS user_id
					FROM posts p
					INNER JOIN users u
					ON p.user_id = u.id
					ORDER BY p.created_at DESC
					');
				return $this->db->resultSet();
		}

	public function insertPost($data)
	{
		//prepare query
		$this->db->query('INSERT INTO posts (user_id, title, body) VALUES (:user_id, :title, :body)');
		//bind values
		$this->db->bind(':user_id',$data['user_id']);
		$this->db->bind(':title', $data['post_title']);
		$this->db->bind(':body', $data['post_body']);
		//execute  query
		$this->db->execute();

		if($this->db->rowCount() > 0){
			return true;
		} else {
			return false;
		}
	}

	public function deletePost($id)
	{
		//prepare query
		$this->db->query('DELETE FROM posts where id = :id');
		//bind values
		$this->db->bind(':id',$id);
		//execute  query
		$this->db->execute();

		if($this->db->rowCount() > 0){
			return true;
		} else {
			return false;
		}

	}
	public function updatePost($data)
	{
		//prepare query
		$this->db->query('UPDATE posts SET body = :body, title = :title WHERE id = :id');
		//bind values
		$this->db->bind(':id',$data['post_id']);
		$this->db->bind(':title', $data['post_title']);
		$this->db->bind(':body', $data['post_body']);
		//execute  query
		$this->db->execute();

		if($this->db->rowCount() > 0){
			return true;
		} else {
			return false;
		}
	}
	public function getPostById($id)
	{
		//prepare query
		$this->db->query('SELECT * FROM posts WHERE id = :id');
		//bind values
		$this->db->bind(':id',$id);
		//execute query
		if($this->db->execute()){
			return $this->db->single();
		} else {
			return false;
		}
	}
	}//end of class Post
