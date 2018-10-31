<?php

/**
 * Input
 */
class Input{
	
	public function get($index){
		if(isset($_GET[$index])){
			return $_GET[$index];
		}
		else{
			return '';
		}
	}


	public function post($index){
		if(isset($_POST[$index])){
			return $_POST[$index];
		}
		else{
			return '';
		}
	}

	public function is_ajax(){
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
	}

}