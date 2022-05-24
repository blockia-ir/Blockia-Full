<?php require_once "pageLoader.php";

$username=$Encryption->decode(strip_tags(@$_POST['username']));
$class_id=$Encryption->decode(strip_tags(@$_POST['class_id']));
$name=addslashes(strip_tags(@$_POST['name']));
$code=$Func->faNumToEn(strip_tags(@$_POST['code']));
$captcha=strip_tags(strtolower($_POST['captcha']));

$valid=true;

if($username=="")
{
  $Msg->error("اطلاعات ارسالی ناقص است");
  $valid=false;  
}

if($name=="")
{
    $Msg->error("لطفا نام را وارد کنید");
    $valid=false;
}


if(!is_numeric($class_id))
{
    $Msg->error("اطلاعات کلاس نا معتبر است");
    $valid=false;
}

if(!is_numeric($code))
{
    $Msg->error("کد  ورود به طور صحیح وارد کنید");
    $valid=false;
}

if($captcha!=$_SESSION['captcha']['code'])
{
    $Msg->error("کد امنیتی را به  صورت  صحیح وارد کنید");
    $valid=false;
}

if($valid==false)
{
    header("location:ru.php?u={$Encryption->encode($username)}");
    exit;
}

$block_user=$DB->query("SELECT id,class_link_register,description_link_register FROM {$DB->tablePrefix}block_users
                     WHERE username=<bind>$username</bind> AND active_link_register=1",true);

if(!isset($block_user->id))
{
    exit("خطا لینک ثبت دانش آموز یافت نشد و یا غیر فعال شده");
}
   
    
$rowClassStudent['block_user_id']=$block_user->id;
$rowClassStudent['name']=$name;
$rowClassStudent['class_id']=$class_id;
$rowClassStudent['code']=$code;

   
$student=$DB->query("SELECT * FROM {$DB->tablePrefix}class_student  WHERE block_user_id='{$block_user->id}' AND code=<bind>$code</bind> ",true);
if(isset($student->id))
{
    $Msg->error("کد $code از قبل ثبت شده است");
    header("location:ru.php?u={$Encryption->encode($username)}");
    exit;
}

$result=$DB->insert("{$DB->tablePrefix}class_student",$rowClassStudent);
if($result)
{
    $id=$DB->lastInsertId();
    $Msg->success("اطلاعات شما با موفقیت ثبت شد");
    setcookie("registerStudent",$Encryption->encode($id),time()+(30*24*60*60),"/");
    header("location:resultRegister.php?id={$Encryption->encode($id)}");
    exit;
}
else
{
    $Msg->error("خطای رخ داده. اطلاعات ثبت نشد");
    header("location:ru.php?u={$Encryption->encode($username)}");
    exit; 
    
}



