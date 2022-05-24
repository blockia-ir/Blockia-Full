<?php require_once "blockPageLoader.php";/*must be top of page*/ ?>
<!DOCTYPE html>
<html lang="fa">
  <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>آزمونها</title>
    <link rel="icon" type="image/png" href="../images/iconExam2.png" />
    <link rel="stylesheet" href="../lib/fonts/font.css"  />
    <link rel="stylesheet" href="../lib/font-awesome/css/fontawesome-all.min.css" >
    <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" />
    <link rel="stylesheet" href="../css/panel.css" />
    <script src="../lib/js/jquery.min.js"></script>
    <script src="../lib/js/function.js"></script>
    <link rel="stylesheet" href="../lib/jsPersianCal/js-persian-cal.css">
    <script type="text/javascript" src="../lib/jsPersianCal/js-persian-cal.min.js"></script>
    <link rel="stylesheet" href="../lib/multiple-select/multiple-select.css">
    <script type="text/javascript" src="../lib/multiple-select/multiple-select.js"></script>
    <script src="../lib/jquery-ui/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="../lib/timepicker/include/ui-1.10.0/ui-lightness/jquery-ui-1.10.0.custom.min.css" type="text/css" />
    <link rel="stylesheet" href="../lib/timepicker/jquery.ui.timepicker.css?v=0.3.3" type="text/css" />
    <script type="text/javascript" src="../lib/timepicker/include/ui-1.10.0/jquery.ui.core.min.js"></script>
    <script type="text/javascript" src="../lib/timepicker/jquery.ui.timepicker.js?v=0.3.3"></script>
  </head>
  <body class="rtl" >
	<?php require_once "menu.php"?>
	<div class="container-fluid">
 
<?php
$id="";//define id
$linkExam="";// define linkExam
$dir="rtl";
if(!empty($_GET['type']) )
{
  $type=strip_tags($_GET['type']);
  if($type!=1 && $type!=2)
    $type=2;
}

