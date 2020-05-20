<?php

namespace App\libraries;
use PDO;

class Database {
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $dbcharset;
    private $options;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct(){
      // Set DSN
      $array = require CONFIG_PATH.'dbconfig.php';
      $db = $array['database'];

      $this->host = $db['host'];
      $this->dbname = $db['dbname'];
      $this->dbcharset = $db['charset'];
      $this->user = $db['username'];
      $this->pass = $db['password'];
      $this->options = $db['options'];

      $dsn = $this->host . ';' . $this->dbname . ';' . $this->dbcharset;

      // Create PDO instance
      try{
        $this->dbh = new PDO($dsn, $this->user, $this->pass, $this->options);
      } catch(PDOException $e){
        $this->error = $e->getMessage();
        echo $this->error;
      }
    }

    // Prepare statement with query
    public function query($sql){
      $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null){
      if(is_null($type)){
        switch(true){
          case is_int($value):
            $type = PDO::PARAM_INT;
            break;
          case is_bool($value):
            $type = PDO::PARAM_BOOL;
            break;
          case is_null($value):
            $type = PDO::PARAM_NULL;
            break;
          default:
            $type = PDO::PARAM_STR;
        }
      }

      $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute(){
      return $this->stmt->execute();
    }

    // Get result set as array of objects
    public function resultSet(){
      $this->execute();
      return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single record as object
    public function single(){
      $this->execute();
      return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get row count
    public function rowCount(){
      return $this->stmt->rowCount();
    }
  }
