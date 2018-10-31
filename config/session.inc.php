<?php

/**
 * SESSION
 */
class Session{

	public function __construct(){
		session_start();
	}

	public function set($data, $value=null){
		if(is_array($data)){
			foreach ($data as $k => $v) {
				$_SESSION['userdata'][$k] = $v;
			}
			return TRUE;
		}
		elseif(is_string($data) && !is_null($value)){
			$_SESSION['userdata'][$data] = $value;
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	public function item($index=null){
		if(is_null($index)){
			return $_SESSION['userdata'];
		}
		elseif(isset($_SESSION['userdata'][$index])){
			return $_SESSION['userdata'][$index];
		}
		else{
			return null;
		}
	}

	public function destroy(){
		session_destroy();
		return true;
	}

	public function set_flash($item, $msg){
		if($item != 'userdata'){
			$_SESSION[$item] = $msg;
			return true;
		}
		else{
			return false;
		}
	}

	public function has_flash($item){
		return isset($_SESSION[$item]);
	}

	public function flash($item){
		if(isset($_SESSION[$item])){
			$data = $_SESSION[$item];
			unset($_SESSION[$item]);
			return $data;
		}
		else{
			return NULL;
		}
	}

}