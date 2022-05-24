<?php ob_start();session_start();
require_once "../lib/php/autoload.php";
$_setting=parse_ini_file("settingAdmin.ini");

$DB=new DB();
$Msg=new Msg();
$Func=new Func();
$Jdf=new Jdf();
$Encryption=new Encryption();

require_once "exitBusy.php";

/*
if(isset($_COOKIE['sui']))
{
	$block_user_id=$Encryption->decode($_COOKIE['sui']);
	if(is_numeric($block_user_id) && $block_user_id>0)
	{
		$_SESSION['block_user_id']=$_COOKIE['sui'];
		unset($_SESSION['errorLogin']);
        $DB->query("call  insertLogin($result->id,'{$Func->getIP()}','{$Func->getOS()}','{$Func->getBrowser()}')");
        header("location:index.php");
		exit;
	}
	
}
*/
?>
<!doctype html>
<html lang="fa">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../images/iconExam2.png">
    <title>ورود به پنل <?=@$_setting['exam_name']?></title>
    <link rel="stylesheet" type="text/css" href="../lib/fonts/font.css" />
    <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" >
    <link rel="stylesheet" type="text/css" href="../css/login.css" />
    <script src="../lib/js/jquery.min.js" ></script>
  </head>

  <body class=" rtl text-center">
    <p class="mb-5" >&nbsp;</p>
    <form class="form-signin card" action="blockAuth.php?<?=rand()?>" method="post" >
        <?= $Msg->show() ?>
        
        <h2><?=@$_setting['exam_name']?></h2>
        <label for="username" class="sr-only">نام کاربری</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="نام کاربری" required autofocus>
        <label for="password" class="sr-only">رمز عبور</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="رمز عبور" required>
  
      <button id="btnSubmit" class="btn btn-lg btn-primary btn-block" type="submit" >ورود به پنل</button>
		<div class='small mt-2 text-left' >
      <?php if(@$_setting['register_user']==false):?>
			<a href="registerForm.php" class="" >ثبت نام</a> |
      <?php endif ?>
			<a class='' href="recoveryPasswordPage.php" >بازیابی رمز عبور</a> |
      <a href="https://blockia.ir"  target="_blank" >blockiaExam</a>
		</div>
   </form>

							
    <?php
    /*
	echo @$_SESSION['errorLogin'];
    echo $actual_link = $_SERVER['HTTP_REFERER'];
    */
    ?>
    <script>
        $("#btnSubmit").click(function(){
          if($("#username").val()!="" && $("#password").val()!="")
          {
            $(this).html("در حال بررسی اطلاعات . . .");
          }
        });
    </script>
    <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

<?php
session_destroy();
?>
