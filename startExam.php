<?php require_once "pageLoader.php";
$exam_result_id=$Encryption->decode(strip_tags((@$_REQUEST['id'])));
$remainedTime=0;
$dir="rtl";

if(!is_numeric($exam_result_id))
{
  exit("خطا: اطلاعات ارسالی ناقص است");
}


$exam_result=$DB->query("SELECT {$DB->tablePrefix}exam_result.*,{$DB->tablePrefix}exam.duration,
						{$DB->tablePrefix}exam.date_start,{$DB->tablePrefix}exam_result.base_mark,{$DB->tablePrefix}exam.dir,
            {$DB->tablePrefix}exam.date_end,{$DB->tablePrefix}exam.show_question_type,
						{$DB->tablePrefix}exam_result.number_question,{$DB->tablePrefix}exam_result.type
						FROM {$DB->tablePrefix}exam_result 
            INNER JOIN {$DB->tablePrefix}exam ON {$DB->tablePrefix}exam_result.exam_id={$DB->tablePrefix}exam.id AND {$DB->tablePrefix}exam_result.id=<bind>$exam_result_id</bind>
            WHERE {$DB->tablePrefix}exam_result.id=<bind>$exam_result_id</bind>",true);
if(isset($exam_result->id))
{
	$exam_id=$exam_result->exam_id;
	$tdate_start=strtotime($exam_result->date_start);
	$tdate_end=strtotime($exam_result->date_end);
	$tdate_now=strtotime("now");

	if(empty($_COOKIE[$exam_id]) &&  empty($_SESSION['exam_result_id']))
	{
		exit("خطای رخ داده مرورگر ببندید و دوباره  وارد لینک آزمون  بشید");
	}
	
	if($_COOKIE[$exam_id]!=$exam_result_id && $_SESSION['exam_result_id']!=$exam_result_id)
	{
		$Msg->error("خطای رخ  داده  دوباره وارد آزمون  بشوید");
		header("location:exam.php?id=$exam_id");
		exit;
	}
	
	if($tdate_start>$tdate_now )
	{
		$Msg->error("آزمون هنوز شروع نشده");
		unset($_COOKIE[$exam_id]);
		@setcookie($exam_id,null,time()-(365*24*60*60),"/");
		header("location:exam.php?id=$exam_id");
		exit;
	}
	elseif($tdate_now>=$tdate_end)
	{
		$Msg->error("آزمون به پایان رسیده است");
		header("location:examResult.php.php?id=$exam_id");
		exit;
	}

	if($exam_result->date_finsh!="") 
	{
		header("location:examResult.php?id=$exam_id&eri=$exam_result_id");
		exit;
	}
	else
	{
		
		$exam_result_id=$exam_result->id;
		$exam_id=$exam_result->exam_id;
		$student_name=$exam_result->student_name;
		$exam_name=$exam_result->exam_name;
		$class_name=$exam_result->class_name;

		$date_start=$exam_result->date_start;
		$date_end=$exam_result->date_end;
		$date_create=$exam_result->date_create;
		$duration=$exam_result->duration;
		$remainedTime=($exam_result->duration*60)-(strtotime($date)-strtotime($date_create));
		if($remainedTime > strtotime($exam_result->date_end)-strtotime($date))
		{
		   $remainedTime=(strtotime($exam_result->date_end)-strtotime($date));
		}
		else
		{
		   $remainedTime=$remainedTime;
		}
	}
}



if($remainedTime<1)
{
  $DB->prepare( "UPDATE {$DB->tablePrefix}exam_result SET date_finsh='$date' WHERE id=<bind>$exam_result_id</bind> ");
  $Msg->error("زمان آزمون شما به پایان رسیده است");
  header("location:examResult.php?id=$exam_id&eri=$exam_result_id");
  exit;
}

$minute=($remainedTime-($remainedTime%60))/60;
$second=$remainedTime%60;

$limit="";
$whereQuestion="";
$arrayQuestion=$arrayQuestionAnswered=$arrayAnswer=$arrayDateAnswer=array();
$breaks = array("<br />","<br>","<br/>");
//get last answer user
$answer_user=$DB->query("SELECT exam_question_id,answer,date FROM `{$DB->tablePrefix}student_answer`
                        WHERE exam_result_id=<bind>$exam_result_id</bind>");
foreach($answer_user as $answer)
{

	$arrayAnswer[$answer->exam_question_id]=str_ireplace($breaks, "", $answer->answer);
	
	// if answer date is null not answered by user
	// just for use when $exam_result->number_question>0 in showSingleQuestion.php
	if($answer->date!="")
		$arrayDateAnswer[$answer->exam_question_id]=$answer->date;

	$whereQuestion.=$answer->exam_question_id.",";
	
}unset($answer);

$countAnswer=count($arrayAnswer);

if($exam_result->type==1 && $exam_result->number_question>0 && $countAnswer==0)
{
	$limit=" LIMIT {$exam_result->number_question}";
  $whereQuestion="";
}
elseif($exam_result->type==1 && $exam_result->number_question>0 && $countAnswer>0)
{
	$whereQuestion=rtrim($whereQuestion,",");
	$whereQuestion=" AND {$DB->tablePrefix}exam_question.id IN ($whereQuestion) ";
}
else
{
	$limit="";
  $whereQuestion="";
}
	
if($exam_result->show_question_type=='1' || $exam_result->show_question_type=='3')
  $listExamQuestion=$DB->query("SELECT {$DB->tablePrefix}exam_question.* FROM {$DB->tablePrefix}exam_question 
								WHERE {$DB->tablePrefix}exam_question.exam_id=<bind>$exam_id</bind> $whereQuestion
                ORDER BY {$DB->tablePrefix}exam_question.ordr ASC, {$DB->tablePrefix}exam_question.id ASC $limit ");
else
  $listExamQuestion=$DB->query("SELECT {$DB->tablePrefix}exam_question.* FROM {$DB->tablePrefix}exam_question WHERE
                    {$DB->tablePrefix}exam_question.exam_id=<bind>$exam_id</bind> $whereQuestion
										ORDER BY rand() $limit");

// get answer qustion
$insertStudentAnswer="";
foreach($listExamQuestion as $question)
{
  $arrayQuestion[$question->id]= $question->answer;
	$arrayQuestionAnswered[$question->id]=(isset($arrayAnswer[$question->id]) ? true:false);
	
	//just use when $exam_result->number_question>0
	$insertStudentAnswer.="($exam_result_id,{$question->id},NULL,NULL,NULL),";
	
}unset($question);

if($exam_result->type==1 && $exam_result->number_question>0 && $countAnswer==0)
{
	$insertStudentAnswer=rtrim($insertStudentAnswer,",").";";
	$insertStudentAnswer="INSERT INTO  {$DB->tablePrefix}student_answer
												(`exam_result_id`, `exam_question_id`, `answer`, `score`, `marked`)
												VALUES
												$insertStudentAnswer";
	$DB->prepare($insertStudentAnswer);
}

$count_question=count($listExamQuestion);       
$title=" آزمون ".$exam_name. " | ".$student_name;

if($exam_result->dir==1)
{
	$dir="ltr";
}

$options=array('rtl'=>["الف","ب","ج","د"],"ltr"=>["a","b","c","d"]);

$urlFinishExam='calculateScoreExam.php?exam_id='.$Encryption->encode($exam_id).
                '&exam_result_id='.$Encryption->encode($exam_result_id).
                '&count_question='.$Encryption->encode($count_question).
                '&base_mark='.$Encryption->encode($exam_result->base_mark);
								
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

function checkDirection($string)
{
  if(strstr($string,'dir="ltr"')!="")
    return "ltr";
  elseif(strstr($string,'dir="rtl"')!="")
    return "rtl";
  else
    return "";
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
    <link rel="icon" type="image/png" href="images/iconExam2.png" />
    <link rel="stylesheet" href="lib/fonts/font.css" />
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap-rtl.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="lib/js/jquery.min.js"></script>
  </head>
  
  <body class="<?=$dir?>" >
        <div class="container border fixed-top " style="max-width: 700px;" >
            <div class="row text-center bg-light text-primary align-items-center "  id="headerExam"   >
              <div class="col-3 p-0">
                <?=$student_name?>
              </div>
              <div class="col-6 p-0 small text-dark">
                <?=$exam_name?>
              </div>
              <div class="col-3 p-0">
                <div id="remainedTime" >
                  <?=$minute.":".$second?>
                </div>
              </div>
              <div class='col-12' >
                <p class='small text-danger m-0' >در حین آزمون به هیچ وجه از این صفحه خارج نشوید</p>
                <div class="alert alert-danger p-1" id="msgInternet" style="display: none;" >
                  <div class="h5" > اینترنت دستگاه قطع است</div>
                </div>
              </div>
            </div> 
        </div>
        
        <div class="container" style="max-width: 700px;" id="seprator" > seprator </div>

        <div class="container" id="listQuestion" style="max-width: 700px" >
          <?php
            if($exam_result->show_question_type=="2" || $exam_result->show_question_type=="3" )
              require_once "showSingleQuestion.php";
            else
              require_once "showAllQuestion.php";
          ?>
        </div>
      
      
      <script>
      
      var exam_id='<?=$Encryption->encode($exam_id)?>';
      var exam_result_id='<?=$Encryption->encode($exam_result_id);?>';
      var count_question='<?=$Encryption->encode($count_question);?>';
      var base_mark='<?=$Encryption->encode($exam_result->base_mark);?>';
      var objQuestionAnswered=<?= json_encode($arrayQuestionAnswered);?>;
			
      $(document).on("click"," .btnSetAnswerQuestion , .radioOption ",function(){
        var question_id=$(this).attr("data-id");
        var answer=$("#"+question_id).val();
                  
        if(answer=="" || answer==undefined)
        {
          return false;
        }
        setAnswer(question_id,answer);
      }); 
      
      var remainedTime = <?=@$remainedTime?>;
      var timer = setInterval(function() {
      
        var minutes = (remainedTime-(remainedTime % 60)) / 60;
        var seconds = ((remainedTime % 60));
        document.getElementById("remainedTime").innerHTML =  pad(minutes) + ":" + pad(seconds) ;
        
        remainedTime--; 
        if (remainedTime < 0)
        {
          clearInterval(timer);
          window.location="<?=$urlFinishExam?>";
        }
         
      }, 1000);
      
      function pad(n) {
          return (n < 10) ? ("0" + n) : n;
      }
      
      
      $(".answerInput").keydown(function(){
        var el = this;
        setTimeout(function(){
          el.style.cssText = 'height:auto;';
          el.style.cssText = 'height:' + el.scrollHeight + 'px';
        },0);
      }
      );
      
      $(document).ready(function(){
       $('.answerInput').on("cut copy paste",function(e) {
          e.preventDefault();
       });
      });
      
      function setAnswer(question_id,answer)
      {  
        question_id=question_id.replace(/\D/g,'');
        $("#msg"+question_id).html("<psan class='text-primary' >در حال ثبت پاسخ . . . </span>");
        $.post("setAnswer.php",
              {
               exam_id:exam_id,
               question_id:question_id,
               answer:answer,
               exam_result_id:exam_result_id,
               count_question:count_question
              },
               function(data)
               {
								var objData = JSON.parse(data);
								if(objData.status==1)
								{
									objQuestionAnswered[question_id]=true;
								}
                $("#msg"+question_id).html(objData.msg);
               }
            ).fail(function() 
              {
                  $("#msg"+question_id).html("<span class='text-danger' >خطای رخ داده اتصال اینترنت خود را بررسی کنید</span>");
              }); 
      }
	  
		history.pushState(null, null, location.href);
		window.onpopstate = function () {
			history.go(1);
		};
      </script>
			<style>
        .option img{
            max-height: 100px;
        }        
				.answerInput{
					height: auto;
				}
				.ltr, .ltr .blockQuestion .question ,
				.ltr	.question p,
				.ltr	.question span,
				.ltr	.option p,
				.ltr #remainedTime,
				.ltr #listQuestionNumber .btn,
				.ltr .numberOfQuestion{ 
					font-family: Arial !important;
				}
				.ltr .btnNextQuestion{
					float: right;
				}
				.rtl .btnNextQuestion{
					float: left;
				}
			</style>
			<script src="lib/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
