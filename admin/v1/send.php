<?php

require "../../config/bootstrap.php";

if($input->is_ajax()){

	$id = $db->escape($input->post('id'));

	if(!empty($id)){
		$msg = $db->escape($input->post('msg'));

		$result = $db->insert("INSERT INTO `detail_chat`(`id_detail`, `id_chat`, `sumber`, `isi`, `waktu`, `status`) VALUES (null,'$id','admin','$msg',CURRENT_TIMESTAMP,'unread')");
		if($result){
			$output = array(
				'status' => TRUE,
				'sent' => $msg,
				'id' => $result->insert_id
			);
		}
		else{
			$output = array(
				'status' => FALSE,
				'error' => 'Gagal mengirim pesan'
			);
		}
		
		echo json_encode($output);
	}
	else{
		header('HTTP/1.0 406 Not Acceptable', 406, true);
		exit();
	}
}
else{
	header('HTTP/1.0 406 Not Acceptable', 406, true);
	exit();
}