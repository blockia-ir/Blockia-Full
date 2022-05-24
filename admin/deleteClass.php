<?php require_once "blockPageLoader.php";

$id=$Encryption->decode(strip_tags($_GET['id']));

if(!is_numeric($id))
{
    exit("error: id not valid");
}

$result=$DB->prepare("DELETE FROM {$DB->tablePrefix}class WHERE block_user_id='$blockUserId' AND id='$id' ");
if($result)
{
    $Msg->success("کلاس حذف شد");
    // delete student
    $DB->prepare("DELETE FROM {$DB->tablePrefix}class_student WHERE block_user_id='$blockUserId' AND class_id='$id' ");
    // delete exam class
    $DB->prepare("DELETE FROM `{$DB->tablePrefix}exam_class` WHERE block_user_id='$blockUserId' AND class_id='$id' ");
}

header("location:classForm.php");
exit;