if(!empty($_GET['id']))
{
	$id=$exam_id=$Encryption->decode(strip_tags($_GET['id']));
	
	if(!is_numeric($id))
		exit("error: exam id is not valid");
	
	$exam=$DB->query("SELECT {$DB->tablePrefix}exam.*,date(date_start) as dateStart,date(date_end)  as dateEnd,
                   date_format(date_start,'%H:%i') as time_start,date_format(date_end,'%H:%i') as time_end  FROM {$DB->tablePrefix}exam WHERE block_user_id='$blockUserId' AND id='$id' ",true);
  
  $linkExam="http://".$_SERVER['HTTP_HOST'].strstr($_SERVER['PHP_SELF'],basename(getcwd()),true)."exam.php?id=".$exam->id;

}

$listClassTeacher=$DB->query("SELECT * FROM {$DB->tablePrefix}class WHERE block_user_id='$blockUserId'");

$countClass=count($listClassTeacher);
if($countClass==0)
{
  $Msg->error("پیش از اینکه آزمونی درج کنید ابتدا باید کلاس های خود را وارد کنید");
  header("location:classForm.php");
  exit;
}

if(@$exam->dir==1)
{
  $dir="ltr";
}

$options=array(
                'rtl'=>["الف","ب","ج","د"],
                "ltr"=>["a","b","c","d"]
                );
?>
<script type="text/javascript" src="../tinymce/tinymce.min.js" ></script>
<script type="text/javascript" src="../tinymce/init_<?=$dir?>.js" ></script>
<div class="row" >
	<div class="col-md-1 col-sm-3 col-3 p-0 pl-2" >
		<span><a href="examPage.php" class="text-primary" data-toggle="tooltip" data-placement="left" title="نمایش لیست آزمونها" ><i class="fas fa-list"></i> آزمونها</a></span>
	</div>
	<?php if($id!=""):?>
		<div class="col-md-1 col-sm-3 col-3 p-0">
					<span><a href="examCopy.php?exam_id=<?=$exam->id?>" class=" mt-3 text-success" data-toggle="tooltip" data-placement="bottom" title="یک آزمون جدید براساس سوالات و تنظیمات این آزمون ایجاد می شود" > <i class='fas fa-copy' ></i> کپی آزمون</a></span>
		</div>
		<div class="col-md-1 col-sm-3 col-3 p-0" >
			<span><a href="examForm.php" class=" mt-3 text-warning" data-toggle="tooltip" data-placement="bottom" title="یک آزمون جدید ایجاد میشود" > <i class='fas fa-file' ></i> آزمون جدید</a></span>
		</div>
    <div class="col-md-1 col-sm-3 col-3 p-0">
      <span><a href="#" class=" mt-3 text-info"   data-toggle="modal" data-target="#modalConvertExam"  > <i class='fas s fa-exchange-alt' ></i> تبدیل </a></span>
    </div>
	<?php endif ?>
</div>

<?php if($id!=""):?>
	<button class="btn btn-info btn-sm" tabindex="0"  id="btnCopyLink" for="linkExam" data-toggle="popover" data-trigger="focus" title="" data-content="لینک آزمون کپی شد" >
		<i class='fas fa-copy' ></i> کپی لینک آزمون</button> 
	<a href="../testExam/exam.php?id=<?=$exam->id?>" target="_blank" class="btn btn-sm btn-secondary" >پیش نمایش آزمون</a>
	<span id="msgCopy" ></span>
	<br/>
	<span>لینک آزمون</span>
	<input type="text" readonly class="readonly border-0 bg-transparent ltr" style="width: 300px; background:#fff;" value="<?=$linkExam?>" />
				<textarea class="" id="linkExam" style="width: 0px;height: 0px;border: 0px;">آزمون:<?=$exam->name?>
				
	<?=$Jdf->jdate('l, j F Y ساعت H:i',strtotime($exam->date_start),0)?> مدت امتحان <?=$exam->duration?> دقیقه
	لینک آزمون
	<?=$linkExam?>
				</textarea>
<?php endif?>

<form action="addUpdateExam.php" method="post" class="" >
	<div class="row" >		
			<input type="hidden" name="id" value="<?=$Encryption->encode(@$exam->id)?>" style="display: none;" />
			<input type="hidden" name="type" value="<?= (empty($exam->type) ? $type:$exam->type)?>" style="display: none;" />
			
			<div class="col-md-3 col-sm-6 p-1 " >
				<label for="name" >نام امتحان</label>
				<input type="text" id="name" name="name" value="<?= @$exam->name ?>" maxlength="255"  class="form-control" autocomplete="off" required />
			</div>
			<div class="col-md-2 col-sm-6 p-1">
				<label for="class" >کلاس</label>
				<select id="class" name="class[]"  class=" multiple" multiple="multiple"  required >
					<?php foreach($listClassTeacher as $class):?>
						<option value="<?=@$class->id?>" <?=(@strstr(@$exam->class,@$class->id)!="" ? "selected" :"" )?> ><?=@$class->name?></option>
					<?php endforeach?>
				</select>
			</div>
			<div class="col-md-1 col-sm-6 p-1" >
				<label for="duration" >مدت امتحان</label>
				<input type="number" id="duration" name="duration" value="<?= isset($exam->duration) ? $exam->duration:10 ?>" min="1"   class="form-control" autocomplete="off" required />
			</div>			
			<div class="col-md-1 col-sm-6 p-1" style="padding:2px" >
				<label for="date_start" >تاریخ شروع</label>
				<div class="input-group" >
					<input type="text" id="date1" name="date1" value="<?= @$Jdf->convertToJalali($exam->dateStart) ?>" maxlength=""  class="form-control p-1" autocomplete="off" required />
					<div class="input-group-prepend">
						<span class="input-group-text p-2" id="btnCalc1"><i class='fas fa-1x fa-calendar-alt' ></i></span>
					</div>	
				</div>
			</div>
			<div class="col-md-1 col-sm-6 p-1" >
				<label for="time1" >زمان شروع </label>
				<input type="text" id="time1" name="time1" value="<?=@$exam->time_start;?>" class=" form-control bg-white " readonly />
			</div>
			<div class="col-md-1 col-sm-6 p-1" >
				<label for="date2" >تاریخ پایان </label>
				<div class="input-group-prepend">
				<input type="text" id="date2" name="date2" value="<?= @$Jdf->convertToJalali($exam->dateEnd) ?>" maxlength=""   class="form-control p-1" autocomplete="off" required />
				<div class="input-group-prepend">
					<span class="input-group-text p-2" id="btnCalc2"><i class='fas fa-1x fa-calendar-alt' ></i></span>
				</div>
				</div>
			</div>
			<div class="col-md-1 col-sm-6 p-1" >
				<label for="time2" >زمان پایان </label>
				<input type="text" id="time2" name="time2" value="<?=@$exam->time_end?>" class="time form-control bg-white" readonly />
			</div>
			<div class="col-md-2 col-sm-6 p-1" >
				<button type="submit" id="btnSetExam" class="btn btn-primary" style="margin-top: 27px;" >ثبت اطلاعات آزمون</button>
				
				<?if($id!=""):?>
				<button type="button" id="btnMoreSetting" class="btn btn-secondary"  title="نمایش تنظیمات بیشتر"  data-toggle="modal" data-target="#modalMoreSettingExam"  style="margin-top: 27px;" ><i class='fas fa-1x fa-cog' ></i></button>
				<?endif?>
				
			</div>
	</div>
</form>

<hr id="beforeQuestion" class="mt-2" />
<?= $Msg->show(); ?>
<?php 
if($id!=""):
	$question_id="";//define question_id 
	if(!empty($_GET['question_id']))
	{
		$question_id=$Encryption->decode(strip_tags($_GET['question_id']));
		if(!is_numeric($question_id))
			exit("error question id not valid");
		
		$exam_question=$DB->query("SELECT * FROM {$DB->tablePrefix}exam_question WHERE block_user_id='$blockUserId' AND id='$question_id' ",true);

	}
	
?>

<form action="addUpdateExamQuestion.php" method="POST"  >
	<div class="row" id="exam_question" >

		<input type="hidden" name="id" id="id" value="<?=$Encryption->encode(@$exam_question->id)?>"  required  >
		<input type="hidden" name="exam_id" id="exam_id" value="<?=$Encryption->encode($exam->id)?>"  required  >
    
		<div class="col-md-4 " >
			<label for="question_type_id" >نوع سوال</label>
			<select id="question_type_id" name="question_type_id" class="form-control" >
				<option value="1" <?=(@$exam_question->question_type_id==1) ? "selected":"" ?> >تستی</option>
				<option value="2" <?=(@$exam_question->question_type_id==2) ? "selected":"" ?> >صحیح و غلط</option>
        <?php if(@$exam->type==2):?>
				<option value="5" <?=(@$exam_question->question_type_id==5) ? "selected":"" ?> >جای خالی، کوتاه پاسخ، تشریحی</option>
        <?php endif ?>
			</select>
		</div>
    <?php if(@$exam->type==2):?>
		<div class="col-md-2 " >
			<label for="score" >نمره سوال</label>
			<input type="number" id="score" name="score" class="form-control"  value="<?=(isset($exam_question->score) ? $exam_question->score+0:1)?>"  step="0.25" required />
		</div>
    <?php endif?>
		<div class="col-md-6" ></div>
    
		<div class="col-md-12" >
			<label for="question" >سوال</label>
			<textarea id="question" name="question"  class="form-control tinymceQuestion" ><?=@$exam_question->question?></textarea>
		</div>
		<div class="col-md-3" id="answerA" >
			<input type="radio" name="answer" id="answer_a" value="a" <?=(@$exam_question->answer=="a") ? "checked":""?> />
			<label for="answer_a" ><?= $options[$dir][0] ?>)</label>
			<textarea id="a" name="a"  class="form-control tinymceAnswer"   ><?= @$exam_question->a ?></textarea>
		</div>
		<div class="col-md-3" id="answerB" >
			<input type="radio" name="answer" id="answer_b" value="b" <?=(@$exam_question->answer=="b") ? "checked":""?> />
			<label for="answer_b" ><?= $options[$dir][1] ?>)</label>
			<textarea id="b" name="b"  class="form-control tinymceAnswer"   /><?= @$exam_question->b ?></textarea>
		</div>
		<div class="col-md-3" id="answerC" >
			<input type="radio" name="answer" id="answer_c" value="c" <?=(@$exam_question->answer=="c") ? "checked":""?> />
			<label for="answer_c" ><?= $options[$dir][2] ?>)</label>
			<textarea id="c" name="c"  class="form-control tinymceAnswer"   /><?= @$exam_question->c ?></textarea>
		</div>
		<div class="col-md-3" id="answerD" >
			<input type="radio" name="answer" id="answer_d" value="d" <?=(@$exam_question->answer=="d") ? "checked":""?> />
			<label for="answer_d" ><?= $options[$dir][3] ?>)</label>
			<textarea id="d" name="d"  class="form-control tinymceAnswer "   /><?= @$exam_question->d ?></textarea>
		</div>
			<div class="col-md-12">
				<input type="submit" id="btnSubmit" value="ثبت سوال" class="btn btn-primary mt-1" />
				<?php if($question_id!=""):?>
				<a href="examForm.php?id=<?=$Encryption->encode($exam_id)?>&#btnSetExam" class="btn btn-warning mt-1" >درج سوال جدید</a>
				<?php endif?>
				<!--<span id="msg" class="text-danger h5" ></span>-->
			</div>		
	</div>
