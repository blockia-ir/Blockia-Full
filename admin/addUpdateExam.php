<?php
require_once "blockPageLoader.php";

$id=strip_tags(@$_POST['id']);
$type=strip_tags(@$_POST['type']);
$name=strip_tags($_POST['name']);
$class=$_POST['class'];
$duration=strip_tags($_POST['duration']);
$date1=strip_tags($_POST['date1']);
$time1=strip_tags($_POST['time1']);
$date2=strip_tags($_POST['date2']);
$time2=strip_tags($_POST['time2']);
$date=$Jdf->date();
$type=(empty($type) ? 2:$type);

$valid=true;

if($name==""){
    $Msg->error("لطفا نام آزمون وارد کنید");
    $valid=false;
}

if(!is_array($class)){
    $Msg->error("لطفا کلاس وارد کنید");
    $valid=false;
}

if($duration==""){
    $Msg->error("لطفا مدت امتحان را وارد کنید");
    $valid=false;
}

if($date1==""){
    $Msg->error("لطفا تاریخ شروع وارد کنید");
    $valid=false;
}
if($time1==""){
    $Msg->error("لطفا زمان  شروع  وارد کنید");
    $valid=false;
}

if($date2==""){
    $Msg->error("لطفا تاریخ پایان آزمون وارد کنید");
    $valid=false;
}

if($time2==""){
    $Msg->error("لطفا زمان پایان آزمون وارد کنید");
    $valid=false;
}

if(!is_numeric($type))
{
    $Msg->error("نوع آزمون  معتبر نیست");
    $valid=false;
}

if($valid==false)
{
    header("location:examForm.php");
    exit;
           
}


$date1=$Jdf->convertToGregorian($date1);
$date2=$Jdf->convertToGregorian($date2);

$date_start=$date1." ".$time1;
$date_end=$date2." ".$time2;

$tdate_start=strtotime($date_start);
$tdate_end=strtotime($date_end);

/*
if($tdate_start<strtotime("-5 day") )
{
		$previous = "javascript:history.go(-1)";
		if(isset($_SERVER['HTTP_REFERER'])) {
			$previous = $_SERVER['HTTP_REFERER'];
		}
		echo "<center>";
		echo "<h1 style='color:#ff8800'>تاریخ شروع نباید در گذشته تنظیم شود</h1>";
		echo "<h2><a href='$previous' >بازگشت به صفحه قبل</a></h2>";
		echo "</center>";
		exit; 
}
*/

$number_exam=empty($_setting['number_exam_hour']) ? 1000:$_setting['number_exam_hour'];

if($id!="")
    $id=$Encryption->decode($id);
    
