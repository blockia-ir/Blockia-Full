<?php ob_start();session_start();
require_once "../lib/php/autoload.php";

$DB=new DB();
$Msg=new Msg();
$Func=new Func();
$Encryption=new Encryption();
$valid=true;

if(empty($_SESSION['data']))
	exit("error: data not set");

$data=$Encryption->decode(strip_tags($_SESSION['data']));

if(empty($data))
	exit("error data not valid");


$data=explode(",",$data);
$block_user_id=$data[0];
$strtime=$data[1];

if(strtotime("now")>$strtime)
	exit("خطا: لینک بازیابی منقضی شده");

$captcha=strip_tags(strtolower($_POST['captcha']));
if($captcha!=$_SESSION['captcha']['code'])
{
    $Msg->error("کد امنیتی را به  صورت  صحیح وارد کنید");
    $valid=false;
}

$password=$Func->faNumToEn(strip_tags(@$_POST['password']));
if($password=="")
{
	$Msg->error("رمز عبور را وارد کنید");
    $valid=false;
}

if(!$valid)
{
	header("location:resetPasswordForm.php?data=".$_SESSION['data']);
	exit;
}


$block_user=$DB->query("SELECT * FROM {$DB->tablePrefix}block_users WHERE id=<bind>$block_user_id</bind>",true);

if(!isset($block_user->id))
{
	exit("خطا: کاربر مورد نظر یافت نشد");
}

if($password!="")
{
    $passwordHash=password_hash(($password),PASSWORD_BCRYPT,['cost'=>12]);
    $DB->update("{$DB->tablePrefix}block_users",['password'=>$passwordHash],
				" WHERE  id='{$block_user->id}' AND username='{$block_user->username}' " );
				
    $Msg->success("اطلاعات ورود: <br/> نام کاربری: {$block_user->username} <br/> رمز عبور: $password ");
}

header("location:login.php");
exit;
    
