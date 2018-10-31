<?php

require "../../config/bootstrap.admin.php";

if($input->is_ajax()){
	$id = $db->escape($input->post('id'));

	if(!empty($id)){
		$sql = "DELETE FROM `chat` WHERE `id_chat` = '$id'";

		$db->query($sql);

		$output = array(
			'status' => TRUE,
			'id' => "$id"
		);

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