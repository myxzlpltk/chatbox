<?php

require "config.inc.php";
require "database.inc.php";
require "input.inc.php";
require "cookie.inc.php";

$cfg = new Config();
$db = new Database();
$input = new Input();
$cookie = new Cookie();

require "helper.inc.php";

// CLEAR ALL

unset($__config);

// SET DATE
date_default_timezone_set('Asia/Jakarta');

if(!true){
	header('HTTP/1.0 404 Not Found', 404, true);
	exit();
}

$_limit = 10;