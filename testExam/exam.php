<?php require_once "pageLoader.php";

$ip=$Func->getIP();

unset($_SESSION['exam_result_id']);

$id=$exam_id=strip_tags(@$_REQUEST['id']);
if(!is_numeric($id)){
    exit("خطا: آزمون نامعتبر است");
}

$exam=$DB->query("SELECT {$DB->tablePrefix}exam.*,
  (SELECT COUNT(0) FROM {$DB->tablePrefix}exam_question WHERE {$DB->tablePrefix}exam_question.exam_id=<bind>$exam_id</bind> AND {$DB->tablePrefix}exam_question.question_type_id!=9) as countQuestion,
  (SELECT COUNT(0) FROM {$DB->tablePrefix}exam_result WHERE {$DB->tablePrefix}exam_result.exam_id=<bind>$exam_id</bind>) as countExamResult
  FROM {$DB->tablePrefix}exam WHERE id=<bind>$exam_id</bind> AND block_user_id='$block_user_id' ",true);

if(!isset($exam->id)){
     exit("خطا: آزمون یافت نشد");
}

if($exam->countExamResult>1000) 
{
	exit("خطا ظرفیت این آزمون پر شده است");
}

$tdate_start=strtotime($exam->date_start);
$tdate_end=strtotime($exam->date_end);
$tdate_now=strtotime("now");

$jdate_start=$Jdf->jdate('l, j F Y ساعت H:i',$tdate_start,0);
$jdate_end=$Jdf->jdate('l, j F Y ساعت H:i',$tdate_end,0);
$jdate_now=$Jdf->jdate('l, j F Y ساعت H:i');

$remainedTime= $tdate_start-$tdate_now;


$description=' شما در حال تست آزمون هستید محدودیت ها برای شما اعمال نخواهد شد <br/> این  لینک  مخصوص شما است برای  دانش آموز ارسال نکنید';

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
    <link rel="stylesheet" href="../lib/fonts/font.css" />
    <link rel="stylesheet" href="../lib/font-awesome/css/fontawesome-all.min.css"  />
    <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <script src="../lib/js/jquery.min.js"></script>
  </head>

  <body class="rtl" >
    
        <div class="container " >
               <div class="row mt-5" >
                <div class="col-md-3"></div>
                <div class="col-md-6 text-center">
                    <div class="card" >
                        <div class="card-body" >
                          <?=$Msg->show()?>
                        <?php if(true): // هنگامی که زمان آزمون فرا رسیده است ?>
                         
                          <div> <span class="text-danger" >پیش نمایش آزمون </span>: <span class="text-primary"><?=$exam->name?></span></div>
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
                                کد ورود یکی از دانش آموزان را وارد کنید
                              </div>
                              <div class="form-group">
                                <label for="code">کد ورود:</label>
                                <input type="text" class="form-control" id="code" placeholder="کد ورود خود را وارد کنید" name="code" required >
                                <div class="invalid-feedback">لطفا کد ورود را وارد کنید</div>
                              </div>
                              <?php endif?>
                              <button type="submit" class="btn btn-primary">شروع آزمون</button>
                               <span class="small" ><?=$Jdf->now()?></span>
                            </form>
                          
                        <?php endif ?>
                        <div class="alert alert-warning mt-2" ><?=$description?></div>
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
    <!--<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>-->
  </body>

</html>
