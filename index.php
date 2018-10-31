<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat Box</title>
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/roboto/roboto.css">
	<link rel="stylesheet" href="assets/main.css">
</head>
<body>

	<div class="box">
	</div>
	
	<script src="assets/jquery/jquery.min.js"></script>
	<script src="assets/popper/popper.min.js"></script>
	<script src="assets/sweetalert/sweetalert.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/moment/moment.min.js"></script>
	<script src="assets/moment/id.js"></script>
	<script src="assets/chatbox.js"></script>
	<script>
		$(document).ready(function() {
			$('.box').chatBox({
				api: {
					request: 'v1/request.php',
					send: 'v1/send.php',
					replies: 'v1/replies.php',
					history: 'v1/history.php',
					destroy: 'v1/destroy.php',
					status: 'v1/status.php',
				},
				sessionIndex: 'chatBoxToken',
				status: 'online',
				refreshInterval: 7500,
				limit: 10,
				updateStatusInterval: 60000,
				thankYouDict: 'Terima kasih! Percakapan telah berakhir.'
			});
		});
	</script>
</body>
</html>