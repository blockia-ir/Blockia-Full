<?php ob_start();session_start();
include_once "../../../../lib/php/DB.php";
include_once "../../../../lib/php/Encryption.php";

if(empty($_SESSION['block_user_id']))
{
	exit("error you must login ");
}

$DB=new DB();
$Encryption=new Encryption();


$block_user_id=$blockUserId=$blockUserId=$Encryption->decode($_SESSION['block_user_id']);
define("block_user_id",$block_user_id);

if(!is_numeric($block_user_id))
	exit("error: user id is not valid");



