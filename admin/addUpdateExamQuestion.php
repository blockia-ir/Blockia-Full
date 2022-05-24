<?php require_once "blockPageLoader.php";

$id=$Encryption->decode(strip_tags(@$_POST['id']));
$exam_id=$Encryption->decode(strip_tags($_POST['exam_id']));
$question_type_id=strip_tags(@$_POST['question_type_id']);
$score=strip_tags(@$_POST['score']);
$question=fix($_POST['question']);
$a=fix(@$_POST['a']);
$b=fix(@$_POST['b']);
$c=fix(@$_POST['c']);
$d=fix(@$_POST['d']);
$answer=strip_tags(@$_POST['answer']);
$date=date("Y-m-d H:i:s");
$valid=true;

$score=empty($score) ? 0:$score;

function fix($string)
{
  $pattern = "/<p[^>]*><\\/p[^>]*>/"; 
  //$pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";  use this pattern to remove any empty tag
  $string=preg_replace($pattern, '', $string);
  $string=str_replace('<p dir="ltr">&nbsp;</p>','',$string);
  $string=str_replace('<p dir="ltl">&nbsp;</p>','',$string);
  $string=str_replace('<p>&nbsp;</p>','',$string);
  $string=strip_tags($string,"<p><img><span><b><i><u>");
  return $string;
}

if(!is_numeric($exam_id))
{
	$Msg->error("آزمون نامعتبر است");
  $valid=false;
}

if(!is_numeric($question_type_id))
{
	$Msg->error("نوع سوال نامعتبر است");
    $valid=false;
}

/*
if($score<0.25 || $score>5)
{
	$Msg->error("نمره هر سوال باید بین 0.25 تا 5 نمره باشد");
  $valid=false;
}
*/

if($question=="")
{
    $Msg->error("لطفا سوال آزمون را وارد کنید");
    $valid=false;
}

if($a=="" && ($question_type_id==1 || $question_type_id==2) )
{
    $Msg->error("لطفا گزینه الف وارد کنید");
    $valid=false;
}

if($b=="" && ($question_type_id==1 || $question_type_id==2)  )
{
    $Msg->error("لطفا گزینه ب وارد کنید");
    $valid=false;
}

if($c=="" && $question_type_id==1)
{
    $Msg->error("لطفا گزینه ج وارد کنید");
    $valid=false;
}

if($d=="" && $question_type_id==1)
{
    $Msg->error("لطفا گزینه د وارد کنید");
    $valid=false;
}

if($answer=="" && ($question_type_id==1 ||$question_type_id==2))
{
    $Msg->error("لطفا پاسخ را انتخاب کنید");
    $valid=false;
}

if($valid==false)
{
    header("location:examForm.php?id={$Encryption->encode($exam_id)}");
    exit;
           
}

$row['block_user_id']=$blockUserId;
$row['exam_id']=$exam_id;
$row['question_type_id']=$question_type_id;
$row['score']=$score;
$row['question']=$question;
$row['a']=$a;
$row['b']=$b;
$row['c']=$c;
$row['d']=$d;
$row['answer']=$answer;
$row['date_create']=$date;

//$Msg->info("addupdateexamquestion2");

if($id!="" && is_numeric($id))
{
    //update exam_question
    $result=$DB->update("{$DB->tablePrefix}exam_question",$row,"WHERE block_user_id='$block_user_id' AND id='$id' ");
    if($result)
    {
        $Msg->success("سوال با موفقیت ویرایش شد");
        header("location:examForm.php?id={$Encryption->encode($exam_id)}#btnSetExam");
        exit;
    }
    else
    {
        $Msg->error("خطایی رخ داده. ویرایش سوال انجام نشد");
        header("location:examForm.php?id={$Encryption->encode($exam_id)}#btnSetExam");
        exit;
    }

}
else
{
    //insert exam
    $result=$DB->insert("{$DB->tablePrefix}exam_question",$row);
    if($result)
    {
        $Msg->success(" سوال با موفقیت درج شد");
        header("location:examForm.php?id={$Encryption->encode($exam_id)}#btnSetExam");
        exit;
    }
    else
    {
        $Msg->error("خطایی رخ داده. درج سوال انجام نشد");
        header("location:examForm.php?id={$Encryption->encode($exam_id)}#btnSetExam");
        exit;
    }
}

?>
