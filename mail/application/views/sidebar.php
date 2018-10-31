<?php if(!isset($menu)) $menu = 0 ?>
<?php if($this->session->userdata('role') == 'admin'){ ?>

<div class="col-md-3">
	<div class="card card-clean">
		<div class="card-header">
			<a href="<?= base_url('admin/mail/compose') ?>" title="Compose Mail" class="btn btn-success btn-block btn-sm">Compose Mail</a>
		</div>
		<div class="card-body p-0">
			<p class="my-2 mx-3">Folders</p>
			<div class="list-group list-group-flush">
				<a href="<?= base_url('admin/mail') ?>" class="list-group-item <?php if($menu==1) echo "active" ?>">
					<i class="fa fa-inbox"></i>
					<span>Inbox</span>
				</a>
				<a href="<?= base_url('admin/mail/sent') ?>" class="list-group-item <?php if($menu==2) echo "active" ?>">
					<i class="fa fa-send-o"></i>
					<span>Sent</span>
				</a>
				<a href="<?= base_url('admin/mail/trash') ?>" class="list-group-item <?php if($menu==3) echo "active" ?>">
					<i class="fa fa-trash"></i>
					<span>Trash</span>
				</a>
			</div>
		</div>
	</div>

	<div class="card card-clean mt-3">
		<div class="card-body">
			<h3 class="text-center"><i class="fa fa-user fa-2x"></i></h3>
			<p class="lead text-center text-secondary"><?= html_escape($this->session->userdata('nama')) ?></p>
			<p class="m-0 text-center">
				<a href="<?= base_url('logout') ?>" title="Logout" class="btn btn-danger btn-sm"><i class="fa fa-sign-out"></i> Logout</a>
			</p>
		</div>
	</div>
</div>

<?php } else{ ?>

<div class="col-md-3">
	<div class="card card-clean">
		<div class="card-header">
			<a href="<?= base_url('mail/compose') ?>" title="Compose Mail" class="btn btn-success btn-block btn-sm">Compose Mail</a>
		</div>
		<div class="card-body p-0">
			<p class="my-2 mx-3">Folders</p>
			<div class="list-group list-group-flush">
				<a href="<?= base_url('mail') ?>" class="list-group-item <?php if($menu==1) echo "active" ?>">
					<i class="fa fa-inbox"></i>
					<span>Inbox</span>
				</a>
				<a href="<?= base_url('mail/sent') ?>" class="list-group-item <?php if($menu==2) echo "active" ?>">
					<i class="fa fa-send-o"></i>
					<span>Sent</span>
				</a>
				<a href="<?= base_url('mail/trash') ?>" class="list-group-item <?php if($menu==3) echo "active" ?>">
					<i class="fa fa-trash"></i>
					<span>Trash</span>
				</a>
			</div>
		</div>
	</div>

	<div class="card card-clean mt-3">
		<div class="card-body">
			<h3 class="text-center"><i class="fa fa-user fa-2x"></i></h3>
			<p class="lead text-center text-secondary"><?= html_escape($this->session->userdata('nama')) ?></p>
			<p class="m-0 text-center">
				<a href="<?= base_url('logout') ?>" title="Logout" class="btn btn-danger btn-sm"><i class="fa fa-sign-out"></i> Logout</a>
			</p>
		</div>
	</div>
</div>

<?php } ?>