if($id!="" && is_numeric($id))
{
	$rowExam=$DB->query("SELECT * FROM {$DB->tablePrefix}exam WHERE id='$id' AND block_user_id='$blockUserId' ",true);
	if(strtotime($rowExam->date_start)!=$tdate_start)
	{
        
		$examHour=$DB->query("SELECT hour(date_start) as hour,count(*) as countExam,
                    sum(ROUND ((LENGTH(class)- LENGTH( REPLACE ( class, ',', '') ) ) / LENGTH(','))+1) AS countClass    
                    FROM {$DB->tablePrefix}exam
                    WHERE date(date_start)=date(<bind>$date_start</bind>) AND
                    hour(date_start)=hour(<bind>$date_start</bind>) GROUP BY hour(date_start)",true);
		if(@$examHour->countClass>=$number_exam)
		{
			$previous = "javascript:history.go(-1)";
			if(isset($_SERVER['HTTP_REFERER'])) {
				$previous = $_SERVER['HTTP_REFERER'];
			}

			$time_start=substr($time1,0,2)+0;
			echo "<center>";
			echo "<h1 style='color:#ff8800'>ظرفیت آزمون در ساعت $time_start به پایان رسیده است. امکان ثبت آزمون در این ساعت نیست</h1>";
			echo "<h1 style='color:#ff8800' >فقط در زمان ها خلوت امکان ثبت آزمون وجود دارد</h1>";
			echo "<h2><a href='$previous' >بازگشت به صفحه قبل</a></h2>";
			echo "</center>";
			exit; 
		}
	}
		
}
else
{
	$examHour=$DB->query("SELECT hour(date_start) as hour,count(*) as countExam,
                sum(ROUND ((LENGTH(class)- LENGTH( REPLACE (class, ',', '') ) ) / LENGTH(','))+1) AS countClass    
                FROM {$DB->tablePrefix}exam
                WHERE date(date_start)=date(<bind>$date_start</bind>) AND
                hour(date_start)=hour(<bind>$date_start</bind>) GROUP BY hour(date_start)",true);
	if(@$examHour->countClass>=$number_exam)
	{
		$previous = "javascript:history.go(-1)";
		if(isset($_SERVER['HTTP_REFERER'])) {
			$previous = $_SERVER['HTTP_REFERER'];
		}

		$time_start=substr($time1,0,2)+0;
		echo "<center>";
		echo "<h1 style='color:#ff8800'>ظرفیت آزمون در ساعت $time_start به پایان رسیده است. امکان ثبت آزمون در این ساعت نیست</h1>";
        echo "<h1 style='color:#ff8800' >فقط در زمان ها خلوت امکان ثبت آزمون وجود دارد</h1>";
		echo "<h2><a href='$previous' >بازگشت به صفحه قبل</a></h2>";
		echo "</center>";
		exit;
	}
}
/*
if($block_user_id==11)
{
	print_r($examHour);
	echo "<br/>";
	echo "SELECT hour(date_start) as hour,count(*) as countExam,
                sum(ROUND ((LENGTH(class)- LENGTH( REPLACE (class, ',', '') ) ) / LENGTH(','))+1) AS countClass    
                FROM {$DB->tablePrefix}exam
                WHERE date(date_start)=date('<bind>$date_start</bind>') AND
                hour(date_start)=hour('<bind>$date_start</bind>') GROUP BY hour(date_start)";
	// echo "SELECT hour(date_start) as hour,count(*) as countExam,
                    // sum(ROUND ((LENGTH(class)- LENGTH( REPLACE ( class, ',', '') ) ) / LENGTH(','))+1) AS countClass    
                    // FROM {$DB->tablePrefix}exam
                    // WHERE date(date_start)=date('<bind>$date_start</bind>') AND
                    // hour(date_start)=hour('<bind>$date_start</bind>') GROUP BY hour(date_start)";
	//echo $examHour->countClass." | ".$number_exam;
exit;
	
}*/


		
		
if($tdate_end<$tdate_start)
{
    $Msg->error("تاریخ پایان آزمون  نباید زودتر از تاریخ  شروع  آزمون  باشد");
    $back_id=($id=="") ? "":"?id=".$Encryption->encode($id);
   
    header("location:examForm.php$back_id");
    exit;
}

$exam['block_user_id']=$blockUserId;
$exam['type']=$type;
$exam['name']=$name;
$exam['class']=implode(",",$class);
$exam['duration']=$duration;
$exam['date_start']=$date_start;
$exam['date_end']=$date_end;




if($id!="" && is_numeric($id)){
    //update exam
    $exam_id=$id;
    $result=$DB->update("{$DB->tablePrefix}exam",$exam," WHERE  id='$exam_id' AND block_user_id='$blockUserId' ");
    if($result){
        $DB->prepare("DELETE FROM {$DB->tablePrefix}exam_class WHERE exam_id='$exam_id' AND block_user_id='$blockUserId' ");
        foreach($class as $val)
        {
            $DB->insert("{$DB->tablePrefix}exam_class",['block_user_id'=>$blockUserId,'exam_id'=>$exam_id,'class_id'=>$val]);
        }
        $Msg->success("آزمون با موفقیت ویرایش شد");
        header("location:examForm.php?id=".$Encryption->encode($exam_id));
        exit;
    }else{
        //var_dump($result);
        $Msg->show();
    }

}else{
    //insert exam
    $result=$DB->insert("{$DB->tablePrefix}exam",$exam);
    if($result)
    {
        $exam_id=$DB->lastInsertId();
        foreach($class as $val)
        {
            $DB->insert("{$DB->tablePrefix}exam_class",['block_user_id'=>$blockUserId,'exam_id'=>$exam_id,'class_id'=>$val]);
        }
        $Msg->success("آزمون با موفقیت درج شد.  <br/> اکنون می توانید سوالات آزمون درج  کنید");
        header("location:examForm.php?id=".$Encryption->encode($exam_id));
        exit;
    }
    
}
