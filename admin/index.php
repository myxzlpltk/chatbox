<?php require "../config/bootstrap.admin.php" ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin | Chat Box</title>
	<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../assets/admin.css">
</head>
<body>

	<div id="chats-box">
		
	</div>
	
	<script src="../assets/jquery/jquery.min.js"></script>
	<script src="../assets/sweetalert/sweetalert.min.js"></script>
	<script src="../assets/moment/moment.min.js"></script>
	<script src="../assets/moment/id.js"></script>
	<script src="../assets/chatbox-admin.js"></script>
	<script>
		$(document).ready(function() {
			$('#chats-box').chatBox({
				api: {
					send: 'v1/send.php',
					replies: 'v1/replies.php',
					history: 'v1/history.php',
					contact: 'v1/contact.php',
					chat: 'v1/chat.php',
					block: 'v1/block.php',
					status: 'v1/status.php',
					delete: 'v1/delete.php',
				},
				limit: <?= $_limit ?>,
				sessionIndex: 'chatBoxToken',
				status: 'active',
				refreshInterval: 7500,
				momentInterval: 300000
			});
		});
	</script>
</body>
</html>