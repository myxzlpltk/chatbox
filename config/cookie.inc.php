<?php

/**
 * SESSION
 */
class Cookie{

	public function set($data, $value=null, $hour = 1){
		if(is_array($data)){
			foreach ($data as $k => $v) {
				setcookie($k, $v, time() + 3600 * $hour);
			}
			return TRUE;
		}
		elseif(is_string($data) && !is_null($value)){
			setcookie($data, $value, time() + 3600 * $hour);
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	public function item($index=null){
		if(is_null($index)){
			return $_COOKIE;
		}
		elseif(isset($_COOKIE[$index])){
			return $_COOKIE[$index];
		}
		else{
			return null;
		}
	}

	public function destroy(){
		foreach ($_COOKIE as $key => $value) {
			unset($_COOKIE[$key]);
			setcookie($key, '', time()-10);
		}
		return true;
	}

}