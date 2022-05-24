<?php require_once "pageLoader.php"; ?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>نصب بلوکیا</title>
        <link rel="icon" type="image/png" href="../images/iconExam2.png" />
        <link rel="stylesheet" href="../lib/fonts/font.css"  />
        <link rel="stylesheet" href="../lib/font-awesome/css/fontawesome-all.min.css" >
        <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" />
        <link rel="stylesheet" href="../css/panel.css" />
        <script src="../lib/js/jquery.min.js"></script>
        <script src="../lib/js/function.js"></script>
    </head>
    <body class="rtl " >
        <div class="container" style="margin-top: -50px;">
            <?=$Msg->show();?>
            <form class="needs-validation" novalidate>
            <div class="row" >
                <div class="col-md-12">
                    <?php
                        if (!extension_loaded('mbstring'))
                        {
                            echo 
                            "<div class='alert alert-danger' >
                            	extensions  mbsring  
                            	در هاست شما  فعال نیست  ابتدا باید 
                            	mbstring 
                            	در هاست خود فعال کنید. 
                            	<br/>
                            	برای فعال سازی در هاست سی پنل ابتدا در صفحه  اصلی گزینه 
                            	select php version 
                            	را انتخاب کنید. 
                            	سپس از قسمت  extensions  افزونه mbstring  فعال کنید.
                            	<br/>
                            	درصورتیکه  موفق به  فعال سازی  mbstring نشدید با شرکت ارائه دهنده  هاست خود تماس حاصل فرمایید.
                            	<img src='enable_mbstring.png' class='w-100 img-thumbnail' />
                            </div>";
                        }
                    ?>
                    <div class="h3" >نصب بلوکیا</div>
                </div>
                <div class="col-md-6">
                        <div >اطلاعات پایگاه داده: </div>
                        <div class="form-group">
                          <label for="DBname">نام پایگاه داده: </label>
                          <input type="text" class="form-control" id="DBname" placeholder="" name="DBname" required>
                          <div id="msgDBname" ></div>
                        </div>
                        <div class="form-group">
                          <label for="DBuser">نام کاربری پایگاه داده: </label>
                          <input type="text" class="form-control" id="DBuser" placeholder="" name="DBuser" required>
                          <div id="msgUser" ></div>
                        </div>
                        <div class="form-group">
                          <label for="DBpass">رمز عبور پایگاه داده: </label>
                          <input type="text" class="form-control" id="DBpass" placeholder="" name="DBpass" required>
                          <div id="msgDBname" ></div>
                        </div>
                        <div class="form-group">
                          <label for="DBhost">آدرس پایگاه داده: </label>
                          <input type="text" class="form-control" id="DBhost" placeholder="" name="DBhost" value="localhost" required>
                          <div id="msgDBname" ></div>
                        </div>            
                </div>
                <div class="col-md-6" >
                    <div>اطلاعات حساب کاربری: </div>
                    <div class="form-group">
                      <label for="name">نام: </label>
                      <input type="text" class="form-control" id="name" placeholder="" name="name" required>
                      <div id="msgUserName" ></div>
                    </div>
                    <div class="form-group">
                      <label for="username">نام کاربری: </label>
                      <input type="text" class="form-control" id="username" placeholder="" name="username" required>
                      <div id="msgUserName" ></div>
                    </div>
                    <div class="form-group">
                      <label for="email">ایمیل: </label>
                      <input type="text" class="form-control" id="email" placeholder="" name="email" required>
                      <div id="msgEmail" ></div>
                    </div>
                    <div class="form-group">
                      <label for="password">رمز عبور: </label>
                      <input type="text" class="form-control" id="password" name="password" placeholder="یک رمز امن انتخاب کنید"  required>
                      <div id="msgPassword" ></div>
                    </div>
                </div>
                <div class="col-md-12">
                     <button type="submit" id="btnInstall" class="btn btn-primary mb-2">نصب</button> 
					 <a target="_blank" href="https://blockia.ir/learn" >آموزش نرم افزار</a>
                    <div id="msg" ></div>
                </div>
                
            </div>
             </form>
            
        </div>
        
        <script>
        $(document).ready(function(){
            $("form").on("submit", function(event){
                event.preventDefault();
         
                var formValues= $(this).serialize();
                $("#msg").html("در حال نصب چند لحظه صبر کنید . . . ");
                $.post("install.php", formValues, function(data){
                    // Display the returned data in browser
                    $("#msg").html(data);
                });
            });
        });
        </script>
        <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>

          
    <body>
</html>