</form>
<div class="row" >
	<div class="col-md-12" style="max-width:100%;overflow: auto;" >
		<?php  require "exam_questionGrid.php"; ?>
	</div>
</div>
<?php endif?>


<script type="text/javascript">
	$(document).ready(function(){
		$('.multiple').multipleSelect();
	});

	 
	var objCal1 = new AMIB.persianCalendar('date1',
	
			{extraInputID: "date1", extraInputFormat: "yyyy-mm-dd",
				onchange: function( pdate ){
					if( pdate ) {
						$("#date2").val($("#date1").val());
					} else {
						return false;
					}
				}
			}
	);
		
	var objCal2 = new AMIB.persianCalendar('date2',
	        {extraInputID: "date2", extraInputFormat: "yyyy-mm-dd",}
	);
	
	$("#btnCalc1").click(function(){
     objCal1.showHidePicker();
    });
	$("#btnCalc2").click(function(){
     objCal2.showHidePicker();
    });
	
( function( $ ) {
	$.fn.persiancalendar = function(extra) {
		return this.each( function( index, element ) {
			id = jQuery(element).attr("id");
			new AMIB.persianCalendar( id, extra );
		} );
	};
})( jQuery );
  
$("#btnSetExam").click(function(){
    var msg="";
    var valid=true;
      
      
    if($("#name").val()=="" )
    {
			msg=("<div class='alert alert-danger' >نام آزمون را وارد کنید</div> ");
			valid=false;
		}
    
    if($("#class").val()=="" )
    {
			msg+=("<div class='alert alert-danger' >کلاس را انتخاب کنید</div> ");
			valid=false;
		}
    
    if($("#duration").val()<1 )
    {
			msg+=("<div class='alert alert-danger' >مدت امتحان را تعیین کنید</div> ");
			valid=false;
		}
    
		if($("#date1").val()=="" )
    {
			msg+=("<div class='alert alert-danger' >تاریخ  شروع آزمون را تعیین کنید</div> ");
			valid=false;
		}
    
    if($("#time1").val()=="" )
    {
			msg+=("<div class='alert alert-danger' >ساعت شروع  آزمون  را تعیین کنید</div> ");
			valid=false;
		}
    
    if($("#date2").val()=="" )
    {
			msg+=("<div class='alert alert-danger' >تاریخ  پایان آزمون را تعیین کنید</div> ");
			valid=false;
		}
		
    if($("#time2").val()=="" )
    {
			msg+=("<div class='alert alert-danger' >ساعت پایان آزمون را تعیین کنید</div> ");
			valid=false;
		}
    
    if(valid)
    {
      var date1=$("#date1").val();
      var time1=$("#time1").val();
      var date2=$("#date2").val();
      var time2=$("#time2").val();
      
      date1=date1.split("-").join("");
      //date1=date1.split("/").join("");
      time1=time1.split(":").join("");
      date1=date1.replace(" ","");
      var date_time1=date1+time1;
      
      date2=date2.split("-").join("");
      //date2=date2.split("/").join("");
      time2=time2.split(":").join("");
      date2=date2.replace(" ","");
      var date_time2=date2+time2;
      
      if(date_time1>=date_time2)
      {
        msg+=("<div class='alert alert-danger' >تاریخ پایان نباید زودتر از تاریخ شروع باشد</div> ");
        valid=false; 
      }
    }
    
		if(valid==false){
			event.preventDefault();
			event.stopPropagation();
			$("#msgModal").modal("show");
		}
		$("#msg").html(msg);    
    
});

