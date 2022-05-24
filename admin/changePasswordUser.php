<?php require_once "blockPageLoader.php";
if(!admin)
{
    exit("error: you can not access this page");
}

$id=$Encryption->decode(strip_tags(@$_POST['id']));
$password=$Func->faNumToEn(strip_tags(@$_POST['password']));

$valid=true;

if(!is_numeric($id))
{
    $Msg->error("error: id not valid");
    $valid=false;
}

if($password=="")
{
    $Msg->error("لطفا رمز عبور را وارد کنید");
    $valid=false;
}


if($valid==false)
{
    header("location:userForm.phpid={$Encryption->encode($id)}");
    exit;
}

$password=str_replace(['"',"'"],"",$password);

$row['password']=password_hash(($password),PASSWORD_BCRYPT,['cost'=>12]);
$DB->update("{$DB->tablePrefix}block_users",$row,"WHERE id='$id' ");
$Msg->success("رمز عبور به صورت $password تغییر کرد");
header("location:userForm.php?id={$Encryption->encode($id)}");
exit;

