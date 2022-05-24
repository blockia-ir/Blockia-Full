<?php require_once "blockPageLoader.php";

$id=$Encryption->decode(strip_tags(@$_POST['id']));
$name=strip_tags($_POST['name']);

if(empty($name))
{
    $Msg->error("نام کلاس را وارد کنید");
    header("location:classForm.php");
    exit;
}

$row=array();
$row['block_user_id']=$blockUserId;
$row['name']=$name;

if($id!="" && is_numeric($id))
{
    $DB->update("{$DB->tablePrefix}class",$row,"WHERE block_user_id='$blockUserId' AND id='$id' ");
    $Msg->success("نام کلاس ویرایش شد");
}
else
{
    $DB->insert("{$DB->tablePrefix}class",$row);
    $Msg->success("کلاس درج شد");
}

header("location:classForm.php");
exit;
