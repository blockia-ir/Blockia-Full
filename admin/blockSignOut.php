<?php
ob_start();
session_start();
session_destroy();
if(!empty($_GET['exit']))
{
	setcookie("nameUser","",time()-1000,"/");
	setcookie("sui","",time()-1000,"/");
}
// unset($_COOKIE['nameUser']);
// unset($_COOKIE['sui']);
header("location:login.php");
exit;
