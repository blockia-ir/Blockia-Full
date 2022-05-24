<?php ob_start();session_start();
$time_start = microtime(true);//get time start run script
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require_once "../lib/php/autoload.php";

if(empty($_SESSION['block_user_id']))
{
	exit("خطا:  ابتدا باید وارد پنل کاربری بشوید");
}

$DB=new DB();
$Msg=new Msg();
$Func=new Func();
$Jdf=new Jdf();
$Encryption=new Encryption();


$block_user_id=$blockUserId=$blockUserId=$Encryption->decode($_SESSION['block_user_id']);
define("block_user_id",$block_user_id);

if(!is_numeric($block_user_id))
	exit("error: user id is not valid");


$date=date("Y-m-d H:i:s");

