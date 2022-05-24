<?php require_once "blockPageLoader.php";
if(!admin)
{
    exit("error: you can not access this page");
}

$user_id=$Encryption->decode(strip_tags($_GET['id']));

if(!is_numeric($user_id))
{
    exit("error: user id not valid");
}

$result=$DB->prepare("DELETE FROM {$DB->tablePrefix}block_users WHERE id=<bind>$user_id</bind>");
if($result)
{
    $Msg->success("کاربر حذف شد");
    header("location:userPage.php");
    exit;
}
else
{
    $Msg->error("خطای رخ داده کاربر حذف نشد");
    header("location:userPage.php");
    exit; 
}
