<?php require_once "pageLoader.php";

$exam_result_id=strip_tags($_GET['i']);
$exam_result_id=$Encryption->decode($exam_result_id);

if(!is_numeric($exam_result_id))
{
  exit("خطا: اطلاعات ارسالی ناقص است");
}


$exam_result=$DB->query("SELECT {$DB->tablePrefix}exam_result.* FROM {$DB->tablePrefix}exam_result 
						WHERE id=<bind>$exam_result_id</bind>",true);

$exam=$DB->query("SELECT * FROM {$DB->tablePrefix}exam WHERE id={$exam_result->exam_id}",true);
if(!isset($exam->id))
{
     exit("خطا: آزمون  موجود نیست");
}

if($exam->show_answer!=1)
{
	exit("خطا پاسخنامه غیر فعال شده");
}

$date_end=strtotime($exam->date_end);
$date_now=strtotime(date("Y-m-d H:i:s"))+60;
if($date_end>$date_now)
{
	require_once "notShowAnswerSheet.php";
	exit;
}

if(isset($exam_result->id))
{
    $exam_result_id=$exam_result->id;
    $student_name=$exam_result->student_name;
    $exam_name=$exam_result->exam_name;
    $class_name=$exam_result->class_name;
    $date_create=$exam_result->date_create;
}

$whereQuestion="";
$arrayQuestion=$arrayAnswer=$arrayScore=array();//define var

$breaks = array("<br />","<br>","<br/>");  
$answer_user=$DB->query("SELECT exam_question_id,answer,score FROM `{$DB->tablePrefix}student_answer`
                        WHERE exam_result_id=<bind>$exam_result_id</bind>");
foreach($answer_user as $answer)
{
	$arrayAnswer[$answer->exam_question_id]=str_ireplace($breaks, "", $answer->answer);
  $arrayScore[$answer->exam_question_id]=$answer->score;
	//just for use when $exam_result->number_question>0
  $whereQuestion.=$answer->exam_question_id.",";
}unset($answer);

if($exam_result->type==1 && $exam_result->number_question>0 )
{
	$whereQuestion=rtrim($whereQuestion,",");
	$whereQuestion=" AND {$DB->tablePrefix}exam_question.id IN ($whereQuestion) ";
}
else
{
  $whereQuestion="";
}

$listExamQuestion=$DB->query("SELECT {$DB->tablePrefix}exam_question.* FROM {$DB->tablePrefix}exam_question 
							WHERE exam_id=<bind>{$exam->id}</bind> $whereQuestion");
foreach($listExamQuestion as $question)
{
  $arrayQuestion[$question->id]= $question->answer;
}unset($question);

     
$title=" پاسخ نامه ".$exam_name. " | ".$student_name;

function fix($string){
  $pattern = "/<p[^>]*><\\/p[^>]*>/"; 
  //$pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";  use this pattern to remove any empty tag
  $string=preg_replace($pattern, '', $string);
  $string=str_replace('<p dir="ltr">&nbsp;</p>','',$string);
  $string=str_replace('<p dir="ltl">&nbsp;</p>','',$string);
  $string=str_replace('="../','="/',$string);
  return nl2br($string);
}

function checkDirection($string)
{
  if(strstr($string,'dir="ltr"')!="")
    return "ltr";
  elseif(strstr($string,'dir="rtl"')!="")
    return "rtl";
  else
    return "";
}

$msgInfoExam="";
if($exam_result->negative>0)
{
  $msgInfoExam="
    این آزمون دارای نمره  منفی بوده
    <br/>
    به ازای هر {$exam_result->negative} غلط یک پاسخ  درست حذف شده
  ";
}
if($exam_result->base_mark!="" && $exam_result->base_mark!=20 )
{
  $msgInfoExam.="<br/> نمره این آزمون از {$exam_result->base_mark} محاسبه شده";
}

if($msgInfoExam!="")
  $msgInfoExam="<div class='alert alert-info small text-center p-0' >$msgInfoExam</div>";
 

?>

<!DOCTYPE html>
<html lang="fa">
  <head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#F7F7F7" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= @$title ?></title>
    <meta name="description" content="<?= @$description ?> " />
    <link rel="icon" type="image/png" href="images/iconExam2.png" />
    <link rel="stylesheet" href="lib/fonts/font.css" />
    <!--<link rel="stylesheet" href="font-awesome/css/fontawesome-all.min.css"  />-->
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap-rtl.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="lib/js/jquery.min.js"></script>
  </head>

  <body class="rtl" >
        <div class="container border fixed-top " style="max-width: 700px;" >
            <div class="row text-center bg-light text-primary align-items-center "  id="headerExam"   >
              <div class="col-3 p-0">
                <?=$student_name?>
              </div>
              <div class="col-6 p-0">
                <?=$exam_name?>
              </div>
              <div class="col-3 p-0">
                <div id="remainedTime" >
                 نمره: <span id='sumScore' ><?=$exam_result->mark+0?></span>
                </div>
              </div>
            </div> 
        </div>
        
        <div class="container" style="max-width: 700px;" id="seprator" > seprator </div>
        
        <div class="container" style="max-width: 700px;" >
          <?=$msgInfoExam?>
          
          <?php $i=0;foreach($listExamQuestion as $question):$i++?>
     
          <div class="row flex-row bg-white mb-1 p-1  border blockQuestion " >
             
                <div class="col-md-12 p-1 ">
                    <div class="question <?=checkDirection($question->question)?>" >
                    <?= $i.") ".fix($question->question)?>
					<br/>
                     <span class="font-italic small" >بارم (<?=$question->score+0?>) | <span class='text-primary' >نمره دریافتی <?=@$arrayScore[$question->id]+0?></span></span>
                    </div>
                   
                </div> 
                 <?php if($question->question_type_id!=5):?>
                  <div class="col-md-3 col-sm-6 h-100 p-1 <?=("a"==@$question->answer) ? "answerTrue" : "" ?> " >
                    <input type="radio" disabled class="radioOption" <?=("a"==@$arrayAnswer[$question->id]) ? "checked" : "" ?> id="a<?=$question->id?>" data-id="<?=$question->id?>"  value="a" />
                    <label for="a<?=$question->id?>" class='option w-100 border rounded h-100 p-2 <?=checkDirection($question->a)?>' ><?=fix($question->a)?></label>
                  </div>
                  <div class="col-md-3 col-sm-6 h-100 p-1 <?=("b"==@$question->answer) ? "answerTrue" : "" ?> " >
                    <input type="radio" disabled class="radioOption" <?=("b"==@$arrayAnswer[$question->id]) ? "checked" : "" ?>  id="b<?=$question->id?>"  data-id="<?=$question->id?>" value="b" />
                    <label for="b<?=$question->id?>"  class='option w-100 border rounded h-100 p-2 <?=checkDirection($question->b)?>' ><?=fix($question->b)?></label>
                  </div>
                  <div class="col-md-3 col-sm-6 h-100 p-1 <?=("c"==@$question->answer) ? "answerTrue" : "" ?> <?=(@$question->question_type_id==2 ? "d-none" :"" )?>"" >
                    <input type="radio" disabled class="radioOption" <?=("c"==@$arrayAnswer[$question->id]) ? "checked" : "" ?>  id="c<?=$question->id?>"  data-id="<?=$question->id?>"  value="c" />
                    <label for="c<?=$question->id?>"  class='option w-100 border rounded h-100 p-2 <?=checkDirection($question->c)?>' ><?=fix($question->c)?></label>
                  </div>
                  <div class="col-md-3 col-sm-6 h-100 p-1 <?=("d"==@$question->answer) ? "answerTrue" : "" ?> <?=(@$question->question_type_id==2 ? "d-none" :"" )?>" " >
                    <input type="radio" disabled class="radioOption" <?=("d"==@$arrayAnswer[$question->id]) ? "checked" : "" ?>  id="d<?=$question->id?>"  data-id="<?=$question->id?>" value="d" />
                    <label for="d<?=$question->id?>"  class='option w-100 border rounded h-100 p-2 <?=checkDirection($question->d)?>' ><?=fix($question->d)?></label>
                  </div>
                <?php endif?>
                <?php if($question->question_type_id==5):?>
                <div class="col-md-12 font-italic rounded"  style="background-color: #f7f7f7;">
                   <div class="text-info font-italic small ">  پاسخ دانش آموز:  </div>
                   <div class="text-wrap text-break"  ><?= @$arrayAnswer[$question->id] ?></div>
                   
                </div>
                
                <?php endif?>

          </div>
          <?php endforeach?>
        </div>
        
  </body>
	<style>
			body
			{
					background:#f7f7f7;
			}
			.answerTrue
			{
					border: 2px solid green;
					border-radius:5px;
			}
			.question, .option
			{
					cursor:default;
			}
	</style>
</html>
