<?php require_once "blockPageLoader.php";

$active_link_register=strip_tags($_POST['active_link_register']);
$class_link_register=($_POST['class_link_register']);
$description_link_register=addslashes(strip_tags($_POST['description_link_register']));

if(!is_numeric($active_link_register))
{
    exit("error: active_link_register not valid");
}

if(!is_array($class_link_register) && $class_link_register!=""  )
{
    exit("error: class_link_register not valid");
}

$row['active_link_register']=$active_link_register;
$row['class_link_register']=@implode(",",$class_link_register);
$row['description_link_register']=$description_link_register;

$result=$DB->update($tbl_block_users,$row,"WHERE $tbl_block_users.id='$block_user_id' ");
if($result)
{
    $Msg->success("تغییرات  ثبت شد");
}
else
{
    $Msg->error("خطای رخ داده");
}
header("location:link_register_student.php");
exit;