$(document).ready(function() {
		$('#time1').timepicker({
			showPeriodLabels: false
		});
		$('#time2').timepicker({
			showPeriodLabels: false
		});
		
	var question_type_id=$("#question_type_id").val();
	
	if(question_type_id==1)
	{
		$("#answerA").show();
		$("#answerB").show();
		$("#answerC").show();
		$("#answerD").show();
		// tinyMCE.get('a').setContent("");
		// tinyMCE.get('b').setContent("");
		// tinyMCE.get('c').setContent("");
		// tinyMCE.get('d').setContent("");
	}
	else if(question_type_id==2)
	{
		$("#answerA").show();
		$("#answerB").show(); 
		$("#answerC").hide();
		$("#answerD").hide();
		//tinyMCE.get('a').setContent("<?= ($dir=="rtl" ? "صحیح":"True" )?>");
		//tinyMCE.get('b').setContent("<?= ($dir=="rtl" ? "غلط":"False" )?>");
	}
	else if(question_type_id==5 || question_type_id==9) 
	{
		$("#answerA").hide();
		$("#answerB").hide();
		$("#answerC").hide();
		$("#answerD").hide();
		tinyMCE.get('a').setContent("");
		tinyMCE.get('b').setContent("");
		tinyMCE.get('c').setContent("");
		tinyMCE.get('d').setContent("");
	}
	
  });

