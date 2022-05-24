<?php require_once "blockPageLoader.php";

$result=false;
$msg=array("status"=>"0","text"=>"");


$ids=$_POST['ids'];
$exam_id=strip_tags($_POST['exam_id']);

$exam=$DB->query("SELECT * FROM {$DB->tablePrefix}exam where block_user_id='$block_user_id' AND id=<bind>$exam_id</bind> ",true);
if(!isset($exam->id))
{
	exit("error you can not access this exam");
}

if(!is_array($ids))
{
    $msg['text']='ids not set';
    exit(json_encode($msg));
}

$ids=implode(",",$ids);
$sql="
SET @ord=-1;
UPDATE {$DB->tablePrefix}exam_question SET ordr=(@ord:=@ord+1)
WHERE block_user_id='$block_user_id' AND exam_id='$exam_id'
ORDER BY FIELD(id,$ids)";

$result=$DB->prepare($sql);
if($result){
	$DB->update("{$DB->tablePrefix}exam",['show_question_type'=>'1'],"where id='$exam_id' AND block_user_id='$block_user_id' ");
    $msg['text']='<span class="text-success" >تغییر چیدمان با موفقیت انجام شد</span>';
    $msg['status']=1;
    exit(json_encode($msg));
}else{
    $msg['text']='<span class="text-danger" >خطای رخ داده دوباره تلاش کنید</span>';
    exit(json_encode($msg));
}

