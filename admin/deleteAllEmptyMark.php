<?php require_once "blockPageLoader.php";

$exam_id=$Encryption->decode(strip_tags($_GET['id']));

if(!is_numeric($exam_id))
{
    exit("error: exam id not valid");
}

$exam=$DB->query("SELECT * FROM $tbl_exam WHERE
                 $tbl_exam.block_user_id=<bind>$block_user_id</bind>
                 AND $tbl_exam.id=<bind>$exam_id</bind>",true);

if(!isset($exam->id))
{
    exit("error: exam not found");
}


if(strtotime($exam->date_end)>strtotime("now"))
{
    exit("error: exam not finish");
}

$result=$DB->prepare("DELETE FROM $tbl_exam_result WHERE
                     $tbl_exam_result.mark IS NULL AND $tbl_exam_result.percent IS NULL AND
                     $tbl_exam_result.count_true IS NULL AND $tbl_exam_result.count_false IS NULL AND
                     $tbl_exam_result.block_user_id=<bind>$block_user_id</bind> AND
                     $tbl_exam_result.exam_id=<bind>$exam_id</bind> ");
if($result)
{
    $Msg->success("نمرات خالی حذف شدند");
}
else
{
    $Msg->error("خطای در حذف نمرات خالی رخ داده");
}

header("location:exam_resultPage.php?exam_id={$Encryption->encode($exam_id)}");
exit;
