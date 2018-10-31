<?php

require "../config/bootstrap.php";

if($input->is_ajax()){	
	$sql = "SELECT `value` FROM `config` WHERE `name` = 'status'";
	$data = $db->select($sql)->row();

	$output = array(
		'status' => true,
		'data' => isset($data['value']) ? $data['value'] : 'active'
	);
	
	echo json_encode($output);
}
else{
	header('HTTP/1.0 406 Not Acceptable', 406, true);
}

exit();
