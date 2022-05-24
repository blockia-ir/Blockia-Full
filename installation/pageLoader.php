<?php ob_start();session_start();
require_once "../lib/php/autoload.php";
require_once "../lib/php/config.php";


if(@INSTALL_DATE!="" && defined("INSTALL_DATE"))
    exit("error:  blockia exam is installed !");
    
//$DB=new DB();
$Msg=new Msg();
$Func=new Func();
$Jdf=new Jdf();
$Encryption=new Encryption();

$date=date("Y-m-d H:i:s");





