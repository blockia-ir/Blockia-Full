<?php ob_start();session_start();
require_once "../lib/php/autoload.php";

$DB=new DB();
$Msg=new Msg();
$Func=new Func();
$Jdf=new Jdf();
$Encryption=new Encryption();
$date=date("Y-m-d H:i:s");



if(@$_SESSION['errorLogin'] >= 5){
    exit("
<center>
	<h1 style='color:red'>
	خطا بیش از اندازه برای ورود تلاش کردید
	 <br/>
	 مرورگر را به طور کامل بسته و 5 دقیقه دیگر وارد شوید
	</h1>
</center>
	");
}


if(!isset($_POST['username'],$_POST['password'])){
    $Msg->error("خطا: لطفا هر دو فیلد را پر کنید");
    header("location:login.php");
    exit;
}elseif(strlen($_POST['username'])>40 || strlen($_POST['password']) >40){
    $Msg->error("خطا: تعداد کاراکترهای ارسالی بیش از حد مجاز بوده");
    header("location:login.php");
    exit;
}


$username=$Func->faNumToEn(strtolower($_POST['username']));
$password=$Func->faNumToEn($_POST['password']);

$result=$DB->query("SELECT * FROM {$DB->tablePrefix}block_users WHERE username=<bind>$username</bind> LIMIT 1 ",true);

if(isset($result->username)){
    $login=password_verify($password,$result->password);
    if($login){
        $_SESSION['block_user_id']=$Encryption->encode($result->id);
        $nameUser=$result->name;
        if($result->admin!="")
        {
            $_SESSION['admin']=$result->admin;
        }
        setcookie("nameUser",$nameUser,time()+(365*24*60*60),"/");
        $DB->insert("{$DB->tablePrefix}logins",["block_user_id"=>$result->id,"ip"=>$Func->getIP(),"os"=>$Func->getOS(),"browser"=>$Func->getBrowser(),"date"=>$date]);

        unset($_SESSION['errorLogin']);
        header("location:index.php");
        exit;
    }else{
        $Msg->error("خطا: نام کاربری یا رمز عبور اشتباه میباشد.");
		
		if(!isset($_SESSION['errorLogin'])){
            $_SESSION['errorLogin']=1;
        }

        ++$_SESSION['errorLogin'];
        header("location:login.php");
        exit;
    }
    
}else {
    $Msg->error("خطا: نام کاربری یا رمز عبور اشتباه میباشد.");
    if(!isset($_SESSION['errorLogin'])){
        $_SESSION['errorLogin']=1;
    }

    ++$_SESSION['errorLogin'];
    header("location:login.php");

    exit;
}
