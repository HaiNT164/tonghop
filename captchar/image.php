<?php

session_start();

require_once "captchar.php";

$captcha = new Captcha();

$code = $captcha->get_and_show_image();
 
// Lưu code session
$_SESSION['captcha_code'] = $code;

