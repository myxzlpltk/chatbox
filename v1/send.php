<?php

require "../config/bootstrap.php";

if($input->is_ajax()){
	$token = $input->post('token');
	$token = $db->escape($token);

	$chat = $db->select("SELECT * FROM `chat` WHERE `token` = '$token' AND `expired` >= CURRENT_TIMESTAMP AND `status` = 'enabled'")->row();

	if(!empty($chat)){
		$id = $chat['id_chat'];
		$msg = $input->post('message');
		$msg = $db->escape($msg);

		$result = $db->insert("INSERT INTO `detail_chat`(`id_detail`, `id_chat`, `sumber`, `isi`, `waktu`, `status`) VALUES (null,'$id','client','$msg',CURRENT_TIMESTAMP,'unread')");
		if($result){
			$output = array(
				'status' => TRUE,
				'sent' => $msg,
				'id' => enc($result->insert_id)
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
		echo json_encode(array(
			'status' => false,
			'validated' => false,
			'error' => 'Token tidak valid'
		));
	}
}
else{
	header('HTTP/1.0 406 Not Acceptable', 406, true);
	exit();
}