$("#question_type_id").change(function(){
	var question_type_id=$("#question_type_id").val();
	if(question_type_id=="" || question_type_id==undefined)
	{
		return false;
	}
	
	if(question_type_id==1)
	{
		$("#answerA").show();
		$("#answerB").show();
		$("#answerC").show();
		$("#answerD").show();
		tinyMCE.get('a').setContent("");
		tinyMCE.get('b').setContent("");
		tinyMCE.get('c').setContent("");
		tinyMCE.get('d').setContent("");
	}
	else if(question_type_id==2)
	{
		$("#answerA").show();
		$("#answerB").show();
		$("#answerC").hide();
		$("#answerD").hide();
		tinyMCE.get('a').setContent("<?= ($dir=="rtl" ? "صحیح":"True" )?>");
		tinyMCE.get('b').setContent("<?= ($dir=="rtl" ? "غلط":"False" )?>");
	}
	else if(question_type_id==5 || question_type_id==9) 
	{
		$("#answerA").hide();
		$("#answerB").hide();
		$("#answerC").hide();
		$("#answerD").hide();
		tinyMCE.get('a').setContent("");
		tinyMCE.get('b').setContent("");
		tinyMCE.get('c').setContent("");
		tinyMCE.get('d').setContent("");
	}
	
	//if(question_type_id==9)
	//{
	//	$("#score").val(0);
	//	$("#score").prop("readonly",true);
	//}
	//else
	//{
	//	$("#score").val("");
	//	$("#score").prop("readonly",false);
	//}
	
});

$('#btnSubmit').click(function() {
     
		 var question_type_id=$("#question_type_id").val();
		 var score=$("#score").val();
		 var question=tinyMCE.get('question').getContent();
		 var a=tinyMCE.get('a').getContent();
		 var b=tinyMCE.get('b').getContent();
		 var c=tinyMCE.get('c').getContent();
		 var d=tinyMCE.get('d').getContent();
		 var answer=$('input[name=answer]:checked').val();
		 var valid=true;
		 
		 var msg="";
		 //alert(question);
		<?php if(@$exam->type==2):?>
		if((score=="" || score<0.25 || score>5) && question_type_id!=9)
		{
			msg+=("<div class='alert alert-danger' >نمره هر سوال باید بین 0.25 تا  5 نمره باشد</div>");
			valid=false;
		}
		<?php endif?>
    
		if(question==""){
			msg+=("<div class='alert alert-danger' > سوال وارد کنید </div>");
			valid=false;
		}
		
		if(a=="" && (question_type_id==1 || question_type_id==2) ){
			msg+=("<div class='alert alert-danger' > گزینه الف  وارد کنید </div> ");
			valid=false;
		}
		
		if(b=="" && (question_type_id==1 || question_type_id==2) ){
			msg+=("<div class='alert alert-danger' > گزینه ب وارد کنید </div> ");
			valid=false;
		}
		
		if(c=="" && question_type_id==1){
			msg+=("<div class='alert alert-danger' > گزینه ج وارد کنید  </div>");
			valid=false;
		}
		
		if(d=="" && question_type_id==1){
			msg+=("<div class='alert alert-danger' > گزینه د وارد کنید </div> ");
			valid=false;
		}
		
		if((answer=="" || answer==undefined) && (question_type_id==1 || question_type_id==2) ){
			msg+=("<div class='alert alert-danger' > پاسخ سوال انتخاب کنید </div> ");
			valid=false;
		}
		
		if(valid==false){
			event.preventDefault();
			event.stopPropagation();
			$("#msgModal").modal("show");
		}
		$("#msg").html(msg);
});	

$("#btnCopyLink").click(function(){
  var copyText = document.getElementById("linkExam");
  copyText.select();
  copyText.setSelectionRange(0, 99999); /*For mobile devices*/
  document.execCommand("copy");
	$("#msgCopy").html("<b class='text-success' >کپی شد</b>");
});

</script>

<!-- Modal msg -->
<div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <p class=" bold h6" > خطا</p>
            </div>
            <div class="modal-body">
                <div class="modal-body" id="msg" ></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" >بستن</button>
            </div>
        </div>
    </div>
</div>

