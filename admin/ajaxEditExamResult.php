<?php require_once "blockPageLoader.php";

$exam_result_id=strip_tags($_POST['exam_result_id']);
$student_name=addslashes(strip_tags($_POST['student_name']));
$mark=strip_tags($_POST['mark']);
$count_true=strip_tags($_POST['count_true']);
$count_false=strip_tags($_POST['count_false']);
$count_empty=strip_tags($_POST['count_empty']);
$percent=strip_tags($_POST['percent']);

if($exam_result_id=="" )
{
	echo ("<span class='text-danger' >شماره برگه خالی است</span>");
	exit;
}

if(!is_numeric($exam_result_id))
{
	echo ("<span class='text-danger' >شماره برگه نامتعبر است</span>");
	exit;
}

if($student_name=="")
{
	echo ("<span class='text-danger' >نام را وارد کنید</span>");
	exit;
}

if($mark=="" || $mark<0 || $mark>20 || !is_numeric($mark) )
{
	echo ("<span class='text-danger' >لطفا نمره را به  طور صحیح وارد کنید</span>");
	exit;
}

if($count_true=="" || $count_true<0 || !is_numeric($count_true) )
{
	echo ("<span class='text-danger' >لطفا تعداد درست را به طور صحیح وارد کنید</span>");
	exit;
}

if($count_false=="" || $count_false<0 || !is_numeric($count_false) )
{
	echo ("<span class='text-danger' >لطفا تعداد غلط را به طور صحیح وارد کنید</span>");
	exit;
}

if($count_empty=="" || $count_empty<0 || !is_numeric($count_empty) )
{
	echo ("<span class='text-danger' >لطفا تعداد بی پاسخ را به طور صحیح وارد کنید</span>");
	exit;
}

if($percent=="" || $percent<0 || $percent>100 || !is_numeric($percent) )
{
	echo ("<span class='text-danger' >لطفا درصد را به طور صحیح وارد کنید</span>");
	exit;
}

$row['student_name']=$student_name;	
$row['mark']=$mark;	
$row['count_true']=$count_true;	
$row['count_false']=$count_false;	
$row['count_empty']=$count_empty;	
$row['percent']=$percent;	

$result=$DB->update("{$DB->tablePrefix}exam_result",$row,"
					WHERE {$DB->tablePrefix}exam_result.id='$exam_result_id'
					AND {$DB->tablePrefix}exam_result.block_user_id='$block_user_id' ");
if($result)
{
	
	echo "true";
}
else
{
	echo "false";
}




