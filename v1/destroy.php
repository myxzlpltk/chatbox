<?php

require "../config/bootstrap.php";

if($input->is_ajax()){
	$token = $input->post('token');
	$token = $db->escape($token);

	$chat = $db->select("SELECT * FROM `chat` WHERE `token` = '$token' AND `expired` >= CURRENT_TIMESTAMP AND `status` = 'enabled'")->row();

	if(!empty($chat)){
		$id = $chat['id_chat'];
		
		$sql = "UPDATE `chat` SET `status` = 'disabled' WHERE `id_chat` = '$id'";
		$db->query($sql);

		$output = array(
			'status' => true
		);
		
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
}

exit();
