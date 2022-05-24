<?php require_once "blockPageLoader.php";

$exam_id=$Encryption->decode($_POST['exam_id']);
$dir=strip_tags($_POST['dir']);
$show_question_type=strip_tags($_POST['show_question_type']);
$show_list_mark=strip_tags($_POST['show_list_mark']);
$base_mark=strip_tags(@$_POST['base_mark']);
$private=strip_tags(@$_POST['privat']);
$check_ip=strip_tags(@$_POST['check_ip']);
$check_cookie=strip_tags(@$_POST['check_cookie']);
$show_answer=strip_tags(@$_POST['show_answer']);
$number_question=strip_tags(@$_POST['number_question']);
$negative=strip_tags(@$_POST['negative']);

if(empty($number_question))
   $number_question=0;

   
if(!is_numeric($exam_id) || !is_numeric($dir) || !is_numeric($show_question_type) ||
   !is_numeric($show_list_mark) || !is_numeric($base_mark) || !is_numeric($private) ||
   !is_numeric($check_ip) || !is_numeric($show_answer) || !is_numeric($check_cookie) ||
   !is_numeric($negative) || !is_numeric($number_question) )
{
    exit("error: data not valid");
}

$exam=$DB->query("SELECT * FROM {$DB->tablePrefix}exam WHERE block_user_id='$blockUserId' AND id='$exam_id' ",true);
if(!isset($exam->id))
{
   exit("<span class='text-danger' >آزمون  یافت نشد</sapn>'");   
}

if($exam->type!=1)
{
   $number_question=0;
   $negative=0;
}

$row['dir']=$dir;
$row['show_question_type']=$show_question_type;
$row['show_list_mark']=$show_list_mark;
$row['base_mark']=$base_mark;
$row['private']=$private;
$row['check_ip']=$check_ip;
$row['check_cookie']=$check_cookie;
$row['show_answer']=$show_answer;
$row['number_question']=$number_question;
$row['negative']=$negative;


$result=$DB->update("{$DB->tablePrefix}exam",$row,"WHERE id='$exam_id' AND block_user_id='$blockUserId' ");
if($result)
{
    echo "<b style='color:green' >تنظیمات این آزمون ذخیره شد</b>";
}
else
{
    echo "<b style='color:red' >خطای رخ داده دوباره تلاش کنید</b>";
}
exit;
