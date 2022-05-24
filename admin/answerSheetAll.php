<?php require_once "blockPageLoader.php";

$exam_id=$Encryption->decode(strip_tags($_GET['exam_id']));

if(!is_numeric($exam_id))
{
  exit("خطا: اطلاعات ارسالی ناقص است");
}

$sql="SELECT {$DB->tablePrefix}exam_result.id, {$DB->tablePrefix}exam_result.student_name,{$DB->tablePrefix}exam_result.class_name,{$DB->tablePrefix}exam_result.mark,
{$DB->tablePrefix}exam_question.question,{$DB->tablePrefix}exam_question.a,{$DB->tablePrefix}exam_question.b,{$DB->tablePrefix}exam_question.c,{$DB->tablePrefix}exam_question.d,
{$DB->tablePrefix}exam_question.answer,{$DB->tablePrefix}student_answer.answer as student_answer FROM `{$DB->tablePrefix}student_answer` 
INNER JOIN {$DB->tablePrefix}exam_result on {$DB->tablePrefix}student_answer.exam_result_id={$DB->tablePrefix}exam_result.id ANd {$DB->tablePrefix}exam_result.exam_id=<bind>$exam_id</bind>
INNER JOIN {$DB->tablePrefix}exam_question on {$DB->tablePrefix}student_answer.exam_question_id={$DB->tablePrefix}exam_question.id AND {$DB->tablePrefix}exam_question.exam_id=<bind>$exam_id</bind>
WHERE {$DB->tablePrefix}exam_result.exam_id=<bind>$exam_id</bind>";

$listAnswerSheet=$DB->query($sql);
$examResult=$listAnswerSheet[0]->id;$i=0;$html="";      
$html='
<div style="direction:rtl;" >
<div class=" alert alert-warning" >'.
    $listAnswerSheet[0]->student_name." | ".$listAnswerSheet[0]->class_name.
    '</div>';
foreach($listAnswerSheet as $answerSheet)
{

    if($answerSheet->id!=$examResult)
    {
        $html.='<div class=" alert alert-warning" >'.
            $answerSheet->student_name." | ".$answerSheet->class_name.
            '</div>';
            $i=1;
    }
    else
    {
        $i++;
    }
    
    $html.="<div>$i)</span>
        <span>".strip_tags($answerSheet->question,"<img><span>")."</span><br/>
        <span>الف)".strip_tags($answerSheet->a,"<img><span>")."</span>
        <span>ب)".strip_tags($answerSheet->b,"<img><span>")."</span>
        <span>ج)".strip_tags($answerSheet->c,"<img><span>")."</span>
        <span>د)".strip_tags($answerSheet->d,"<img><span>")."</span><br/>
        <span>پاسخ دانش آموز</span>
        <span>{$answerSheet->student_answer}</div><br/>";

    if($answerSheet->id!=$examResult)
    {
        //$i=1;
        $examResult=$answerSheet->id;
        $html."<hr/><hr/>";
    }
    else
    {
        //$i++;
    }

}

$html.="</div>";


function fix($string)
{
  $pattern = "/<p[^>]*><\\/p[^>]*>/"; 
  //$pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";  use this pattern to remove any empty tag
  $string=preg_replace($pattern, '', $string);
  $string=str_replace('<p dir="ltr">&nbsp;</p>','',$string);
  $string=str_replace('<p dir="ltl">&nbsp;</p>','',$string);
  //$string=str_replace('="../','="/',$string);
  return nl2br($string);
}
?>

<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <link rel="icon" type="image/png" href="../images/iconExam2.png" />
        <link rel="stylesheet" href="../lib/fonts/font.css"  />
        <link rel="stylesheet" href="../lib/font-awesome/css/fontawesome-all.min.css" >
        <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" />
        <link rel="stylesheet" href="../css/panel.css" />
        <script src="../lib/js/jquery.min.js"></script>
        <script src="../lib/js/function.js"></script>
    </head>
    <body class="rtl" >
        <div class="container">
            <?=$Msg->show();?>
            <?= $html ?>
        <style>
            img{
                max-width: 200px;
                display: block;
            }
        </style>
        </div>
    <body>
</html>

