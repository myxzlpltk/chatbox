<?php

require "../config/bootstrap.php";

if($input->is_ajax()){
	$token = $input->post('token');
	$token = $db->escape($token);

	$chat = $db->select("SELECT * FROM `chat` WHERE `token` = '$token' AND `expired` >= CURRENT_TIMESTAMP AND `status` = 'enabled'")->row();

	if(!empty($chat)){
		$id = $chat['id_chat'];
		$last = $input->post('last');

		$sql = "SELECT `id_detail` AS `id`, `isi` AS `data`, `waktu`, `sumber` AS `source` FROM `detail_chat` WHERE `id_chat` = '$id'";
		if(!empty($last)){
			$last = dec($last);
			if(!empty($last)){
				$sql .= " AND `id_detail` < '$last'";
			}
			else{
				header('HTTP/1.0 406 Not Acceptable', 406, true);
				exit();
			}
		}
		$sql .= " ORDER BY `id_detail` DESC LIMIT 10";
		$output = $db->select($sql)->result();
		foreach ($output as $key => $value) {
			$output[$key]['id'] = enc($value['id']);
			$output[$key]['data'] = htmlentities($value['data']);
		}
		
		echo json_encode(array(
			'status' => true,
			'data' => $output
		));
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
