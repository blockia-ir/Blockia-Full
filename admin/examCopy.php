<?php require_once "blockPageLoader.php";

$date=date("Y-m-d H:i:s");

$exam_id=strip_tags($_REQUEST['exam_id']);
if(!is_numeric($exam_id))
{
    exit("error: exam_id is not valid");
}

$exam=$DB->query("SELECT * FROM {$DB->tablePrefix}exam WHERE block_user_id=<bind>$block_user_id</bind> AND id=<bind>$exam_id</bind>",true);
if(!isset($exam->id))
{
    exit("error: exam not exist");
}

$row['block_user_id']=$block_user_id;
$row['type']=$exam->type;
$row['name']=" کپی ".$exam->name;
$row['class']=$exam->class;
$row['duration']=$exam->duration;
$row['base_mark']=$exam->base_mark;
$row['show_question_type']=$exam->show_question_type;
$row['show_list_mark']=$exam->show_list_mark;
$row['show_answer']=$exam->show_answer;
$row['number_question']=$exam->number_question;
$row['negative']=$exam->negative;
$row['private']=$exam->private;
$row['check_ip']=$exam->check_ip;
$row['check_cookie']=$exam->check_cookie;
$row['dir']=$exam->dir;
$row['date_start']=null;
$row['date_end']=null;
$row['date_create']=$date;

$result=$DB->insert("{$DB->tablePrefix}exam",$row);
if($result)
{
    $new_exam_id=$DB->lastInsertId();
    $DB->prepare("INSERT INTO `{$DB->tablePrefix}exam_class`
                (block_user_id,exam_id,class_id)
                select $block_user_id,$new_exam_id,class_id from {$DB->tablePrefix}exam_class WHERE exam_id=$exam_id ");
    
    $DB->prepare("INSERT INTO `{$DB->tablePrefix}exam_question`
            (`block_user_id`, `exam_id`, `question_type_id`,`question`, `a`, `b`, `c`, `d`, `answer`, `score`,`ordr`,`date_create`)
       SELECT $block_user_id,$new_exam_id,question_type_id,  question,  a,    b,   c,   d,   answer,   score,  ordr,  '$date' FROM {$DB->tablePrefix}exam_question  WHERE exam_id=$exam_id" );
    
    $Msg->success("آزمون  با موفقیت کپی شد");
    $Msg->info("  <i class='fa fa-exclamation-triangle' aria-hidden='true'></i> فراموش نکنید که زمان شروع و پایان آزمون کپی شده را به  صورت دستی تنظیم کنید");

	header("location:examForm.php?id=".$Encryption->encode($new_exam_id));
    exit;
}
else
{
    $Msg->error("خطای رخ داده کپی آزمون  انجام  نشد");
    header("location:examForm.php?id=".$Encryption->encode($new_exam_id));
    exit;
}




