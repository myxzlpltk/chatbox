<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mailbox</title>
	<link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/libs/font-awesome/css/font-awesome.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/fonts/roboto/roboto.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
</head>
<body>

	<div class="container p-5">
		<div class="col-md-8 col-lg-5 mx-auto">

			<?php flash() ?>
				
			<div class="card p-4 border-0">
				<?= form_open() ?>
					<div class="form-group">
						<label>Username</label>
						<input type="text" name="username" class="form-control">
						<?= form_error('username', '<span class="text-danger">', '</span>') ?>
					</div>
					<div class="form-group">
						<label>Password</label>
						<input type="password" name="password" class="form-control">
						<?= form_error('password', '<span class="text-danger">', '</span>') ?>
					</div>
					<input type="submit" class="btn btn-primary btn-block" value="Login">
				<?= form_close("\n") ?>
			</div>
		</div>
	</div>
	

	<script src="<?= base_url('assets/libs/jquery/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/libs/popper/popper.min.js') ?>"></script>
	<script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>
</html>