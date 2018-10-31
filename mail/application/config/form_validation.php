<?php

$config = array(
	'login' => array(
		array(
			'field' => 'username',
			'label' => 'Username',
			'rules' => 'required'
		),
		array(
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'required'
		)
	),
	'compose' => array(
		array(
			'field' => 'title',
			'label' => 'Judul',
			'rules' => 'required'
		),
		array(
			'field' => 'content',
			'label' => 'Konten',
			'rules' => 'required'
		)
	)
);

$config['error_prefix'] = '<div class="alert alert-danger"><span class="close" data-dismiss="alert">&times;</span>';
$config['error_suffix'] = '</div>';