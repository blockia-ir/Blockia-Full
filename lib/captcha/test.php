<?php session_start();
//require_once "../lib/php/Msg.php";
//$Msg=new Msg();
include("simple-php-captcha.php");
$_SESSION = array();
$_SESSION['captcha'] = simple_php_captcha();
?>
<!doctype html>
<html lang="fa">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../images/favicon.ico">
    <title>فرم ثبت نام در نرم افزار آزمون آنلاین</title>
    <link rel="stylesheet" type="text/css" href="../fonts/font.css" />
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-rtl.min.css" >
    <link rel="stylesheet" type="text/css" href="../css/login.css" />
    <script src="../js/jquery.min.js" ></script>
  </head>

  <body class=" rtl">
    <div class="container-fluid">
      <div class="row mt-2" >
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <div class="alert alert-warning" >
            <b>توجه کنید این فرم ثبت نام مخصوص معلم است. دانش آموز نیازی به ثبت نام ندارد.</b>
          </div>
          <div class="card" >
            <div class="card-header" >
              <div class="card-title" >فرم  ثبت نام معلم در آزمون آنلاین</div>
            </div>
            <div class="card-body" >
              <?//= $Msg->show() ?>
              <form action="registerUser.php" method="post" class="needs-validation"  novalidate>
                <div class="form-group">
                  <label for="name">نام دبیر</label>
                  <input type="text" class="form-control" id="name" value="<?=@$_COOKIE['name']?>" name="name" maxlength="50" required>
                  <div class="invalid-feedback">لطفا  نام خود را وارد کنید</div>
                </div>
                <div class="form-group">
                  <label for="username">نام کاربری:</label>
                  <input type="text" class="form-control" id="username" value="<?=@$_COOKIE['username']?>" name="username" maxlength="50" required>
                  <div class="invalid-feedback">لطفا  نام  کاربری را وارد کنید</div>
                </div>
                <div class="form-group">
                  <label for="password">رمز عبور:</label>
                  <input type="number" class="form-control" id="password"  name="password" min="100000"  placeholder="فقط عدد وارد کنید حداقل 6 رقم" required>
                  <div class="invalid-feedback">لطفا رمز عبور را وار کنید</div>
                </div>
                        <?php
        echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">';

        ?>
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
    </script>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
  </body>
</html>


