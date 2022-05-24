<?php require_once "blockPageLoader.php";

$class_id=$Encryption->decode(strip_tags($_GET['class_id']));
$i_sure=strip_tags($_GET['i_sure']);

if(!is_numeric($class_id))
{
    exit("error: class student id not valid");
}

if($i_sure!="1")
{
    exit("error: your not sure !"); 
}

$result=$DB->prepare("DELETE FROM {$DB->tablePrefix}class_student WHERE 
                     block_user_id=<bind>$block_user_id</bind>
                     AND class_id=<bind>$class_id</bind> ");
if($result)
{
    $Msg->success("دانش آموزان کلاس حذف شدند");
}
else
{
    $Msg->error("خطای رخ داده");
}

header("location:class_studentPage.php");
exit; 
