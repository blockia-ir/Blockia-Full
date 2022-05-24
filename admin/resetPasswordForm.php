<?php ob_start();session_start();
require_once "../lib/php/autoload.php";
require_once "../lib/captcha/captcha.php";

$_SESSION['captcha'] = captcha();
$DB=new DB();
$Msg=new Msg();
$Encryption=new Encryption();

if(empty($_GET['data']))
	exit("error: data not set");

$data=$Encryption->decode(strip_tags($_GET['data']));

if(empty($data))
	exit("error data not valid");

$_SESSION['data']=$_GET['data'];

$data=explode(",",$data);
$block_user_id=$data[0];
$strtime=$data[1];

if(strtotime("now")>$strtime)
	exit("خطا: لینک بازیابی منقضی شده");

$block_user=$DB->query("SELECT * FROM {$DB->tablePrefix}block_users WHERE id=<bind>$block_user_id</bind>",true);
if(!isset($block_user->id))
{
	exit("خطا: کاربر مورد نظر یافت نشد");
} 
?>
<!doctype html>
<html lang="fa">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../images/favicon.ico">
    <title>فرم تغییر رمز عبور</title>
    <link rel="stylesheet" type="text/css" href="../lib/fonts/font.css" />
    <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" >
    <link rel="stylesheet" type="text/css" href="../css/login.css" />
    <script src="../lib/js/jquery.min.js" ></script>
  </head>

  <body class=" rtl">
    <div class="container-fluid">
      <div class="row mt-2" >
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <div class="card" >
            <div class="card-header" >
              <div class="card-title" >فرم بازیابی رمز عبور کاربر: <?= $block_user->name ?></div>
            </div>
            <div class="card-body" >
              <?= $Msg->show() ?>
              <form action="resetPassword.php" method="post" class="needs-validation"  novalidate>
				
                <div class="form-group">
                  <label for="password">رمز عبور جدید: </label>
                  <input type="text" class="form-control" id="password" name="password" maxlength="50" placeholder="" required>
                  <div class="invalid-feedback" >لطفا  رمز عبور را وارد کنید</div>
                </div>

                <div class="form-group">
                  <label for="captcha">کد امنیتی زیر را وارد کنید</label>
                  <input type="text" class="form-control" id="captcha"  name="captcha"  required>
                  <div class="invalid-feedback">لطفا عبارت امنیتی را وارد کنید</div>
                  <br/>
                  <?='<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">';?>
                </div>
                <button type="submit" id="btnSubmit" class="btn btn-primary">ثبت اطلاعات</button>
				<span id="msgResult" ></span>
              </form>
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
    </script>
    <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

