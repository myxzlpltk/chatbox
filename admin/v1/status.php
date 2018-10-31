<?php

require "../../config/bootstrap.admin.php";

if($input->is_ajax()){
	$status = $db->escape($input->post('status'));

	if(empty($status)){
		$sql = "SELECT `value` FROM `config` WHERE `name` = 'status'";
		$data = $db->select($sql)->row();

		$output = array(
			'status' => true,
			'data' => isset($data['value']) ? $data['value'] : 'active'
		);
		
		echo json_encode($output);
	}
	else{
		$sql = "UPDATE `config` SET `value` = '$status' WHERE `name` = 'status'";
		$db->query($sql);

		$output = array(
			'status' => true,
			'data' => $status
		);
		
		echo json_encode($output);
	}
}
else{
	header('HTTP/1.0 406 Not Acceptable', 406, true);
}
exit();