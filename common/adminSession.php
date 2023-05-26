<?php

include_once 'constant.php';

session_start();
if(!(isset($_SESSION['session_nnmp_admin']) && $_SESSION['session_nnmp_admin']!="")){
	header("Location:../QwertyAsdf/signin.php");
	exit(0);
}




?>
