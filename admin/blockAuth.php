<?php ob_start();session_start();
require_once "../lib/php/autoload.php";

$DB=new DB();
$Msg=new Msg();
$Func=new Func();
$Jdf=new Jdf();
$Encryption=new Encryption();
$date=date("Y-m-d H:i:s");

if(@$_SESSION['errorLogin']>=5)
	exit("
<center>
	<h1 style='color:red'> 
	خطا بیش از اندازه برای ورود تلاش کردید
	 <br/> 
	 مرورگر را به طور کامل بسته و 5 دقیقه دیگر وارد شوید
	</h1>
</center>
	");

/*
if($_SERVER['REQUEST_METHOD']==="GET"){
   $Msg->error("خطا: طریقه  ارسال داده  مجاز نبود");
   header("location:login.php");
   exit;
}



if(count($_POST)>=3){
    $Msg->error("خطا: تعداد موارد ارسالی به فایل احراز هویت مجاز نیست");
    header("location:login.php");
    exit;
}


foreach($_POST as $key=>$value){
    if($key!="username" && $key!="password" ){
        $Msg->error("خطا: اجازه  ارسال چنین داده ای را ندارید");
        header("location:login.php");
        exit;
    }
}
*/

if(strlen($_POST['username'])>40 || strlen($_POST['password']) >40){
    $Msg->error("خطا: تعداد کاراکترهای ارسالی بیش از حد مجاز بوده");
    header("location:login.php");
    exit;
}

if(strlen($_POST['username'])<1 || strlen($_POST['password'])<1){
    $Msg->error("خطا: لطفا هر دو فیلد را پر کنید");
    var_dump($_POST);
    exit;
    header("location:login.php");
    exit;
}

$username=$Func->faNumToEn(strtolower($_POST['username']));
$password=$Func->faNumToEn($_POST['password']);

$result=$DB->query("SELECT * FROM {$DB->tablePrefix}block_users WHERE username=<bind>$username</bind> LIMIT 1 ",true);
if(!isset($result->username)){
    $Msg->error("خطا: این نام کاربری  وجود ندارد");
	
	if(isset($_SESSION['errorLogin']))
		$_SESSION['errorLogin']=(int)$_SESSION['errorLogin']+1;
	else
		$_SESSION['errorLogin']=1;
	
    header("location:login.php");
    exit;
}

// if($countUser>1){
    // $Msg->error("خطا: با این نام کاربری بیش از یک مورد وجود دارد");
    // header("location:login.php");
    // exit;
// }


if(isset($result->username)){
    $login=password_verify($password,$result->password);
    if($login){
        $_SESSION['block_user_id']=$Encryption->encode($result->id);
		unset($_SESSION['errorLogin']);
        $nameUser=$result->name;
        if($result->admin!="")
        {
            $_SESSION['admin']=$result->admin;
        }
        setcookie("nameUser",$nameUser,time()+(365*24*60*60),"/");
        //setcookie("sui",$_SESSION['block_user_id'],time()+(365*24*60*60),"/");
		echo "insert login";
        $DB->insert("{$DB->tablePrefix}logins",["block_user_id"=>$result->id,"ip"=>$Func->getIP(),"os"=>$Func->getOS(),"browser"=>$Func->getBrowser(),"date"=>$date]);
        header("location:index.php");
        exit;
    }else{
        $Msg->error("خطا: رمز عبور را  اشتباه وارد کرده اید");
		
		if(isset($_SESSION['errorLogin']))
			$_SESSION['errorLogin']=(int)$_SESSION['errorLogin']+1;
		else
			$_SESSION['errorLogin']=1;
		
        header("location:login.php");
        exit;
    }
    
}else
{
	exit("<h3>نام کاربری مطابقت داده نشد</h3>");
	
}
    
    
    


