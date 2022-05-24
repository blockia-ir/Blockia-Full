<?php ob_start();session_start();
require_once "../lib/php/autoload.php";

$DB=new DB();
$Msg=new Msg();
$Func=new Func();
$Jdf=new Jdf();
$Encryption=new Encryption();

$email=$Func->faNumToEn(strtolower(strip_tags(@$_POST['email'])));
$captcha=strip_tags(strtolower($_POST['captcha']));
$domain=get_domain("http://".$_SERVER['HTTP_HOST']);
$valid=true;


if($email=="")
{
    exit("<b class='text-danger' >لطفا رمز عبور را وارد کنید </b>");
}

if($captcha!=$_SESSION['captcha']['code'])
{
    exit("<b class='text-danger' >کد امنیتی را به  صورت  صحیح وارد کنید");
}

$block_user=$DB->query("SELECT * FROM {$DB->tablePrefix}block_users WHERE email=<bind>$email</bind>",true);

if(@$block_user->email==$email)
{	 
	$linkResetPassword="https://".$_SERVER['HTTP_HOST']."".$_SERVER['PHP_SELF'];
	$linkResetPassword=str_replace(basename($_SERVER['PHP_SELF']),"resetPasswordForm.php",$linkResetPassword);
	$linkResetPassword=$linkResetPassword."?data=".$Encryption->encode($block_user->id.",".strtotime("1 hours"));
	//echo "<br/>";
	$to = $email;
	$subject = " exam recovery password ".date("Y-m-d H:i:s");
	$body = "
	<html>
		<body dir='rtl' >
			نام کاربری: $block_user->username <br/> <br/>
			برای بازیابی  رمز عبور روی لینک  زیر کلیک کنید:
			<br/>
			<a href='$linkResetPassword' >لینک بازیابی رمز عبور</a>
			<br/>
			<p>در صورتیکه  لینک  فوق کار نکرد لینک زیر را در نوار آدرس مرورگرتون کپی کنید</p>
			<br/>
			$linkResetPassword
		</body>
	</html>";
	$headers = "From: info@$domain \r\n";
	$headers .= "Content-type: text/html; charset=utf-8 \r\n";
	if (mail($to, $subject, $body, $headers)) 
	{
		echo
		"<br/>
		<b class='text-success' >ایمیل بازیابی ارسال شد <br/>
		درصورتیکه  ایمیل در پوشه inbox وجود نداشت 
		پوشه spam را بررسی کنید <br/>
		توجه کنید که ایمیل ارسالی فقط یک ساعت اعتبار خواهد داشت
		</b>
		<style>#formRecoveryEmail{display:none;}</style>";
		unset($_SESSION['captcha']['code']);
	} 
	else 
	{
		echo "<b class='text-danger' >خطا در ارسال ایمیل! تنظیمات سرور از این  امکان پشتیبانی نمی کند</b>";
	}
}
else
{
	echo "<b class='text-danger' >ایمیل ثبت نشده</b>";
}
	

function get_domain($url)
{
  $pieces = parse_url($url);
  $domain = isset($pieces['host']) ? $pieces['host'] : '';
  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
    return $regs['domain'];
  }
  return false;
}

