<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mailbox</title>
	<link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/libs/font-awesome/css/font-awesome.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/libs/datatables/css/dataTables.bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/fonts/roboto/roboto.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
</head>
<body>


	<div class="container mt-3">
		<div class="row">
			
			<?php $this->load->view('sidebar', array('menu' => $menu)) ?>

			<div class="col-md-9">

				<?php flash() ?>

				<div class="card card-clean">
					<div class="card-header">
						<span class="float-right text-secondary"><?= date("d M Y H:i", strtotime($mail->date)) ?></span>
						<h3 class="lead"><?= html_escape($mail->title) ?></h3>
					</div>
					<div class="card-body">
						<div class="clearfix">
							<p class="m-0 float-left">
								From : 
								<b>
									<?= html_escape($mail->origin->nama) ?>	
								</b>
								<br>
								To : 
								<b>
									<?= html_escape($mail->dest->nama) ?>	
								</b>
							</p>
							<div class="float-right">
								<?php if($mail->dest->id_user == $this->session->userdata('id')){ ?>
									
									<?php if($mail->trash == 0){ ?>
									<a href="<?= base_url($prefix.'mail/compose/'.($this->session->userdata('role') == 'admin' ? $mail->origin->id_user : '')) ?>" id="replyMail" class="btn btn-primary btn-sm" title="Balas"><i class="fa fa-reply"></i></a>
									<a href="<?= base_url($prefix.'mail/trash/'.$mail->id_mail) ?>" id="deleteMail" class="btn btn-danger btn-sm" title="Buang"><i class="fa fa-trash"></i></a>
									<?php }else{ ?>
									<a href="<?= base_url($prefix.'mail/restore/'.$mail->id_mail) ?>" id="restoreMail" class="btn btn-warning btn-sm" title="Kembalikan"><i class="fa fa-exclamation-circle"></i></a>
									<?php } ?>

								<?php } ?>
							</div>
						</div>
						<hr>
						<iframe src="<?= base_url($prefix.'mail/content/'.$mail->id_mail) ?>" class="my-3" sandbox="allow-same-origin"></iframe>
					</div>
				</div>
			</div>
		</div>
	</div>
	

	<script src="<?= base_url('assets/libs/jquery/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/libs/popper/popper.min.js') ?>"></script>
	<script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.min.js') ?>"></script>
	<script src="<?= base_url('assets/libs/datatables/js/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('assets/libs/datatables/js/dataTables.bootstrap4.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>
</html>