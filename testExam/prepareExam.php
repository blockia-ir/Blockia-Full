<?php require_once "pageLoader.php";

$ip=$Func->getIP();

$exam_id=strip_tags($Encryption->decode(@$_REQUEST['exam_id']));
$student_name=strip_tags(@$_REQUEST['name']);
$exam_class_id=strip_tags(@$_REQUEST['exam_class_id']);
$code=strip_tags(@$_REQUEST['code']);

$nowDate=date("Y-m-d H:i:s");
//$block_user_id="";
$class_name="";

$newExam=true;

if(!is_numeric($exam_id))
{
  exit("خطا: اطلاعات ارسالی ناقص است");
}

// if(!empty($_COOKIE[$exam_id]))
// {
	// $exam_result_id=($_COOKIE[$exam_id]);
	// $_SESSION['exam_result_id']=$exam_result_id;
	// $exam_result_id=$Encryption->encode($exam_result_id);
	// header("location:startExam.php?id=$exam_result_id");
	// exit("");
// }

$exam=$DB->query("SELECT {$DB->tablePrefix}exam.*,
                 (select count(id) from {$DB->tablePrefix}exam_question where exam_id={$DB->tablePrefix}exam.id) as countQuestion
                 FROM {$DB->tablePrefix}exam WHERE id='$exam_id' AND block_user_id='$block_user_id' ",true);
if(!isset($exam->id)){
     exit("خطا: آزمونی متعلق به شما یافت نشد");
}


$tdate_start=strtotime($exam->date_start);
$tdate_end=strtotime($exam->date_end);
$tdate_now=strtotime("now");

// if($tdate_start>$tdate_now )
// {
  // $Msg->error("آزمون هنوز شروع نشده");
  // header("location:exam.php?id=$exam_id");
  // exit;
// }
// elseif($tdate_now>=$tdate_end)
// {
  // $Msg->error("آزمون به پایان رسیده است");
  // header("location:exam.php?id=$exam_id");
  // exit;
// }

  $class_student_id=NULL;
  if($exam->private==1 )
  {
		if(empty($code))
		{
			$Msg->error("برای شرکت در این آزمون  نیاز به کد ورود است");
      header("location:exam.php?id=$exam_id");
      exit;
		}
		
    $code=$Func->faNumToEn($code);
    if(!is_numeric($code))
      exit("error code not valid");
    
    $classStudent=
    $DB->query("SELECT {$DB->tablePrefix}class_student.id as class_student_id,
		{$DB->tablePrefix}class_student.name as student_name,
    {$DB->tablePrefix}class_student.code,{$DB->tablePrefix}class.name as class_name,
		{$DB->tablePrefix}exam_class.id as exam_class_id,
    {$DB->tablePrefix}exam_result.id as exam_result_id,
		{$DB->tablePrefix}exam_result.date_finsh FROM `{$DB->tablePrefix}class_student` 
    LEFT JOIN {$DB->tablePrefix}exam_result on {$DB->tablePrefix}exam_result.class_student_id={$DB->tablePrefix}class_student.id AND {$DB->tablePrefix}exam_result.exam_id='$exam_id'
    INNER JOIN  {$DB->tablePrefix}exam_class on {$DB->tablePrefix}exam_class.class_id={$DB->tablePrefix}class_student.class_id AND {$DB->tablePrefix}exam_class.exam_id='$exam_id'
    INNER JOIN {$DB->tablePrefix}class on {$DB->tablePrefix}class_student.class_id={$DB->tablePrefix}class.id INNER JOIN {$DB->tablePrefix}exam on {$DB->tablePrefix}exam_class.exam_id={$DB->tablePrefix}exam.id
    WHERE {$DB->tablePrefix}class_student.code=<bind>$code</bind> AND {$DB->tablePrefix}exam.private=1",true);
    
    if(!isset($classStudent->student_name))
    {
			$student=$DB->query("SELECT {$DB->tablePrefix}class_student.id, {$DB->tablePrefix}class.name as class_name
													FROM {$DB->tablePrefix}class_student
													INNER JOIN {$DB->tablePrefix}class on {$DB->tablePrefix}class_student.class_id={$DB->tablePrefix}class.id AND
													{$DB->tablePrefix}class_student.code=<bind>$code</bind>
													WHERE {$DB->tablePrefix}class_student.block_user_id={$exam->block_user_id} AND
													{$DB->tablePrefix}class_student.code=<bind>$code</bind> ",true);

			if(isset($student->id))
				$Msg->error(" کلاس  {$student->class_name} برای این آزمون ثبت نشده است ");
			else
				$Msg->error(" کد ورود به آزمون اشتباه است ");
				
      header("location:exam.php?id=$exam_id");
      exit;
    }
    elseif($classStudent->date_finsh!="")
    {
      $Msg->error("کاربر {$classStudent->student_name} قبلا در این آزمون شرکت کرده اید");
      header("location:examResult.php?id=$exam_id&eri={$classStudent->exam_result_id}");
      exit;
    }
    elseif(is_numeric($classStudent->exam_result_id))
    {
      $exam_result_id=$classStudent->exam_result_id;
			$_SESSION['exam_result_id']=$exam_result_id;
			setcookie($exam_id,$exam_result_id,time()+(365*24*60*60),"/");
			$exam_result_id=$Encryption->encode($exam_result_id);
			header("location:startExam.php?id=$exam_result_id");
			exit;
    }
    else
    {
      $class_student_id=$classStudent->class_student_id;
      $student_name=$classStudent->student_name;
      $exam_class_id=$classStudent->exam_class_id;
			$class_name=$classStudent->class_name;
    }
  }
  else
  {
	  if($student_name=="")
		{
			$Msg->error("نام خود را وارد کنید");
			header("location:exam.php?id=$exam_id");
			exit;
	  }
	  
	  if(!is_numeric($exam_class_id))
		{
			$Msg->error("کلاس خود را وارد کنید");
			header("location:exam.php?id=$exam_id");
			exit;
	  }

	  $examClass=$DB->query("SELECT {$DB->tablePrefix}exam_class.*,{$DB->tablePrefix}class.name from  {$DB->tablePrefix}exam_class
                          INNER JOIN {$DB->tablePrefix}class on {$DB->tablePrefix}exam_class.class_id={$DB->tablePrefix}class.id
                          WHERE {$DB->tablePrefix}exam_class.id='$exam_class_id' LIMIT 1",true);
	  if(!isset($examClass->id))
    {
      $Msg->error("کلاس آزمون یافت نشد $exam_class_id");
      header("location:exam.php?id=$exam_id");
      exit(" کلاس آزمون یافت نشد ");
	  }
		
	  $class_name=$examClass->name;
  }
  
  $row['block_user_id']=$exam->block_user_id;
  $row['exam_id']=$exam_id;
  $row['exam_class_id']=$exam_class_id;
  $row['class_student_id']=$class_student_id;
  $row['student_name']=$student_name;
  $row['class_name']=$class_name;
  $row['type']=$exam->type;
  $row['base_mark']=$exam->base_mark;
  $row['number_question']=$exam->number_question;
  $row['negative']=$exam->negative;
  $row['exam_name']=$exam->name;
  //$row['count_question']=$exam->count_question;
  $row['ip']=$ip;
  $row['date_create']=$nowDate;
  
 
	if(empty($_SESSION['exam_result_id']))
	{
		$result=$DB->insert("{$DB->tablePrefix}exam_result",$row);
		$lastInsertId=$DB->lastInsertId();
		if($result &&  is_numeric($lastInsertId))
		{
			$exam_result_id=$lastInsertId;
			$_SESSION['exam_result_id']=$exam_result_id;
			setcookie($exam_id,$exam_result_id,time()+(365*24*60*60),"/");
			$exam_result_id=$Encryption->encode($exam_result_id);
			header("location:startExam.php?id=$exam_result_id");
			exit;
		}
	}
	else
	{
		$exam_result_id=$_SESSION['exam_result_id'];
		setcookie($exam_id,$exam_result_id,time()+(365*24*60*60),"/");
		$exam_result_id=$Encryption->encode($exam_result_id);
		header("location:startExam.php?id=$exam_result_id");
		exit;
	}
  




?>
