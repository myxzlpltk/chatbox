<?php

require "../../config/bootstrap.admin.php";

if($input->is_ajax()){
	$last = $db->escape($input->post('last'));

	if(empty($last)){
		$sql = "
			SELECT `chat`.`id_chat` AS `id`, `isi` AS `msg`, `nama` AS `name`, IF(`expired` < CURRENT_TIMESTAMP OR `status` <> 'enabled', true, false) AS `isBlocked`
			FROM (
				SELECT `id_chat`, `isi` 
				FROM (
					SELECT MAX(`id_detail`) AS `id_detail` 
					FROM `detail_chat` 
					GROUP BY `id_chat`
				) AS `max` 
				INNER JOIN `detail_chat` ON `detail_chat`.`id_detail` = `max`.`id_detail`
			) AS `detail_chat` 
			INNER JOIN `chat` ON `chat`.`id_chat` = `detail_chat`.`id_chat` 
			ORDER BY `chat`.`id_chat` DESC 
			LIMIT $_limit";
	}
	else{
		$sql = "
			SELECT `chat`.`id_chat` AS `id`, `isi` AS `msg`, `nama` AS `name`, IF(`expired` < CURRENT_TIMESTAMP OR `status` <> 'enabled', true, false) AS `isBlocked`
			FROM (
				SELECT `id_chat`, `isi` 
				FROM (
					SELECT MAX(`id_detail`) AS `id_detail` 
					FROM `detail_chat` 
					WHERE `id_chat` < '$last'
					GROUP BY `id_chat`
				) AS `max` 
				INNER JOIN `detail_chat` ON `detail_chat`.`id_detail` = `max`.`id_detail`
			) AS `detail_chat` 
			INNER JOIN `chat` ON `chat`.`id_chat` = `detail_chat`.`id_chat` 
			ORDER BY `chat`.`id_chat` DESC 
			LIMIT $_limit";
	}
	$output = $db->select($sql)->result();

	foreach ($output as $key => $value) {
		$output[$key]['name'] = htmlentities($value['name']);
		$output[$key]['msg'] = htmlentities($value['msg']);
	}

	echo json_encode($output);
}
else{
	header('HTTP/1.0 406 Not Acceptable', 406, true);
}
exit();
