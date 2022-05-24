<?php ob_start();session_start();
require_once "../lib/php/autoload.php";
$_setting=parse_ini_file("settingAdmin.ini");
if(@$_setting['register_user']!=false)
{
    exit("خطا: امکان ثبت نام کاربر وجود ندارد");
}

$DB=new DB();
$Msg=new Msg();
$Func=new Func();
$Encryption=new Encryption();

$name=addslashes(strip_tags(@$_POST['name']));
$email=addslashes(strip_tags(@$_POST['email']));
$username=$Func->faNumToEn(strtolower( strip_tags(@$_POST['username'])));
$password=$Func->faNumToEn(strip_tags(@$_POST['password']));
$captcha=strip_tags(strtolower($_POST['captcha']));

setcookie("name",$name,0,"/");
setcookie("username",$username,0,"/");
setcookie("email",$email,0,"/");

$valid=true;

if($name=="")
{
    $Msg->error("لطفا نام را وارد کنید");
    $valid=false;
}


if($username=="")
{
    $Msg->error("لطفا نام کاربری را وارد کنید");
    $valid=false;
}

if($password=="")
{
    $Msg->error("لطفا رمز عبور را وارد کنید");
    $valid=false;
}

if($captcha!=$_SESSION['captcha']['code'])
{
    $Msg->error("کد امنیتی را به  صورت  صحیح وارد کنید");
    $valid=false;
}

if($email!="" && !filter_var($email, FILTER_VALIDATE_EMAIL))
{
	$Msg->error("ساختار ایمیل وارد شده صحیح نیست");
	$valid=false;
}

if($valid==false)
{
    header("location:registerForm.php");
    exit;
}


$username=str_replace(['"',"'"],"",$username);
$password=str_replace(['"',"'"],"",$password);


$block_user=$DB->query(" SELECT * FROM {$DB->tablePrefix}block_users WHERE username=<bind>$username</bind> ",true);
if(@$block_user->username==$username)
{
    setcookie("username","",0,"/");
    $Msg->error("نام کاربری $username از قبل ثبت شده است نام کاربری دیگری انتخاب کنید");
    header("location:registerForm.php");
    exit; 
}

if($email!="")
{
	$block_user=$DB->query(" SELECT * FROM {$DB->tablePrefix}block_users WHERE email=<bind>$email</bind> ",true);
	if(@$block_user->email==$email)
	{
		setcookie("email","",0,"/");
		$Msg->error("ایمیل  $email از قبل ثبت شده است ");
		header("location:registerForm.php");
		exit; 
	}
}
else
{
	$email=null;
}


$row['name']=$name;
$row['email']=$email;
$row['username']=$username;
$row['password']=password_hash(($password),PASSWORD_BCRYPT,['cost'=>12]);

if($DB->insert("{$DB->tablePrefix}block_users",$row))
{
    $blockUserId=$DB->lastInsertId();
    $Msg->info ("خوش آمدید <br/>
                  اطلاعات  شما با موفقیت ثبت  شد <br/>
                  از این پس می توانید با اطلاعات زیر وارد نرم  افزار شوید <br/>
                  حتما اطلاعات زیر را ذخیره کنید چراکه رمز عبور به هیچ وجه قابل بازیابی نیست <br/>
                  نام کاربری: $username <br/>
                  رمز عبور: $password");
				  
    $_SESSION['block_user_id']=$Encryption->encode($blockUserId);

    setcookie("nameUser",$name,time()+(365*24*60*60),"/");
    setcookie("sui",$_SESSION['block_user_id'],time()+(365*24*60*60),"/");
    header("location:index.php");
    exit;
}
else
{
    $Msg->error("خطای رخ داده  لطفا دوباره  تلاش کنید");
    header("location:registerForm.php");
    exit;
    
}
