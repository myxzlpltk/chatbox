<?php

require "config.inc.php";
require "database.inc.php";
require "input.inc.php";

$cfg = new Config();
$db = new Database();
$input = new Input();

require "helper.inc.php";

// CLEAR ALL

unset($__config);

// SET DATE
date_default_timezone_set('Asia/Jakarta');