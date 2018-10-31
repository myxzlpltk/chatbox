<?php

require "../../config/bootstrap.admin.php";

if($input->is_ajax()){
	$id = $db->escape($input->post('id'));
	$last = $db->escape($input->post('last'));


	if(!empty($id)){
		if(empty($last)){
			$sql = "SELECT `id_detail` AS `id`, `isi` AS `msg`, `waktu`, `sumber` AS `source` FROM `detail_chat` WHERE `id_chat` = '$id' ORDER BY `id_detail` DESC LIMIT $_limit";
		}
		else{
			$sql = "SELECT `id_detail` AS `id`, `isi` AS `msg`, `waktu`, `sumber` AS `source` FROM `detail_chat` WHERE `id_chat` = '$id' AND `id_detail` < '$last' ORDER BY `id_detail` DESC LIMIT $_limit";
		}
		$output = $db->select($sql)->result();
		
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
