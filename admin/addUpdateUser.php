<?php require_once "blockPageLoader.php";
if(!admin)
{
    exit("error: you can not access this page");
}

$id=$Encryption->decode(strip_tags(@$_POST['id']));
$name=addslashes(strip_tags(@$_POST['name']));
$username=$Func->faNumToEn(strtolower(strip_tags(@$_POST['username'])));
$email=addslashes(strip_tags(@$_POST['email']));
$password=$Func->faNumToEn(strip_tags(@$_POST['password']));
$valid=true;

$email=empty($email) ? null:$email;



if($id!="" && !is_numeric($id))
{
    $Msg->error("error: id not valid");
    $valid=false;
}

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

if($password=="" && $id=="")
{
    $Msg->error("لطفا رمز عبور را وارد کنید");
    $valid=false;
}

if($email!=null && !filter_var($email, FILTER_VALIDATE_EMAIL) ) 
{
    $Msg->error("ساختار ایمیل وارد شده صحیح نیست");
    $valid=false;
}

if($valid==false)
{
    header("location:userForm.php");
    exit;
}


$username=str_replace(['"',"'"],"",$username);
$password=str_replace(['"',"'"],"",$password);

// check username exist
if($id!="")
{
    $block_user=$DB->query("SELECT * FROM `{$DB->tablePrefix}block_users` WHERE username=<bind>$username</bind> AND id<><bind>$id</bind>",true);
}
else
{
    $block_user=$DB->query(" SELECT * FROM {$DB->tablePrefix}block_users WHERE username=<bind>$username</bind> ",true);
}


if(@$block_user->username==$username)
{
    $Msg->error("نام کاربری $username از قبل ثبت شده است نام کاربری دیگری انتخاب کنید");
    header("location:userForm.php?id=".$Encryption->encode($id));
    exit; 
}

unset($block_user);

//cehck email exist
if($id!="" && !empty($email))
{
    $block_user=$DB->query("SELECT * FROM `{$DB->tablePrefix}block_users` WHERE email=<bind>$email</bind> AND id<><bind>$id</bind>",true);
}
elseif(!empty($email))
{
    $block_user=$DB->query(" SELECT * FROM {$DB->tablePrefix}block_users WHERE email=<bind>$email</bind> ",true);
}


if(isset($block_user->email))
{
    $Msg->error("ایمیل  $email از قبل ثبت شده است ");
    header("location:userForm.php?id=".$Encryption->encode($id));
    exit; 
}

if($id!="" && is_numeric($id))
{
    $row['name']=$name;
    $row['username']=$username;
    $row['email']=$email;
    $DB->update("{$DB->tablePrefix}block_users",$row," WHERE id='$id' ");
    $Msg->success("کاربر ویرایش شد");
    header("location:userForm.php?id={$Encryption->encode($id)}");
    exit;
}
else
{
    $row['name']=$name;
    $row['username']=$username;
    $row['email']=$email;
    $row['password']=password_hash(($password),PASSWORD_BCRYPT,['cost'=>12]);
    $DB->insert("{$DB->tablePrefix}block_users",$row);
    $user_id=$DB->lastInsertId();
    $Msg->success("کاربر درج شد");
    header("location:userForm.php?id={$Encryption->encode($user_id)}");
    exit;
}
