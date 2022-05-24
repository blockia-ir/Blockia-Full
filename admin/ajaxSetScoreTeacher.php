<?php require_once "blockPageLoader.php";

$exam_result_id=$Encryption->decode(strip_tags(@$_REQUEST['exam_result_id']));
$question_id=strip_tags($_REQUEST['question_id']);
$score=$Func->faNumToEn(strip_tags($_REQUEST['score']));
$marked=1;

if(!is_numeric($exam_result_id))
{
    exit("خطا: شماره برگه سوال نامتعبر است");
}

if(!is_numeric($question_id))
{
    exit("خطا: شماره سوال نامتعبر است");
}

if($score<0)
{
    exit("خطا: نمره  معتبر نیست");
}



$student_answer=$DB->query("SELECT {$DB->tablePrefix}student_answer.* FROM {$DB->tablePrefix}student_answer WHERE
                           {$DB->tablePrefix}student_answer.exam_result_id=<bind>$exam_result_id</bind> AND
                           {$DB->tablePrefix}student_answer.exam_question_id=<bind>$question_id</bind>",true);

if(isset($student_answer->id))
{
   $result=$DB->prepare("UPDATE {$DB->tablePrefix}student_answer SET
                        score=<bind>$score</bind>,
                        marked=<bind>$marked</bind>
                        WHERE  id=<bind>{$student_answer->id}</bind> ");
}
else
{
    $result=$DB->prepare("INSERT INTO `{$DB->tablePrefix}student_answer`
    (
    `exam_result_id`,`exam_question_id`,`score`,`marked`)
    VALUES
    (<bind>$exam_result_id</bind>,<bind>$question_id</bind>,<bind>$score</bind>,<bind>$marked</bind>)");
     
}

if($result)
{
    echo "<span class='text-success' > نمره ".$score." ثبت شد</span>";
}
else
{
    echo "خطا: در ثبت نمره سوال";
    
}
