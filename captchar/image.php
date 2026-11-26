<?php

session_start();

require_once "captchar.php";

$captchar = new Captchar();

$code = $captchar->get_and_show_image();
 
// Lưu code session
$_SESSION['captcha_code'] = $code;