<?if($id!=""):?>
<!-- Modal More Setting Exam -->
<div class="modal fade " id="modalMoreSettingExam" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <p class="modal-title w-100" id="exampleModalLabel">تنظیمات بیشتر آزمون  <?=$exam->name?></p>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <label for="dir" >چینش سوالات</label>
				<select id="dir" class="form-control" >
					<option value="0" <?=($exam->dir=='0') ? 'selected':''?> >راست به چپ (فارسی)</option>
					<option value="1" <?=($exam->dir=='1') ? 'selected':''?>>چپ به راست (انگلیسی)</option>
				</select>
        
				<label for="show_question_type" >شیوه  نمایش سوالات</label>
				<select id="show_question_type" class="form-control" >
					<option value="0" <?=($exam->show_question_type=='0') ? 'selected':''?> >یکجا تصادفی</option>
					<option value="1" <?=($exam->show_question_type=='1') ? 'selected':''?>> یکجا به ترتیب</option>
					<option value="2" <?=($exam->show_question_type=='2') ? 'selected':''?>>تکی تصادفی</option>
					<option value="3" <?=($exam->show_question_type=='3') ? 'selected':''?>>تکی به ترتیب</option>
				</select>
        
				<label for="show_list_mark" >نمایش لیست نمرات برای دانش آموزان</label>
				<select id="show_list_mark" class="form-control" >
					<option value="0"   <?=($exam->show_list_mark=='0') ? 'selected':''?> >نمایش همه نمرات</option>
					<option value="10"  <?=($exam->show_list_mark=='10') ? 'selected':''?> >عدم نمایش نمرات کمتر از 10</option>
					<option value="12"  <?=($exam->show_list_mark=='12') ? 'selected':''?> >عدم نمایش نمرات کمتر از 12</option>
					<option value="14"  <?=($exam->show_list_mark=='14') ? 'selected':''?> >عدم نمایش نمرات کمتر از 14</option>
					<option value="16"  <?=($exam->show_list_mark=='16') ? 'selected':''?> >عدم نمایش نمرات کمتر از 16</option>
					<option value="21"  <?=($exam->show_list_mark=='21') ? 'selected':''?> >عدم نمایش همه نمرات</option>
          <option value="103" <?=($exam->show_list_mark=='103') ? 'selected':''?> >عدم نمایش همه نمرات و نمره دانش آموز</option>
				</select>
        
        <label for="negative" >نمره منفی <span class="small" >(مخصوص آزمون تستی)</span>
        <?php if($exam->type==2):?>
        <a href="#" class=" mt-3 text-info small" data-toggle="modal" data-target="#modalConvertExam" > <i class='fas s fa-exchange-alt' ></i> تبدیل تشریحی به  تستی </a>
        <?php endif?>
        </label>
        
				<select id="negative" class="form-control" <?= (@$exam->type!=1 ? "readonly":"")?> >
					<option value="0" <?=($exam->negative=='0') ? 'selected':''?> >غیر فعال</option>
          <?php if($exam->type==1):?>
					<option value="1" <?=($exam->negative=='1') ? 'selected':''?>>به ازای هر 1 غلط  یک پاسخ درست حذف شود</option>
					<option value="2" <?=($exam->negative=='2') ? 'selected':''?>>به ازای هر 2 غلط یک پاسخ درست حذف شود</option>
					<option value="3" <?=($exam->negative=='3') ? 'selected':''?>>به ازای هر 3 غلط یک پاسخ درست حذف شود</option>
					<option value="4" <?=($exam->negative=='4') ? 'selected':''?>>به ازای هر 4 غلط یک پاسخ درست حذف شود</option>
					<option value="5" <?=($exam->negative=='5') ? 'selected':''?>>به ازای هر 5 غلط یک پاسخ درست حذف شود</option>
					<option value="6" <?=($exam->negative=='6') ? 'selected':''?>>به ازای هر 6 غلط یک پاسخ درست حذف شود</option>
          <?php endif ?>
				</select>
        
        <label for="number_question" >تعداد نمایش سوالات <span class="small" >(مخصوص آزمون تستی)</span> </label>
        <input type="number" id="number_question" value="<?= (@$exam->number_question>0 ?  @$exam->number_question:"")?>"
        placeholder="مقدار صفر یا خالی به  معنی نمایش همه سوالات است" min="0"  class="form-control" <?= (@$exam->type!=1 ? "readonly":"")?>
        data-toggle="tooltip" data-placement="top"
        title="توسط این خاصیت می توانید تعیین کنید که برای هر دانش آموز سوالات متفاوتی نمایش داده بشود
        توجه  کنید زمانی که این  خاصیت فعال می کنید حالت نمایش سوالات باید صورت تصادفی باشد
        این خاصیت مخصوص آزمون  تستی است مقدار صفر یا خالی به معنی نمایش همه سوالات
        و یا غیر فعال بودن این خاصیت است" />
				
        <label for="base_mark" >نمره آزمون</label>
				<select id="base_mark" class="form-control" >
					<option value="20" <?=($exam->base_mark=='20') ? 'selected':''?> >از 20 نمره</option>
					<option value="19" <?=($exam->base_mark=='19') ? 'selected':''?> >از 19 نمره</option>
					<option value="18" <?=($exam->base_mark=='18') ? 'selected':''?> >از 18 نمره</option>
					<option value="17" <?=($exam->base_mark=='17') ? 'selected':''?> >از 17 نمره</option>
					<option value="16" <?=($exam->base_mark=='16') ? 'selected':''?> >از 16 نمره</option>
					<option value="15" <?=($exam->base_mark=='15') ? 'selected':''?> >از 15 نمره</option>
					<option value="14" <?=($exam->base_mark=='14') ? 'selected':''?> >از 14 نمره</option>
					<option value="13" <?=($exam->base_mark=='13') ? 'selected':''?> >از 13 نمره</option>
					<option value="12" <?=($exam->base_mark=='12') ? 'selected':''?> >از 12 نمره</option>
					<option value="11" <?=($exam->base_mark=='11') ? 'selected':''?> >از 11 نمره</option>
					<option value="10" <?=($exam->base_mark=='10') ? 'selected':''?> >از 10 نمره</option>
					<option value="9"  <?=($exam->base_mark=='9') ? 'selected':''?> >از 9 نمره</option>
					<option value="8"  <?=($exam->base_mark=='8') ? 'selected':''?> >از 8 نمره</option>
					<option value="7"  <?=($exam->base_mark=='7') ? 'selected':''?> >از 7 نمره</option>
					<option value="6"  <?=($exam->base_mark=='6') ? 'selected':''?> >از 6 نمره</option>
					<option value="5"  <?=($exam->base_mark=='5') ? 'selected':''?> >از 5 نمره</option>
				</select>
        
				<label for="private" >حالت ورود به آزمون</label>
				<select id="private" class="form-control" >
					<option value="0" <?=($exam->private=='0') ? 'selected':''?> >آزاد برای همه</option>
					<option value="1" <?=($exam->private=='1') ? 'selected':''?> >ورود فقط با کد</option>
				</select>
				<div id="msgPrivate" class="text-danger small mt-1" ></div>
        
				<label for="show_answer" >نمایش پاسخنامه</label>
				<select id="show_answer" class="form-control" >
					<option value="1" <?=($exam->show_answer=='1') ? 'selected':''?> >فعال</option>
					<option value="0" <?=($exam->show_answer=='0') ? 'selected':''?> >غیر فعال</option>
				</select>
        
        <label for="check_ip" >چک کردن براساس آی پی</label>
				<select id="check_ip" class="form-control" >
					<option value="1" <?=($exam->check_ip=='1') ? 'selected':''?> >فعال</option>
					<option value="0" <?=($exam->check_ip=='0') ? 'selected':''?> >غیرفعال</option>
				</select>
				<div id="msgCheckIP" class="text-danger small mt-1" ></div>
        
        <label for="check_cookie" >چک کردن براساس مرورگر</label>
				<select id="check_cookie" class="form-control" >
					<option value="1" <?=($exam->check_cookie=='1') ? 'selected':''?> >فعال</option>
					<option value="0" <?=($exam->check_cookie=='0') ? 'selected':''?> >غیرفعال</option>
				</select>
				
      </div>
      <div class="modal-footer">
				<div id="msgExamMoreSetting" class="mt-2" ></div>
        <button type="button" class="btn btn-primary"  id="btnSetExamMoreSetting" >ذخیره تنطیمات</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
      </div>
    </div>
  </div>
