<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mailbox</title>
	<link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/libs/select2/css/select2.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/libs/font-awesome/css/font-awesome.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/fonts/roboto/roboto.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
</head>
<body>


	<div class="container mt-3">
		<div class="row">

			<?php $this->load->view('sidebar') ?>

			<div class="col-md-9">

				<?php flash() ?>

				<div class="card card-clean">
					<div class="card-header">
						<h3 class="lead">Compose</h3>
					</div>
					<div class="card-body">
						<?= form_open() ?>
							<?php if($this->session->userdata('role') == 'admin'){ ?>
							<div class="form-group">
								<label>Tujuan</label>
								<select name="dest" class="form-control" required>
									<?php if(!empty($user)){ ?>
										<option value="<?= $user->id_user ?>" selected><?= $user->nama ?></option>
									<?php } ?>
								</select>
								<?= form_error('dest', '<span class="text-danger">', '</span>') ?>
							</div>
							<?php } ?>
							<div class="form-group">
								<label>Judul</label>
								<input type="text" name="title" class="form-control" value="<?= html_escape(set_value('title')) ?>">
								<?= form_error('title', '<span class="text-danger">', '</span>') ?>
							</div>
							<div class="form-group">
								<label>Konten</label>
								<textarea name="content" class="form-control ckeditor"><?= set_value('content') ?></textarea>
								<?= form_error('content', '<span class="text-danger">', '</span>') ?>
							</div>
							<button type="submit" class="btn btn-primary float-right"><i class="fa fa-send"></i> Kirim</button>
						<?= form_close("\n") ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	

	<script src="<?= base_url('assets/libs/jquery/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/libs/popper/popper.min.js') ?>"></script>
	<script src="<?= base_url('assets/libs/sweetalert/sweetalert.min.js') ?>"></script>
	<script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.min.js') ?>"></script>

	<?php if($this->session->userdata('role') == 'admin'){ ?>
	<script src="<?= base_url('assets/libs/select2/js/select2.min.js') ?>"></script>
	<script src="<?= base_url('assets/libs/select2/js/i18n/id.js') ?>"></script>
	<?php } ?>
	
	<script src="<?= base_url('assets/libs/ckeditor/ckeditor.js') ?>"></script>
	<script src="<?= base_url('assets/libs/ckeditor/adapters/jquery.js') ?>"></script>
	<script src="<?= base_url('assets/js/main.js') ?>"></script>
	
	<?php if($this->session->userdata('role') == 'admin'){ ?>
	<script>
		$(document).ready(function() {
			$('select').select2({
				ajax: {
					url: '<?= base_url('admin/mail/dest') ?>',
					dataType: 'json',
					delay: 500,
					method: 'post',
					data: function(params){
						var query = {
							search: params.term
						};

						return query;
					},
					processResults: function(data){
						return {
							results: data
						};
					}
				}
			});
		});
	</script>
	<?php } ?>
</body>
</html>