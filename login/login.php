<?php
session_start();

const EXPIRE_DAYS = 90;

$user = $_POST['username'];
$password = $_POST['password'];


if(!$user || !$password) {
    echo 'Username and Password Required';
    exit;
}

// process login and get user id
$logged = true;
$user_id = "4";

if(!$logged) {
    echo 'Invalid username or password';
    exit;
}

// store in session
$_SESSION["user_id"] = $user_id;

if(!empty($_POST["remember"]) && $_POST["remember"] == 'on') {
    setcookie ("user_login",$user_id,time()+ (EXPIRE_DAYS * 24 * 60 * 60));
} else {
    if(isset($_COOKIE["user_login"])) {
        setcookie ("user_login","");
    }
}

header("Location: index.php");
die();