</div>

<!-- modalConvertExam -->
<div class=" rtl modal fade" id="modalConvertExam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <div class=" bold big" >تبدیل آزمون</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                  <?php if($exam->type==1):?>
                    <div class="blod big" >
                      آیا مایل هستید آزمون تستی به آزمون  تشریحی تبدیل شود؟
                      <br/>
                      با این کار قادر خواهید بود سوالات تشریحی در این آزمون درج  کنید
                      <br/>
                      بارم هر سوال را می توانید از طریق کادر زیر تعیین کنید
                      <input type="number" id="convertScore" name="convertScore" class="form-control big" value="1" step="0.25" />
                      
                    </div>
                  <?php else: ?>
                    <div class="big" >
                      آیا مایل هستید آزمون  تشریحی به آزمون تستی تبدیل شود؟
                      <br/>
                      <ul>
                        <li>
                          با این  کار بارم سوالات  به  صورت خودکار تعیین خواهد شد
                        </li>
                        <li>
                           می توانید برای آزمون نمره منفی در نظر بگیرید
                        </li>
                        <li>
                          می توانید تعیین کنید که از بین سوالات  آزمون  چند سوال به  صورت تصادفی برای هر دانش آموز نمایش داده شود
                        </li>
                      </ul>
                    </div>
                    <div class="alert alert-warning" >
                                        توجه کنید پس از تبدیل آزمون  تستی به تشریحی بارم  و سوالات تشریحی 
                      این آزمون  حذف خواهند شد
                    </div>
                  <?php endif ?>
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn  btn-secondary" data-dismiss="modal" >پشیمون شدم</button>
                <button type="button"  class="btn btn-info" id="btnConvertExam"  > <i class='fas s fa-exchange-alt' ></i>  تبدیل کن</button>
            </div>
        </div>
    </div>
