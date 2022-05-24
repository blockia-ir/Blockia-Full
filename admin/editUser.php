<?php require_once "blockPageLoader.php"; 

$editInfo=strip_tags(@$_POST['editInfo']);
$name=addslashes(strip_tags(@$_POST['name']));
$email=addslashes(strip_tags(@$_POST['email']));
$password=$Func->faNumToEn(strip_tags(@$_POST['password']));

if($editInfo==1)
{

	if($email!="")
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
		{
			$Msg->error("ساختار ایمیل وارد شده صحیح نیست");
			header("location:editblockUserForm.php");
			exit;
		}
		
		$block_user=$DB->query("SELECT * FROM {$DB->tablePrefix}block_users WHERE email=<bind>$email</bind> AND id<>'$blockUserId' ",true);
		if(!empty($block_user->email))
		{
			$Msg->error("ایمیل $email از قبل ثبت شده است");
			header("location:editblockUserForm.php");
			exit;
		}
	}
	else
	{
		$email=null;
	}
			
	
	$DB->update("{$DB->tablePrefix}block_users",['name'=>$name,'email'=>$email]," WHERE id='$blockUserId' ");
	setcookie("nameUser",$name,0,"/");
	$Msg->success("تغییرات با موفقیت ثبت شد");
	
}



if($password!="")
{
    $passwordHash=password_hash(($password),PASSWORD_BCRYPT,['cost'=>12]);
    $DB->update("{$DB->tablePrefix}block_users",['password'=>$passwordHash]," WHERE  id='$blockUserId' " );
    $Msg->success("رمز عبور به  صورت  $password تغییر کرد");
}

header("location:editblockUserForm.php");
exit;
    
