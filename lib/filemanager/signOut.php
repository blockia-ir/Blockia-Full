<?php session_start();
session_destroy();
setcookie("nameUser","",time()-(60),"/");
setcookie("username","",time()-(60*60),"/");
setcookie("password","",time()-(60*60),"/");
header("location:../../admin/blockSingOut.php?exit=1");
exit;