</div>
<script >
  
  $("#btnConvertExam").click(function(){
    var exam_id='<?=$Encryption->encode(@$exam_id)?>';
    var convertScore=$("#convertScore").val();
    
    if(exam_id==""|| exam_id==undefined)
      return false;
    
    if(convertScore==undefined)
      convertScore="";
      
    window.location.replace("convertExam.php?exam_id="+exam_id+"&score="+convertScore);
      
  });
  
  $(function () {
      $("#check_ip")
          .tooltip({ placement:"top",title: 'در صورتیکه این خاصیت فعال باشد با هر آی پی اینترنت فقط یک  نفر\
                   می تواند در آزمون شرکت کند. توجه کنید که حدود 2 درصد احتمال دارد آی پی ها تکراری باشند بنابراین توصیه  میشود\
                  در آزمون های که زیر 50 نفر شرکت می کنند این خاصیت  فعال کنید.' })
          .blur(function () {
              $(this).popover('hide');
          });
    $("#check_cookie")
          .tooltip({ placement:"top",title: 'در صورتیکه این خاصیت فعال باشد با هر مرورگر فقط یک نفر\
                   می تواند در آزمون شرکت کند. توصیه میشود این  خاصیت فعال باشد  \
و فقط در مواردی که مایل هستید دانش آموز بیش از یکبار از طریق یک مرورگر در آزمون  شرکت کند این خاصیت را غیرفعال کنید' })
          .blur(function () {
              $(this).popover('hide');
          });
          
      $("#convertExam")
          .tooltip({ placement:"bottom",title: 'این آزمون  تبدیل به آزمون <?=($exam->type==1 ? 'تشریحی':'تستی')?> میشود' })
          .blur(function () {
              $(this).popover('hide');
          });
      // $("#btnMoreSetting")
          // .tooltip({ placement:"bottom",title: 'تنظیمات بیشتر آزمون' })
          // .blur(function () {
              // $(this).popover('hide');
      // });
  });
  
	$("#private").change(function(){
		var privat=$("#private").val();
		if(privat==1){
			$("#msgPrivate").html("توجه کنید در صورتیکه  دانش آموزان  را وارد کرده اید از گزینه `ورود فقط با کد` استفاده کنید. در غیر این  صورت امکان ورود به آزمون برای دانش آموزان وجود نخواهد داشت.");
		}
		else
		{
			$("#msgPrivate").html("");
		}
		
	});
  
  
	/*
	$("#check_ip").change(function(){
		var check_ip=$("#check_ip").val();
		if(check_ip==0){
			$("#msgCheckIP").html("در صورتیکه این این گزینه غیر فعال باشند بررسی امتحان داده شده بر حسپ آی پی امکان پذیر نخواهد بود در نتیجه دانش آموزان می تواند بیش از یک بار امتحان دهند.");
		}
		else
		{
			$("#msgCheckIP").html("");
		}
		
	});
  */
  var tempDir='<?=@$exam->dir?>';
	$("#btnSetExamMoreSetting").click(function(){
		var exam_id='<?=$Encryption->encode(@$exam->id)?>';
		var dir=$("#dir").val();
		var show_question_type=$("#show_question_type").val();
		var show_list_mark=$("#show_list_mark").val();
		var base_mark=$("#base_mark").val();
		var privat=$("#private").val();
		var check_ip=$("#check_ip").val();
		var check_cookie=$("#check_cookie").val();
		var show_answer=$("#show_answer").val();
		var number_question=$("#number_question").val();
		var negative=$("#negative").val();
		
		$("#msgExamMoreSetting").html('در حال ذخیره سازی . . . ');
		$.post("ajaxSetExamMoreSetting.php",
					 {
						exam_id:exam_id,
						dir:dir,
						show_question_type:show_question_type,
						show_list_mark:show_list_mark,
						base_mark:base_mark,
						privat:privat,
						check_ip:check_ip,
						check_cookie:check_cookie,
						show_answer:show_answer,
						number_question:number_question,
						negative:negative
						},
						function(data)
						{
							$("#msgExamMoreSetting").html(data);
						}
							
					).done(function() 
						{
							if(tempDir!=dir)
							{
							  window.location.reload();
							}
						}
					);
    
    
	});
</script>
<?endif?>

<style>
.ui-widget-header {
    border: 1px solid #007bff;
    background: #007bff;
    color: #fff;
    font-weight: bold;
}
.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active {
    border: 1px solid #007fc3;
    background: #007bff52;
}
.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default
{
	font-weight: normal;
}
</style>
	</div>
  </div>
<?php require_once "footer.php"?>
<body>
</html>
