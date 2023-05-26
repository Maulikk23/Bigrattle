<?php

echo $_COOKIE['user_id'];

// Access the value of the user_id cookie
$user_id = $_COOKIE['user_id'];

// Rest of your code
echo $user_id;
setcookie("token", "", time() - 3600);
session_destroy();

header("location:login.php")
?>