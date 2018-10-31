<?php

require "../../config/bootstrap.admin.php";

if($input->is_ajax()){
	$except = $db->escape($input->post('except'));

	if(!empty($except)){
		$sql = "
			SELECT `chat`.`id_chat` AS `id`, `isi` AS `msg`, `nama` AS `name` 
			FROM (
				SELECT `id_chat`, `isi`, `max`.`id_detail` 
				FROM (
					SELECT MAX(`id_detail`) AS `id_detail` 
					FROM `detail_chat` 
					WHERE `id_chat` <> '$except' AND `status` = 'unread' AND `sumber` = 'client' 
					GROUP BY `id_chat`
				) AS `max` 
				INNER JOIN `detail_chat` ON `detail_chat`.`id_detail` = `max`.`id_detail`
			) AS `detail_chat` 
			INNER JOIN `chat` ON `chat`.`id_chat` = `detail_chat`.`id_chat` 
			ORDER BY `detail_chat`.`id_detail` DESC";

		$output = $db->select($sql)->result();
		echo($db->error);

		foreach ($output as $key => $value) {
			$output[$key]['name'] = htmlentities($value['name']);
			$output[$key]['msg'] = htmlentities($value['msg']);
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
