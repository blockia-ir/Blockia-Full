<?php require_once "pageLoader.php";

$ip=$Func->getIP();

unset($_SESSION['exam_result_id']);

$id=$exam_id=strip_tags(@$_REQUEST['id']);
if(!is_numeric($id)){
    exit("خطا: آزمون نامعتبر است");
}
if(@count($_GET)>1)
	exit("error: link  not correct");

$exam=$DB->query("SELECT {$DB->tablePrefix}exam.*,
  (SELECT COUNT(0) FROM {$DB->tablePrefix}exam_question WHERE {$DB->tablePrefix}exam_question.exam_id=<bind>$exam_id</bind> AND {$DB->tablePrefix}exam_question.question_type_id!=9) as countQuestion,
  (SELECT COUNT(0) FROM {$DB->tablePrefix}exam_result WHERE {$DB->tablePrefix}exam_result.exam_id=<bind>$exam_id</bind>) as countExamResult
  FROM {$DB->tablePrefix}exam WHERE id=<bind>$exam_id</bind>",true);

if(!isset($exam->id)){
     exit("خطا: آزمون یافت نشد");
}
/*
if($exam->countExamResult>1000) 
{
	exit("خطا ظرفیت این آزمون پر شده است");
}
*/

$tdate_start=strtotime($exam->date_start);
$tdate_end=strtotime($exam->date_end);
$tdate_now=strtotime("now");

$jdate_start=$Jdf->jdate('l, j F Y ساعت H:i',$tdate_start,0);
$jdate_end=$Jdf->jdate('l, j F Y ساعت H:i',$tdate_end,0);
$jdate_now=$Jdf->jdate('l, j F Y ساعت H:i');

$remainedTime= $tdate_start-$tdate_now;

// when exam date end 
if($tdate_now>$tdate_end)
{
  $Msg->error("زمان آزمون {$exam->name} به پایان رسیده است");
  header("location:examResult.php?id=$exam_id");
  exit;
}
if($exam->number_question==0 || $exam->number_question=="")
	$countQuestion=$exam->countQuestion;
else
	$countQuestion=$exam->number_question;

if($tdate_start<=$tdate_now  && $exam->check_cookie==1 && isset($_COOKIE[$exam_id]) && is_numeric($_COOKIE[$exam_id]))
{
  $exam_result_id=$_COOKIE[$exam_id];
  $exam_result=$DB->query("SELECT {$DB->tablePrefix}exam_result.* FROM {$DB->tablePrefix}exam_result 
							WHERE {$DB->tablePrefix}exam_result.id=<bind>$exam_result_id</bind>",true);
  if(isset($exam_result->id))
  {
    //if student finish exam goto result page
    if(@$exam_result->date_finsh!="") 
    {
      header("location:examResult.php?id=$exam_id&eri={$exam_result->id}");
      exit;
    }
    else
    {
      setcookie($exam_id,$exam_result->id,time()+(365*24*60*60),"/");
      $exam_result_id=$Encryption->encode($exam_result->id);
      header("location:startExam.php?id=$exam_result_id");
      exit;
    }
  }
  else
  {
	unset($_COOKIE[$exam_id]);
    @setcookie($exam_id,null,time()-(365*24*60*60),"/");
  }
}
else
{
	unset($_COOKIE[$exam_id]);
	@setcookie($exam_id,null,time()-(365*24*60*60),"/");
}

if($tdate_start<=$tdate_now && $exam->check_ip==1)
{
  $exam_result=$DB->query("SELECT {$DB->tablePrefix}exam_result.* FROM {$DB->tablePrefix}exam_result
                          WHERE {$DB->tablePrefix}exam_result.exam_id=<bind>$exam_id</bind>
                          AND {$DB->tablePrefix}exam_result.ip=<bind>$ip</bind>",true);
  //در صورتیکه کاربر با همین آیپی در این  آزمون شرکت کرده باشد
  if(isset($exam_result->id))
  {
    if(@$exam_result->date_finsh!="") 
    {
      header("location:examResult.php?id=$exam_id&eri={$exam_result->id}");
      exit;
    }
    else
    {
      setcookie($exam_id,$exam_result->id,time()+(365*24*60*60),"/");
      $exam_result_id=$Encryption->encode($exam_result->id);
      header("location:startExam.php?id=$exam_result_id");
      exit;
    }
  }
}

$msgNegative="";
if($exam->negative>0)
{
  $msgNegative="<div class='alert alert-danger text-center' > ** توجه کنید این آزمون نمره منفی دارد ** </div>";
}

$remainedTime= $tdate_start-$tdate_now;
$description=$exam->name.'، تاریخ شروع: '.$jdate_start.'، تعداد سوالات '.$countQuestion.'، زمان پاسخ گویی: '.$exam->duration.' دقیقه ';

?>
<!DOCTYPE html>
<html lang="fa">
  <head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#F7F7F7" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $exam->name ?></title>
    <meta name="description" content="<?= $description ?>  " />
    <?php if($tdate_start>=$tdate_now ):?>
    <meta http-equiv="refresh" content="120" >
    <?php endif?>
    <link rel="icon" type="image/png" href="images/iconExam2.png" />
    <link rel="stylesheet" href="lib/fonts/font.css" />
    <link rel="stylesheet" href="lib/font-awesome/css/fontawesome-all.min.css"  />
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap-rtl.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="lib/js/jquery.min.js"></script>
  </head>

  <body class="rtl" >
    
        <div class="container " >
               <div class="row mt-5" >
                <div class="col-md-3"></div>
                <div class="col-md-6 text-center">
                    <div class="card" >
                        <div class="card-body" >
                          <?=$Msg->show()?>
                        <?php if($tdate_start>=$tdate_now ):?>
                            <div class="text-primary" ><?=$exam->name?></div>
                            <div class="" ><span>زمان شروع آزمون:</span> <br/><span class="text-info" ><?=$jdate_start?></span></div>
                            <div class="" ><span>زمان پایان آزمون:</span> <br/><span class="text-danger" ><?=$jdate_end?></span></div>
                            <div class="" ><span>تعداد سوالات:</span> <br/><span class="" ><?=$countQuestion?> سوال</span></div>
                            <div class="" ><span>مدت زمان پاسخ گویی:</span> <br/><span class="" ><?=$exam->duration?> دقیقه</span></div>
                            <div class="alert alert-warning" >
                                الان <?=$jdate_now?> است  بنابراین
                                زمان آزمون فرا نرسیده است
                            </div>
                            <div id="remainedTime" class="h3" ></div>
                        <?php elseif($tdate_now>=$tdate_end):?>
                        <?php
                          header("location:examResult.php?id=$exam_id");
                          exit;
                        ?>
                            <div class="" ><?=$exam->name?></div>
                            <div class="alert alert-warning" >
                                زمان این آزمون به پایان رسیده است
                            </div>
                            
                        <?php else: // هنگامی که زمان آزمون فرا رسیده است ?>
                         
                          <div  >آزمون: <span class="text-primary"><?=$exam->name?></span></div>
                          
                            <form action="prepareExam.php?exam_id=<?=$Encryption->encode($exam_id)?>" method="POST"  class="needs-validation text-left" novalidate >
                              <?php if($exam->private==0):?> 
                              <div class="form-group">
                                <label for="name">نام کامل:</label>
                                <input type="text" class="form-control" id="name" placeholder="نام کامل خود را وارد کنید" name="name" required >
                                <div class="invalid-feedback">لطفا نام خود را به طور صحیح وارد کنید</div>
                              </div>
                              <div class="form-group">
                                <?php
                                      $listExamClass=$DB->query("select {$DB->tablePrefix}exam_class.id,{$DB->tablePrefix}class.name 
                                        from {$DB->tablePrefix}exam_class
                                        join {$DB->tablePrefix}class on {$DB->tablePrefix}exam_class.class_id={$DB->tablePrefix}class.id
                                        WHERE {$DB->tablePrefix}exam_class.exam_id=<bind>$exam_id</bind>");
                                ?>
                                <label for="exam_class_id">کلاس:</label>
                                <select class="form-control" id="exam_class_id"  name="exam_class_id" required >
                                  <option value="" >کلاس خود را انتخاب کنید</option>
                                  <?php foreach($listExamClass as $examClass):?>
                                  <option value="<?=$examClass->id?>" ><?=$examClass->name?></option>
                                  <?php endforeach ?>
                                </select>
                                <div class="invalid-feedback">لطفا کلاس خود را انتخاب کنید</div>
                              </div>
                              <?php elseif($exam->private==1):?>
                              <div class="alert alert-info mt-3 text-center" >
                                برای ورود به این آزمون  باید کد ورود داشته باشید.
                                کد ورود را می توانید از معلم خود بگیرید
                              </div>
                              <div class="form-group">
                                <label for="code">کد ورود:</label>
                                <input type="text" class="form-control" id="code" placeholder="کد ورود خود را وارد کنید" name="code" required >
                                <div class="invalid-feedback">لطفا کد ورود را وارد کنید</div>
                              </div>
                              <?php endif?>
                              <button type="submit" class="btn btn-primary">شروع آزمون</button>
                              <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#examHelp">راهنمای آزمون</button>
                               <div class="small mt-1" ><?=$Jdf->jdate("Y-n-j H:i:s")?></div>
                               <?=$msgNegative?>
                            </form>
							<!--
							<div class="alert alert-danger mt-3 text-left" >
                              درحال بروزرسانی این قسمت هستیم احتمال دارد با خطا های مواجه بشوید
                            </div> 
							-->
							
                            
                          
                        <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div> 
        </div>
<script>
// Disable form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

<?php if($tdate_start>=$tdate_now ):?>
var remainedTime =<?=@$remainedTime?>;
var timer = setInterval(function() {

  //var hours = Math.floor((remainedTime % ( 60 * 60 * 24)) / ( 60 * 60));
  //var minutes = 0;//(remainedTime-(remainedTime % 60)) / 60;
  //var seconds = 0;//((remainedTime % 60));
  
  var days = Math.floor(remainedTime / ( 60 * 60 * 24));
  var hours = Math.floor((remainedTime % ( 60 * 60 * 24)) / ( 60 * 60));
  var minutes = Math.floor((remainedTime % ( 60 * 60)) / ( 60));
  var seconds = Math.floor((remainedTime % ( 60)) );
  document.getElementById("remainedTime").innerHTML = show(days)+show(hours)+show(minutes) + pad(seconds) ;
  
  remainedTime--; 
  if (remainedTime < 0) {
    clearInterval(timer);
    window.location.reload();
  }
   
}, 1000);

function show(time){
  time=pad(time);
  if(time==0)
    return "";
  
  return time+":";
}

function pad(n) {
  return (n < 10) ? ("0" + n) : n;
}
<?php endif ?>
</script>
      <div class="modal fade" id="examHelp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered  modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <p class=" bold big" >راهنمای آزمون</p>
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                              پیش از آزمون به موارد زیر توجه کنید
                              <ol class="p-0" >
                                  <li  class="list-group-item list-group-item-light" >
                                    پاسخ سوال زمانی ثبت خواهد شد که عبارت 
                                    <span class="text-success" >پاسخ درج  شد</span>
                                     و یا 
                                    <span class="text-success" >پاسخ ویرایش شد </span>
                                   با رنگ سبز نمایش داده بشود در غیر این  صورت  پاسخ سوال ثبت نشده است
                                    <br/>
                                    <img src="help/examHelp01.png" class="mw-100" />
                                  </li>
                                  <li  class="list-group-item list-group-item-light" >
                                    برای ثبت پاسخ  سوالات تشریحی حتما باید روی گزینه  ثبت  پاسخ  هر سوال کلیک کنید
                                    در غیر این  صورت  پاسخ  آن سوال ثبت نخواهد شد
                                    <br/>
                                    <img src="help/examHelp02.png" class="mw-100" />
                                  </li>
                                  <li  class="list-group-item list-group-item-light">
                                    پس از ورود به آزمون  به هیچ  وجه از آزمون خارج نشوید
                                    در غیر این صورت احتمال دارد با خطا های  مواجه  بشوید یا پاسخ برخی از سوالات شما ثبت نشود
                                  </li>
                                  <li  class="list-group-item list-group-item-light">
                                    درصورتیکه VPN و یا فیلترشکن  روشن دارید. خاموش کنید
                                  </li>
                                  <li  class="list-group-item list-group-item-light">
                                    ترجیحا از مرورگرهای کروم یا فایرفاکس استفاده کنید
                                  </li>
                              </ol>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" >بستن</button>
                  </div>
              </div>
          </div>
      </div>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
