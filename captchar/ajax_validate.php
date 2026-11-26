<?php

session_start();


// Lấy thông tin
$content = isset($_POST['content']) ? $_POST['content'] : false;
$captcha = isset($_POST['captcha']) ? $_POST['captcha'] : false;

// Biến lưu kết quả trả về
$error = array(
    'error'     => 'success',
    'content'   => '',
    'captcha'   => ''
);

// Kiểm tra content
if (!$content){
    $error['content'] = 'Bạn chưa nhập nội dung';
    $error['error'] = 'error';
}

// Kiểm tra captchar
if (!$captcha) {
    $error['captcha']='Bạn chưa nhập nội dung captcha';
    $error['error']='error';
}
else if (!isset($_SESSION['captcha_code']) || $_SESSION['captcha_code']!=trim($captcha)) {
    $error['captcha']='Captcha bạn nhập chưa đúng';
    $error['error']='error';
}

die(json_encode($error));



