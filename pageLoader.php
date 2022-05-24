<?php ob_start();session_start();
require_once "lib/php/autoload.php";

$DB=new DB();
$Msg=new Msg();
$Func=new Func();
$Jdf=new Jdf();
$Encryption=new Encryption();

$date=date("Y-m-d H:i:s");

$DB->notExecute=array("drop","delete");

/*
$browserError="";
if(!isset($_COOKIE['PHPSESSID']))
{
	echo $browserError="
	<div class='alert alert-danger' style='position: relative;z-index: 9999999999;' >
	این  سایت  نمی تواند در این مرورگر به درستی کار کند. 
	 لطفا از مرورگر دیگری استفاده کنید.
	 توصیه می شود از مرورگر فایر فاکس استفاده کنید.
	</div>";
}
*/




