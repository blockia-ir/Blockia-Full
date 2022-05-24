<?php ob_start();session_start();
require_once "../lib/php/autoload.php";
require_once "../lib/captcha/captcha.php";
$_setting=parse_ini_file("settingAdmin.ini");
if(@$_setting['register_user']!=false)
{
    exit("خطا: امکان ثبت نام کاربر وجود ندارد");
}
$Msg=new Msg();
$_SESSION['captcha'] = captcha();
?>
<!doctype html>
<html lang="fa">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../images/favicon.ico">
    <title>فرم ثبت نام در نرم افزار آزمون آنلاین</title>
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
            <b>توجه کنید این فرم ثبت نام مخصوص معلم است. دانش آموز نیازی به ثبت نام ندارد.</b>
            <br/>
            <b>در صورتیکه  قبلا ثبت نام کردید لطفا از طریق  <a href="login.php" >صفحه ورود</a> وارد سایت بشوید. نیازی به  ثبت نام مجدد نیست </b>
          </div>
          <div class="card" >
            <div class="card-header" >
              <div class="card-title" >فرم  ثبت نام معلم در آزمون آنلاین</div>
            </div>
            <div class="card-body" >
              <?= $Msg->show() ?>
              <form action="registerUser.php" method="post" class="needs-validation"  novalidate>
                <div class="form-group">
                  <label for="name">نام دبیر:</label>
                  <input type="text" class="form-control" id="name" value="<?=@$_COOKIE['name']?>" name="name" maxlength="50" required>
                  <div class="invalid-feedback">لطفا  نام خود را وارد کنید</div>
                </div>
                <div class="form-group">
                  <label for="username">نام کاربری:</label>
                  <input type="text" class="form-control" id="username" value="<?=@$_COOKIE['username']?>" name="username" maxlength="50" placeholder="فقط از حروف و اعداد انگلیسی استفاده کنید بدون فاصله" required>
                  <div class="invalid-feedback" >لطفا  نام  کاربری را وارد کنید</div>
                  <span id="msgUsername" class="text-danger"></span>
                </div>
                <div class="form-group">
                  <label for="password">رمز عبور:</label>
                  <input type="number" class="form-control" id="password"  name="password" min="100000"  placeholder="فقط عدد وارد کنید حداقل 6 رقم" required>
                  <div class="invalid-feedback">لطفا رمز عبور را به طور صحیح وارد کنید حداقل 6 رقم</div>
                </div>
				<div class="form-group">
                  <label for="email">ایمیل: (اختیاری)</label>
                  <input type="email" class="form-control" id="email" value="<?=@$_COOKIE['email']?>" name="email" maxlength="50" placeholder="ایمیل اختیاری" >
                  <div class="invalid-feedback" >لطفا ایمیل را به طور صحیح وارد کنید</div>
                  <span id="msgUsername" class="text-danger"></span>
                </div>
                <div class="form-group">
                  <label for="password">کد امنیتی زیر را وارد کنید</label>
                  <input type="text" class="form-control" id="captcha"  name="captcha"  required>
                  <div class="invalid-feedback">لطفا عبارت امنیتی را وارد کنید</div>
                  <br/>
                  <?='<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">';?>
                </div>
                <button type="submit" class="btn btn-primary">ثبت اطلاعات</button>
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
    
     $("#username").change(function(){
          var val=$("#username").val();
          $("#username").val(val.replace(/ /g,''));
     });
     
    $("#username").keypress(function(event){
          var val=$("#username").val();
          $("#username").val(val.replace(/ /g,''));
          var ew = event.which;
          if(ew == 32)
          {
              $("#msgUsername").html("");
              return true;
          }
          if(48 <= ew && ew <= 57)
          {
              $("#msgUsername").html("");
              return true;
          }
          if(65 <= ew && ew <= 90)
          {
              $("#msgUsername").html("");
              return true;
          }
          if(97 <= ew && ew <= 122)
          {
              $("#msgUsername").html("");
              return true;
          }
          $("#msgUsername").html("فقط از حروف و عدد انگلیسی استفاده کنید");
          return false;
      });
	  
	    $(function () {
			$("#email")
			  .tooltip({ placement:"top",title: 'در صورتیکه ایمیل را وارد کنید امکان بازیابی رمز عبور وجود خواهد داشت' })
			  .blur(function () {
				  $(this).popover('hide');
			  });
		});
    </script>
    <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

