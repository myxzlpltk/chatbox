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
					<div class="card-body">
						<table class="table table-striped table-hover dataTable w-100" id="mail-table" data-ajax="<?= $data ?>">
							<thead class="d-none">
								<tr>
									<th>Mail List</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
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