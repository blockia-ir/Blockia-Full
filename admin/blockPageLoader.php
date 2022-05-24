<?php ob_start();session_start();
$time_start = microtime(true);//get time start run script
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require_once "../lib/php/autoload.php";

if(empty($_SESSION['block_user_id']))
{
	header("location:blockSignOut.php?i=".rand());
	exit;
}

$DB=new DB();
$Msg=new Msg();
$Func=new Func();
$Jdf=new Jdf();
$Encryption=new Encryption();

$block_user_id=$blockUserId=$blockUserId=$Encryption->decode($_SESSION['block_user_id']);
define("block_user_id",$block_user_id);

if(!is_numeric($block_user_id))
	exit("error: user id is not valid");

	
if(!empty($_SESSION['admin']))
{
	if($block_user_id==$Encryption->decodeAdmin($_SESSION['admin']))
	{
		$Admin=$admin=true;
		define("admin",true);
	}
	else
	{
		$Admin=$admin=false;
		define("admin",false);
	}
}
else
{
	$Admin=$admin=false;
	define("admin",false);
}


//define table name var for make easy query!
$tbl_class=$DB->tablePrefix."class";
$tbl_class_student=$DB->tablePrefix."class_student";
$tbl_exam=$DB->tablePrefix."exam";
$tbl_exam_class=$DB->tablePrefix."exam_class";
$tbl_exam_question=$DB->tablePrefix."exam_question";
$tbl_exam_question_type=$DB->tablePrefix."exam_question_type";
$tbl_exam_result=$DB->tablePrefix."exam_result";
$tbl_logins=$DB->tablePrefix."logins";
$tbl_block_users=$DB->tablePrefix."block_users";
$tbl_student_answer=$DB->tablePrefix."student_answer";
$tbl_upload_files=$DB->tablePrefix."upload_files";

$_setting=parse_ini_file("settingAdmin.ini");
