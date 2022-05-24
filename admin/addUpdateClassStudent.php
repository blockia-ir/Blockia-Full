<?php require_once "blockPageLoader.php";

$id=$Encryption->decode(strip_tags(@$_POST['id']));
$name=strip_tags($_POST['name']);
$code=$Func->faNumToEn(strip_tags($_POST['code']));
$class_id=strip_tags($_POST['class_id']);
$date=date("Y-m-d H:i:s");
$valid=true;

if($name=="")
{
    $Msg->error("لطفا نام دانش آموز را وارد کنید");
    $valid=false;
}

if($class_id=="")
{
    $Msg->error("لطفا کلاس را انتخاب کنید");
    $valid=false;
}

if($code=="")
{
    $Msg->error("لطفا کد ورود به آزمون را وارد کنید");
    $valid=false;
}

if($valid==false)
{
    if($id!="" && is_numeric($id))
    {
        header("location:class_studentForm.php?id={$Encryption->encode($id)}");
    }
    else
    {
        header("location:class_studentForm.php");
    }
   
    exit;
           
}

$rowClassStudent['block_user_id']=$blockUserId;
$rowClassStudent['name']=$name;
$rowClassStudent['class_id']=$class_id;
$rowClassStudent['code']=$code;



if($id!="" && is_numeric($id))  //update class_student
{
    
    $student=$DB->query("SELECT * FROM {$DB->tablePrefix}class_student  WHERE block_user_id='$blockUserId' AND code=<bind>$code</bind> AND id!='$id' ",true);
    if(isset($student->id))
    {
        $Msg->error("کد $code برای دانش آموز {$student->name} از قبل ثبت شده است");
        header("location:class_studentForm.php?id={$Encryption->encode($id)}");
        exit;
    }
    
    $result=$DB->update("{$DB->tablePrefix}class_student",$rowClassStudent,"  WHERE block_user_id='$blockUserId' AND id='$id' ");
    if($result)
    {
        $Msg->success("دانش آموز با موفقیت ویرایش شد");
        header("location:class_studentForm.php?id={$Encryption->encode($id)}");
        exit;
    }else
    {
        //var_dump($result);
        $Msg->show();
    }

}
else  //insert class_student
{ 
   
    $student=$DB->query("SELECT * FROM {$DB->tablePrefix}class_student  WHERE block_user_id='$blockUserId' AND code=<bind>$code</bind> ",true);
    if(isset($student->id))
    {
        $Msg->error("کد $code برای دانش آموز {$student->name} از قبل ثبت شده است");
        header("location:class_studentForm.php");
        exit;
    }
    
    $result=$DB->insert("{$DB->tablePrefix}class_student",$rowClassStudent);
    if($result)
    {
        $id=$DB->lastInsertId();
        $Msg->success("دانش آموز با موفقیت درج شد");
        header("location:class_studentForm.php?id={$Encryption->encode($id)}");
        exit;
    }
    
}
