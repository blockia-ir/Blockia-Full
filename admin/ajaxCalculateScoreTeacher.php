<?php require_once "blockPageLoader.php";

$exam_result_id=$Encryption->decode(strip_tags(@$_REQUEST['exam_result_id']));
$count_true=0;
$count_false=0;
$count_empty=0;
$sumScore=0;


if(!is_numeric($exam_result_id))
{
    exit("خطا: شماره برگه سوال نامتعبر است");
}


$exam=$DB->query("SELECT {$DB->tablePrefix}exam.base_mark,
                        (SELECT COUNT(id) FROM {$DB->tablePrefix}exam_question WHERE {$DB->tablePrefix}exam_question.exam_id={$DB->tablePrefix}exam.id
                        AND ({$DB->tablePrefix}exam_question.question_type_id!=9 OR {$DB->tablePrefix}exam_question.question_type_id is null)) as countQuestion
                        FROM {$DB->tablePrefix}exam_result
                        INNER JOIN {$DB->tablePrefix}exam ON {$DB->tablePrefix}exam_result.exam_id={$DB->tablePrefix}exam.id AND
                        {$DB->tablePrefix}exam_result.id=<bind>$exam_result_id</bind>
                        WHERE {$DB->tablePrefix}exam_result.block_user_id=<bind>$block_user_id</bind> AND
                        {$DB->tablePrefix}exam_result.id=<bind>$exam_result_id</bind>",true);

$listStudentAnswer=$DB->query("SELECT {$DB->tablePrefix}student_answer.* FROM {$DB->tablePrefix}student_answer WHERE
                           {$DB->tablePrefix}student_answer.exam_result_id=<bind>$exam_result_id</bind>");
$student_answer=array();
$countStudentAnswer=count($listStudentAnswer);
for($i=0;$i<$countStudentAnswer;$i++)
{
	if($listStudentAnswer[$i]->score>0)
    {
        $count_true++;
    }
    elseif($listStudentAnswer[$i]->answer!="" && $listStudentAnswer[$i]->marked==1 )
    {
        $count_false++;
    }
	
	/*
    if($listStudentAnswer[$i]->score>0)
    {
        $count_true++;
    }
    
    if($listStudentAnswer[$i]->marked==1 && $listStudentAnswer[$i]->score==0)
    {
        $count_false++;
    }
	*/
    
    $sumScore+=$listStudentAnswer[$i]->score+0;
}

//echo "{$exam->countQuestion}"."-"."($count_true"."+"."$count_false)";

$count_empty=$exam->countQuestion-($count_true+$count_false);
if($count_empty<0)
{
   $count_empty=0;
}

$percent=round(($sumScore*100)/$exam->base_mark,2);
if($percent>100)
{
   $percent=100;
}
   
$sumScore=$Func->roundMark($sumScore);

$result=$DB->prepare("UPDATE {$DB->tablePrefix}exam_result SET
                 count_true=<bind>$count_true</bind>,
                 count_false=<bind>$count_false</bind>,
                 count_empty=<bind>$count_empty</bind>,
                 count_question=<bind>{$exam->countQuestion}</bind>,
                 mark=<bind>$sumScore</bind>,
                 percent=<bind>$percent</bind>,
                 marked=1
                 WHERE {$DB->tablePrefix}exam_result.id=<bind>$exam_result_id</bind>");

if($result)
{
    echo $sumScore;
}
else
{
    echo "<span class='text-danger' >خطا: در ثبت نمره سوال</span>";
}
