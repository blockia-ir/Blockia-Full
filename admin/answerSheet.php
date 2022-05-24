<?php require_once "blockPageLoader.php";

$exam_result_id=$Encryption->decode(strip_tags($_GET['i']));

if(!is_numeric($exam_result_id))
{
  exit("خطا: اطلاعات ارسالی ناقص است");
}

$exam_result=$DB->query("SELECT {$DB->tablePrefix}exam_result.* FROM {$DB->tablePrefix}exam_result WHERE
												block_user_id=<bind>$block_user_id</bind> AND id=<bind>$exam_result_id</bind>",true);

$exam=$DB->query("SELECT {$DB->tablePrefix}exam.*,
                 (SELECT COUNT(id) FROM {$DB->tablePrefix}exam_question WHERE
                  {$DB->tablePrefix}exam_question.exam_id=<bind>{$exam_result->exam_id}</bind>
                  AND {$DB->tablePrefix}exam_question.question_type_id=5) as countTashrihi
                  FROM {$DB->tablePrefix}exam
                 WHERE block_user_id=<bind>$block_user_id</bind> AND
                 id={$exam_result->exam_id}",true);
if(!isset($exam->id))
{
     exit("خطا: آزمون  موجود نیست");
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
$arrayQuestion=$arrayAnswer=$arrayScore=array();

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
                             WHERE exam_id=<bind>{$exam->id}</bind> $whereQuestion AND
                             ({$DB->tablePrefix}exam_question.question_type_id!=9 OR {$DB->tablePrefix}exam_question.question_type_id is null)
                              ORDER BY {$DB->tablePrefix}exam_question.question_type_id DESC , {$DB->tablePrefix}exam_question.id ASC");
foreach($listExamQuestion as $question)
{
  $arrayQuestion[$question->id]= $question->answer;
}unset($question);

//$listExamQuestion=$DB->query("CALL `listExamQuestionType2`({$exam_result->exam_id})");
       
$title="شماره برگه:".$exam_result_id." پاسخ نامه ".$exam_name. "_".$student_name;
$title=str_replace(" ","_",$title);

function fix($string){
  $pattern = "/<p[^>]*><\\/p[^>]*>/"; 
  //$pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";  use this pattern to remove any empty tag
  $string=preg_replace($pattern, '', $string);
  $string=str_replace('<p dir="ltr">&nbsp;</p>','',$string);
  $string=str_replace('<p dir="ltl">&nbsp;</p>','',$string);
  //$string=str_replace('="../','="/',$string);
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
  
$nextExamResult=$DB->query("SELECT * FROM {$DB->tablePrefix}exam_result WHERE
                           ({$DB->tablePrefix}exam_result.marked is null OR {$DB->tablePrefix}exam_result.marked=0)
						   AND
                           {$DB->tablePrefix}exam_result.id!=$exam_result_id AND
                           {$DB->tablePrefix}exam_result.exam_id={$exam_result->exam_id}
                           ORDER BY id ASC LIMIT 1 ",true);
if(isset($nextExamResult->id))
{
	$nextUrl="answerSheet.php?i=".$Encryption->encode($nextExamResult->id);
}
else
{
	$nextUrl="answerSheetEndPage.php";
}

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
    <link rel="icon" type="image/png" href="../images/iconExam2.png" />
    <link rel="stylesheet" href="../lib/fonts/font.css" />
    <!--<link rel="stylesheet" href="font-awesome/css/fontawesome-all.min.css"  />-->
    <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <script src="../lib/js/jquery.min.js"></script>
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
              <div class="col-2 text-center" >
                <button type="button" class="btn btn-sm btn-warning d-print-none" onclick="window.print()" >چاپ</button>
              </div>
              <?php if($exam->countTashrihi>0):?>
              <div class="col-5 text-center" >
                <a class="btn btn-sm btn-success text-white d-print-none" style="" href="<?=$nextUrl?>">تصحیح بعدی</a>
              </div>
              <div class="col-5 text-center" >
                <button class="btn btn-sm btn-info text-white btnCalculateScore d-print-none" >ثبت نتیجه</button>
              </div>
              <?php endif ?>
          </div> 
        </div>
        
        <div class="container" style="max-width: 700px;" id="seprator" > &nbsp; </div>
        
        <div class="container" style="max-width: 700px;margin-top:35px;" >
          <?=$msgInfoExam?>
          <?php $i=0;foreach($listExamQuestion as $question):$i++?>
     
          <div class="row flex-row bg-white mb-1 p-1  border blockQuestion " >
             
                <div class="col-md-12 p-1 ">
                    <div class="question <?=checkDirection($question->question)?>" >
                    <?= $i.") ".fix($question->question)?>
                    <?php if($exam->type==2):?>
                    <span class="font-italic small" >بارم (<?=$question->score+0?>)</span>
					<span class="text-primary small" >نمره دریافتی: <?=@$arrayScore[$question->id]+0?></span>
                    <?php endif ?>
                    </div> 
                </div> 
                 <?php if($question->question_type_id!=5):?>
                  <div class="col-md-3 col-sm-6 h-100 p-1 <?=("a"==@$question->answer) ? "answerTrue" : "" ?> " >
                    <input type="radio" disabled class="radioOption" <?=("a"==@$arrayAnswer[$question->id]) ? "checked" : "" ?> id="a<?=$question->id?>" data-id="<?=$question->id?>"  value="a" />
                    <label for="a<?=$question->id?>" class='option w-100 border rounded p-2 <?=checkDirection($question->a)?>' ><?=fix($question->a)?></label>
                  </div>
                  <div class="col-md-3 col-sm-6 h-100 p-1 <?=("b"==@$question->answer) ? "answerTrue" : "" ?> " >
                    <input type="radio" disabled class="radioOption" <?=("b"==@$arrayAnswer[$question->id]) ? "checked" : "" ?>  id="b<?=$question->id?>"  data-id="<?=$question->id?>" value="b" />
                    <label for="b<?=$question->id?>"  class='option w-100 border rounded p-2 <?=checkDirection($question->b)?>' ><?=fix($question->b)?></label>
                  </div>
                  <div class="col-md-3 col-sm-6 h-100 p-1 <?=("c"==@$question->answer) ? "answerTrue" : "" ?> <?=(@$question->question_type_id==2 ? "d-none" :"" )?>"" >
                    <input type="radio" disabled class="radioOption" <?=("c"==@$arrayAnswer[$question->id]) ? "checked" : "" ?>  id="c<?=$question->id?>"  data-id="<?=$question->id?>"  value="c" />
                    <label for="c<?=$question->id?>"  class='option w-100 border rounded  p-2 <?=checkDirection($question->c)?>' ><?=fix($question->c)?></label>
                  </div>
                  <div class="col-md-3 col-sm-6 h-100 p-1 <?=("d"==@$question->answer) ? "answerTrue" : "" ?> <?=(@$question->question_type_id==2 ? "d-none" :"" )?>" " >
                    <input type="radio" disabled class="radioOption" <?=("d"==@$arrayAnswer[$question->id]) ? "checked" : "" ?>  id="d<?=$question->id?>"  data-id="<?=$question->id?>" value="d" />
                    <label for="d<?=$question->id?>"  class='option w-100 border rounded  p-2 <?=checkDirection($question->d)?>' ><?=fix($question->d)?></label>
                  </div>
                <?php endif?>
                <?php if($question->question_type_id==5):?>
                <div class="col-md-12 font-italic rounded"  style="background-color: #f7f7f7;">
                   <div class="text-info font-italic small ">  پاسخ دانش آموز:  </div>
                   <div class="text-wrap text-break" ><?= nl2br(@$arrayAnswer[$question->id]) ?></div>
                   
                </div>
                <div class="row mt-1">
                  
                    <?php
                                if($question->score<=1) $scoreStep=0.25;
                            elseif($question->score<=2) $scoreStep=0.5;
                            elseif($question->score<=3) $scoreStep=0.75;
                            elseif($question->score<=4) $scoreStep=1;
                            elseif($question->score<=5) $scoreStep=1.25;
                           
                    ?>
                    <div class="col-12">
                      <div id="msg<?=$question->id?>"> &nbsp; </div>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <?php
                                for($score=0;$question->score>$score;$score=$score+$scoreStep)
                                {
                                        echo "<button type='button' onclick=\"document.getElementById('{$question->id}').value=this.value\" value='$score' class='btn btn-secondary btnSetScore d-print-none' data-id='{$question->id}' >$score</button>";
                                   
                                }
                            ?>
                            <button type="button" class="btn btn-secondary btnSetScore d-print-none" onclick="document.getElementById('<?=$question->id?>').value=this.value" value='<?=$question->score+0?>' data-id='<?=$question->id?>' ><?=$question->score+0?></button>
                            <input type="number" class="form-control d-print-none text-center" step="0.25" min="0" max="<?=$question->score?>" id="<?=$question->id?>" value="<?=(isset($arrayScore[$question->id]) ? $arrayScore[$question->id]+0:"")?>" style="border-radius:0px;" />
                            <button class="btn btn-info btnSetScore d-print-none" data-id='<?=$question->id?>' >ثبت</button>

                        </div>
                    </div>
                </div>
                <?php endif?>

          </div>
          <?php endforeach?>
        </div>
        
        <script>
          var exam_result_id='<?=$Encryption->encode($exam_result_id)?>';
          
					$(".btnSetScore").click(function()
              {
               var question_id=$(this).attr("data-id");
               var score=$("#"+question_id).val();
               var min=$("#"+question_id).attr("min");
               var max=$("#"+question_id).attr("max");
               
               if(score=="" || score==undefined)
               {
                  $("#msg"+question_id).html("<span class='text-danger' >نمره را وارد کنید</span>");
                  return false;
               }
               
               if(score<min || score>max)
               {
                  $("#msg"+question_id).html("<span class='text-danger' > نمره این سوال باید بین "+min+" و "+max+" باشد </span>");
                  return false;
               }
               
               
               $("#msg"+question_id).html("در حال ثبت نمره . . . ");
               $.post("ajaxSetScoreTeacher.php",
                      {
                        exam_result_id:exam_result_id,
                        question_id:question_id,
                        score:score
                      },
                      function(data)
                      {
                        $("#msg"+question_id).html(data);
                      }
                );
               
              }
            );
          
          	$(".btnCalculateScore").click(function()
              {
               $("#sumScore").html("...");
               $.post("ajaxCalculateScoreTeacher.php",
                      {
                        exam_result_id:exam_result_id
                      },
                      function(data)
                      {
                        $("#sumScore").html(data);
                      }
                );
               
              }
            );
				</script>
  </body>
<style>
    body{
        background:#f7f7f7;
    }
    .answerTrue
    {
        border: 2px solid green;
        border-radius:5px;
    }
    .question, .option{
        cursor:default;
    }
	#headerExam {
		height: 100px;
	}

	
	@media print 
	{
		.fixed-top
		{
			position: relative;
		}

		#seprator {
			height: 0px !important;
			margin-bottom: -50px;
		}

	  table{ page-break-after:auto }
	  table tr    { page-break-inside:avoid; page-break-after:auto }
	  table td    { page-break-inside:avoid; page-break-after:auto }
	  table thead { display:table-header-group }
	  table tfoot { display:table-footer-group }
	}
 

</style>
</html>
