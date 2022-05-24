<?php require_once "blockPageLoader.php";
if(!admin)
{
    exit("error: you can not access this page");
}

$exam_name=strip_tags(addslashes($_POST['exam_name']));
$register_user=strip_tags($_POST['register_user']);
$number_exam_hour=strip_tags($_POST['number_exam_hour']);
$valid=true;

if(empty($exam_name))
{
    $Msg->error("نام نباید خالی باشد");
    $valid=false;
}

if(strlen($exam_name)>100)
{
    $Msg->error("حداکثر اندازه نام  باید 100 کاراکتر باشد");
    $valid=false;
}

if($register_user!="false" && $register_user!="true")
{
  $Msg->error("وضعیت ثبت کاربر نامعتبر است");
  exit;
}

if(!is_numeric($number_exam_hour))
{
    $Msg->error("تعداد ثبت آزمون در ساعت باید عدد باشد");
    $valid=false;
}

if($valid==false)
{
    header("location:settingAdminForm.php");
    exit;
}

$setting="[setting]\r\n";
$setting.="exam_name='$exam_name'\r\n";
$setting.="register_user=$register_user\r\n";
$setting.="number_exam_hour=$number_exam_hour\r\n";

$result=file_put_contents("settingAdmin.ini",$setting);
if($result)
{
    chmod("settingAdmin.ini", 0644);
    $Msg->success("تنظیمات با موفقیت ثبت شد");
}
else
{
    $Msg->error("خطای رخ داده");
}
header("location:settingAdminForm.php");
exit;

