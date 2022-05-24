<?php ob_start();session_start();
require_once "../php/autoload.php";

$Encryption=new Encryption();

if(empty($_SESSION['block_user_id']))
{
	//header("location:../../admin/blockSignOut.php");
	exit("error: Access Denied");
}

$block_user_id=$Encryption->decode($_SESSION['block_user_id']);

if(!empty($_SESSION['admin']))
{
	if($block_user_id!=$Encryption->decodeAdmin($_SESSION['admin']))
	{
		//header("location:../../admin/blockSignOut.php");
		exit("error: Access Denied");
	}
}
else
{
	//header("location:../../admin/blockSignOut.php");
	exit("error: Access Denied");
}
