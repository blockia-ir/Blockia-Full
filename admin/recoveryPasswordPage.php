<?php ob_start();session_start();
require_once "../lib/php/autoload.php";
require_once "../lib/captcha/captcha.php";
$Msg=new Msg();
$_SESSION['captcha'] = captcha();
?>
<!doctype html>
<html lang="fa">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../images/favicon.ico">
    <title>فرم بازیابی رمز عبور</title>
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
          <div class="alert alert-warning" >
            <b>توجه کنید در صورتیکه قبلا ایمیل خود را ثبت کرده اید امکان بازیابی رمز عبور وجود خواهد داشت</b>
          </div>
          <div class="card" >
            <div class="card-header" >
              <div class="card-title" >فرم بازیابی رمز عبور</div>
            </div>
            <div class="card-body" >
              <?= $Msg->show() ?>
              <form id="formRecoveryEmail" action="#" method="post" class="needs-validation"  novalidate>

                <div class="form-group">
                  <label for="username">ایمیل: </label>
                  <input type="email" class="form-control" id="email" value="" name="email" maxlength="50" placeholder="" required>
                  <div class="invalid-feedback" >لطفا  نام  کاربری را وارد کنید</div>
                  <span id="msgEmail" class="text-danger"></span>
                </div>

                <div class="form-group">
                  <label for="password">کد امنیتی زیر را وارد کنید</label>
                  <input type="text" class="form-control" id="captcha"  name="captcha"  required>
                  <div class="invalid-feedback">لطفا عبارت امنیتی را وارد کنید</div>
                  <br/>
                  <?='<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">';?>
                </div>
                <button type="button" id="btnSubmit" class="btn btn-primary">ارسال ایمیل بازیابی</button>
              </form>
              <span id="msgResult" ></span>
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
    
     $("#email").change(function()
        { 
            var val=$("#email").val();
            $("#email").val(val.replace(/ /g,''));
        }
     );
	
	$("#btnSubmit").click(function(){
		var email=$("#email").val();
		var captcha=$("#captcha").val();
		
		if(email=="")
		{
			return false;
		}
		$("#msgResult").html("در حال بررسی . . . ");
		$.post("ajaxSendEmailRecoveryPassword.php",
				{
					email:email,
					captcha:captcha
				},function(data)
				{
					$("#msgResult").html(data);
				}
			);
		
		
	});
     
    </script>
    <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

