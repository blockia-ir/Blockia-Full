<?php require_once "pageLoader.php";

$remainedTime=0;
$countTrue=0;
$countFalse=0;
$countEmpty=0;
$score=null;
$sumScore=0;

$exam_id=$Encryption->decode(strip_tags($_GET['exam_id']));
$exam_result_id=$Encryption->decode(strip_tags($_GET['exam_result_id']));
$countQuestion=$Encryption->decode(strip_tags($_GET['count_question']));
//$base_mark=$Encryption->decode(strip_tags($_GET['base_mark']));

if(!is_numeric($exam_id))
{
   exit("error: شماره آزمون نامتعبر است");
}

if(!is_numeric($exam_result_id))
{
   exit("شماره برگه سوال نامتعبر است");
}

if(!is_numeric($countQuestion))
{
   exit("تعداد سوالات  نامتعبر است");
}

//if(!is_numeric($base_mark))
//{
//   exit("نمره آزمون  نا متعبر است");
//}

$exam=$DB->query("SELECT {$DB->tablePrefix}exam_result.id,{$DB->tablePrefix}exam_result.id as exam_result_id,
{$DB->tablePrefix}exam_result.date_create,{$DB->tablePrefix}exam_result.date_finsh,
{$DB->tablePrefix}exam.date_end,{$DB->tablePrefix}exam.duration,{$DB->tablePrefix}exam_result.base_mark,
{$DB->tablePrefix}exam.base_mark,{$DB->tablePrefix}exam_result.negative,{$DB->tablePrefix}exam_result.type,
(SELECT COUNT(id) FROM {$DB->tablePrefix}exam_question WHERE
{$DB->tablePrefix}exam_question.exam_id=<bind>$exam_id</bind>
AND {$DB->tablePrefix}exam_question.question_type_id=5) as countTashrihi FROM {$DB->tablePrefix}exam_result
INNER JOIN {$DB->tablePrefix}exam on {$DB->tablePrefix}exam_result.exam_id={$DB->tablePrefix}exam.id
AND {$DB->tablePrefix}exam_result.id=<bind>$exam_result_id</bind>",true);

if(!isset($exam->id))
	exit("error exam result not found");

$answerResult=$DB->query("SELECT sum(score) as sumScore, count(if(score>0,1,null)) as countTrue, count(if(score=0,1,null)) as countFalse
               FROM `{$DB->tablePrefix}student_answer` WHERE exam_result_id=<bind>$exam_result_id</bind>",true);
               
$sumScore=$answerResult->sumScore;
$countTrue=$answerResult->countTrue;
$countFalse=$answerResult->countFalse;
$countEmpty=$countQuestion-($countTrue+$countFalse);

if((int)$exam->countTashrihi==0)
{
   if($exam->type=1 && $exam->negative>0)
   {
      $negative=(int)$exam->negative;
      $percent=round(((($negative*$countTrue)-$countFalse)/($negative*$countQuestion))*100,2);
      $sumScore=round(((($negative*$countTrue)-$countFalse)/($negative*$countQuestion))*(int)$exam->base_mark,2);
   }
   else
   {
      $percent=round(($countTrue*100)/$countQuestion,2);
   }
   $marked=1; 
   
}
else
{
   $percent=round(($sumScore*100)/$exam->base_mark,2);
   $marked=0;
}

// fix percent if more than 100 when use round
if($percent>100)
{
   $percent=100;
}

$sumScore=$Func->roundMark($sumScore);

// fix sumScore if more than base mark when use round 
if($sumScore>$exam->base_mark)
{
   $sumScore=floor($sumScore);
}
   
// fix sumScore if negative 
if($sumScore<0 || $sumScore==false)
{
   $sumScore=0;
}

$result=$DB->prepare("UPDATE {$DB->tablePrefix}exam_result SET
                 count_true=<bind>$countTrue</bind>,
                 count_false=<bind>$countFalse</bind>,
                 count_empty=<bind>$countEmpty</bind>,
                 count_question=<bind>$countQuestion</bind>,
                 mark=<bind>$sumScore</bind>,
                 percent=<bind>$percent</bind>,
                 marked=$marked,
                 date_finsh=<bind>$date</bind>
                 WHERE {$DB->tablePrefix}exam_result.id=<bind>$exam_result_id</bind>");

if($result)
{
    $Msg->success("آزمون  شما با موفقیت ثبت شد");
    header("location:examResult.php?id=$exam_id&eri=$exam_result_id");
    exit;
}
else
{
    exit("<span style='color:#ff8800' >خطای رخ نتیجه آزمون شما به  طور کامل ثبت نشد</span>");
}
