<?php

require "../../config/bootstrap.admin.php";

if($input->is_ajax()){
	$id = $db->escape($input->post('id'));

	if(!empty($id)){
		$chat = $db->select("SELECT * FROM `chat` WHERE `id_chat` = '$id' AND `expired` >= CURRENT_TIMESTAMP AND `status` = 'enabled'")->row();
		
		if(!empty($chat)){
			$output = array('valid' => FALSE);
		}
		else{
			$output = array('valid' => TRUE);
		}

		$sql = "SELECT `id_detail` AS `id`, `isi` AS `msg`, `waktu`, `sumber` AS `source` FROM `detail_chat` WHERE `id_chat` = '$id' AND `sumber` = 'client' AND `status` = 'unread' ORDER BY `id_detail`";
		$output['data'] = $db->select($sql)->result();

		if(count($output) > 0){
			$detail = '('.implode(', ', array_column($output['data'], 'id')).')';
			
			$updateRead = "UPDATE `detail_chat` SET `status` = 'read' WHERE `id_detail` IN $detail";
			$db->query($updateRead);
		}

		echo json_encode($output);
	}
	else{
		header('HTTP/1.0 406 Not Acceptable', 406, true);
	}
}
else{
	header('HTTP/1.0 406 Not Acceptable', 406, true);
}
exit();