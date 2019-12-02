<?php
session_start();

session_unset();
setcookie ("user_login", "");

header("Location: index.php");
die();
