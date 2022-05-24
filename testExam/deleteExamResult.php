<?php require_once "pageLoader.php";

$id=$Encryption->decode(strip_tags($_GET['id']));

if(!is_numeric($id))
    exit("error: id must be number");

    

$exam_result=$DB->query("SELECT * FROM `{$DB->tablePrefix}exam_result`
                        WHERE  block_user_id='$block_user_id' AND id='$id' ",true);
if(isset($exam_result->id))
{
    // delete student answer
    $result=$DB->prepare("
                DELETE {$DB->tablePrefix}student_answer FROM {$DB->tablePrefix}student_answer
                INNER JOIN {$DB->tablePrefix}exam_result on
                {$DB->tablePrefix}student_answer.exam_result_id={$DB->tablePrefix}exam_result.id AND
				{$DB->tablePrefix}student_answer.exam_result_id='$id' 
                WHERE {$DB->tablePrefix}exam_result.id='$id' AND
                {$DB->tablePrefix}student_answer.exam_result_id='$id' AND
                {$DB->tablePrefix}exam_result.block_user_id='$block_user_id' ");
	if($result)
	{
        // delete exam result
        $DB->prepare("DELETE FROM {$DB->tablePrefix}exam_result WHERE block_user_id='$block_user_id' AND id='$id' ");
		$Msg->success("آزمون دانش آموز: {$exam_result->student_name} حذف شد.");
		header("location:exam.php?id=".($exam_result->exam_id));
		exit;
	}
	else
	{
		$Msg->error("آزمون دانش آموز: {$exam_result->student_name} حذف نشد. خطای رخ داده.");
		header("location:exam.php?id=".($exam_result->exam_id));
		exit;
	}
	
}
else
{
	exit("اطلاعات مورد نظر یافت نشد");
}
