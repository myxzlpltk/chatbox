<?php

/**
 * Database
 */
class Database extends Config{

	public $koneksi;
	public $error;
	public $result;
	public $last_query;

	public function __construct(){
		Parent::__construct();
		$this->connect();
	}

	private function connect(){
		$host = $this->item('hostname');
		$user = $this->item('username');
		$pass = $this->item('password');
		$db = $this->item('database');
		
		$this->koneksi = @mysqli_connect($host, $user, $pass, $db) or die('Tidak terhubung dengan basis data');
	}

	public function select($query){
		$this->last_query = $query;
		$this->result = mysqli_query($this->koneksi, $query);
		if($this->result == FALSE){
			$this->error = mysqli_error($this->koneksi);
		}
		return new Result($this->result);
	}

	public function query($query){
		$this->last_query = $query;
		$this->result = mysqli_query($this->koneksi, $query);
		if($this->result == FALSE){
			$this->error = mysqli_error($this->koneksi);
		}
		return $this->result;
	}

	public function insert($query){
		$this->last_query = $query;
		$this->result = mysqli_query($this->koneksi, $query);
		if($this->result == FALSE){
			$this->error = mysqli_error($this->koneksi);
		}
		return new Insert($this->result, $this->koneksi);
	}

	public function escape($str = ''){
		return mysqli_real_escape_string($this->koneksi, $str);
	}

}


/**
 * RESULT CLASS
 */
class Result{

	private $result;

	public function __construct($result){
		$this->result = $result;
		return $result;
	}
	
	public function result(){
		$data = array();
		if($this->result != FALSE){
			while($row = mysqli_fetch_assoc($this->result)){
				$data[] = $row;
			}
		}
		return $data;
	}

	public function row(){
		$data = null;
		if($this->result != FALSE){
			$data = mysqli_fetch_assoc($this->result);
		}
		return $data;
	}
}


/**
 * RESULT CLASS
 */
class Insert{

	private $result;
	public $insert_id;

	public function __construct($result, $koneksi){
		$this->result = $result;
		$this->insert_id = mysqli_insert_id($koneksi);
		return $result;
	}
}