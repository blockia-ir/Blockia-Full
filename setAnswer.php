<?php require_once "pageLoader.php";

$remainedTime=0;
$countTrue=0;
$countFalse=0;
$countEmpty=0;
$score=null;
$sumScore=0;
$marked=0;

$exam_id=$Encryption->decode(strip_tags($_POST['exam_id']));
$exam_result_id=$Encryption->decode(strip_tags($_POST['exam_result_id']));
$countQuestion=$Encryption->decode(strip_tags($_POST['count_question']));
$question_id=strip_tags($_POST['question_id']);
$answer=addslashes(strip_tags($_POST['answer']));
$answer=nl2br($answer);
                    
if(!is_numeric($exam_result_id))
{
    exit("<span style='color:red' >error: شماره برگه  سوال نامعتبر است </span>");
}

$exam=$DB->query("SELECT {$DB->tablePrefix}exam_result.id,{$DB->tablePrefix}exam_result.id as exam_result_id,
{$DB->tablePrefix}exam_result.date_create,{$DB->tablePrefix}exam_result.date_finsh,
{$DB->tablePrefix}exam.date_end,{$DB->tablePrefix}exam.duration,{$DB->tablePrefix}exam_result.base_mark,
{$DB->tablePrefix}exam.base_mark,{$DB->tablePrefix}exam_result.negative,{$DB->tablePrefix}exam_result.type,
{$DB->tablePrefix}exam_question.question_type_id,{$DB->tablePrefix}exam_question.answer,
{$DB->tablePrefix}exam_question.score,(SELECT COUNT(id) FROM {$DB->tablePrefix}exam_question WHERE
{$DB->tablePrefix}exam_question.exam_id=<bind>$exam_id</bind>
AND {$DB->tablePrefix}exam_question.question_type_id=5) as countTashrihi FROM {$DB->tablePrefix}exam_result
INNER JOIN {$DB->tablePrefix}exam on {$DB->tablePrefix}exam_result.exam_id={$DB->tablePrefix}exam.id AND {$DB->tablePrefix}exam_result.id=<bind>$exam_result_id</bind>
INNER JOIN {$DB->tablePrefix}exam_question on {$DB->tablePrefix}exam.id={$DB->tablePrefix}exam_question.exam_id AND {$DB->tablePrefix}exam_question.id=<bind>$question_id</bind> LIMIT 1",true);

if(!isset($exam->id))
{
    exit("<span style='color:red' >error: شماره برگه سوال یافت نشد</span>");
}

if($exam->date_finsh!="")
{
	exit("<span style='color:red' >error:  خطا:  شما قبلا این آزمون ثبت کردید</span>");
}
 
$remainedTime=120+($exam->duration*60)-(strtotime($date)-strtotime($exam->date_create));

if(isset($exam->duration))
{
    if($remainedTime<1)
    {
        exit("<span style='color:red' >error: خطا: زمان آزمون به پایان رسیده است امکان ثبت نتیجه آزمون وجود ندارد</span>");
    }
} 

// check question is true/false or four option
if($exam->question_type_id=="" || $exam->question_type_id==1 || $exam->question_type_id==2)
{
   // when question not score calculate score
   if($exam->score==0)
   {
      $exam->score=round($exam->base_mark/$countQuestion,2) ;
   }

   
   if($answer==$exam->answer)
   {
      $score=$exam->score;
   }
   elseif($answer=="")
   {
      $score=0;
   }
   else
   {
     $score=0;
   }
    
   $marked=1;
}


//find last answer question
$student_answer=$DB->query("SELECT {$DB->tablePrefix}student_answer.* FROM {$DB->tablePrefix}student_answer WHERE
                           {$DB->tablePrefix}student_answer.exam_result_id=<bind>$exam_result_id</bind> AND
                           {$DB->tablePrefix}student_answer.exam_question_id=<bind>$question_id</bind> ",true);

// set null answer in database when user answer is empty
$answer=($answer=="" ? NULL:$answer);

$row['answer']=$answer;
$row['score']=$score;
$row['marked']=$marked;
$row['date']=$date;

if(isset($student_answer->id))// answer exist  update answer
{  
   $result=$DB->update("{$DB->tablePrefix}student_answer",$row,"WHERE  id='{$student_answer->id}'");
   $msg="<span class='text-success' >پاسخ ویرایش شد</span>";
}
else //  answer not exist insert answer
{
   $row['exam_result_id']=$exam_result_id;
   $row['exam_question_id']=$question_id;
   $result=$DB->insert("{$DB->tablePrefix}student_answer",$row);
   $msg="<span class='text-success' >پاسخ درج شد</span>";
   
     if(is_numeric($score))
        $sumScore=$sumScore+$score;
}

// after set answer 
if($result)
{
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
		
	}
	else
	{
		$percent=round(($sumScore*100)/$exam->base_mark,2);
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
   
   // update exam result 
   $DB->prepare("UPDATE {$DB->tablePrefix}exam_result SET
                 count_true=<bind>$countTrue</bind>,
                 count_false=<bind>$countFalse</bind>,
                 count_empty=<bind>$countEmpty</bind>,
                 count_question=<bind>$countQuestion</bind>,
                 mark=<bind>$sumScore</bind>,
                 percent=<bind>$percent</bind>
                 WHERE {$DB->tablePrefix}exam_result.id=<bind>$exam_result_id</bind>");
   
   echo '{"status":"1","msg":"'.$msg.'"}';
}
else
{
   $msg="<span class='text-danger' >خطا در ثبت نمره</span>";
   echo '{"status":"0","msg":"'.$msg.'"}';
	
}