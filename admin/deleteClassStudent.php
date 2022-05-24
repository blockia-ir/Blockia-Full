<?php require_once "blockPageLoader.php";

$class_student_id=$Encryption->decode(strip_tags($_GET['id']));

if(!is_numeric($class_student_id))
{
    exit("error class student id not valid");
}

$result=$DB->prepare("DELETE FROM {$DB->tablePrefix}class_student WHERE 
                     block_user_id=<bind>$block_user_id</bind>
                     AND id=<bind>$class_student_id</bind> ");
if($result)
{
    $Msg->success("دانش آموز حذف شد");
    header("location:class_studentPage.php");
    exit;
}
else
{
    $Msg->error("خطای رخ داده دانش آموز حذف نشد");
    header("location:class_studentPage.php");
    exit; 
}
