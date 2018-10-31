<?php

function flash(){
	$ci =& get_instance();
	if($ci->session->flashdata('success')){
		$success = '<div class="alert alert-success"><span class="close" data-dismiss="alert">&times;</span>'.$ci->session->flashdata('success').'</div>';
		echo $success;
	}
	if($ci->session->flashdata('error')){
		$error = '<div class="alert alert-danger"><span class="close" data-dismiss="alert">&times;</span>'.$ci->session->flashdata('error').'</div>';
		echo $error;
	}
}

function dump($data = null){
	echo "<pre>\n";
	var_dump($data);
	echo "</pre>\n";
}

function mail_date($date){
	$date = date_create($date);

	if(date_format($date, 'Y-m-d') == date('Y-m-d')){
		return date_format($date, "H:i");
	}
	else{
		return date_format($date, "d M");
	}
}