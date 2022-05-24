<?php require_once "blockPageLoader.php";
	
$date_start=strip_tags($_POST['date_start']);
$date_start=$Jdf->convertToGregorian($date_start);

$listExamhour=$DB->query("SELECT hour(date_start) as hour , count(*) as countExam,
			sum(ROUND ((LENGTH(class)- LENGTH( REPLACE ( class, ',', '') ) ) / LENGTH(','))+1) AS countClass    
			FROM {$DB->tablePrefix}exam
			WHERE date(date_start)=date(<bind>$date_start</bind>) AND hour(date_start)>=7
			GROUP BY hour(date_start)");

$number_exam=empty($_setting['number_exam_hour']) ? 1000:$_setting['number_exam_hour'];
//echo $number_exam*0.6;
	
function classCountExam($count)
{
	global $number_exam;
	if($count<$number_exam*0.6)
		return "bg-success text-white";
	elseif($count>=$number_exam*0.6  &&  $count<$number_exam)
		return "bg-warning text-white";
	elseif($count>=$number_exam && $count<$number_exam*1.2)
		return  "bg-danger text-white";
	elseif($count>=$number_exam*1.2)
		return "bg-dark text-danger";
}

function statusCountExam($count)
{
	global $number_exam;
	if($count<$number_exam*0.6)
		return " خلوت ";
	elseif($count>=$number_exam*0.6  && $count<$number_exam)
		return " متوسط ";
	elseif($count>=$number_exam &&  $count<$number_exam*1.2)
		return  " شلوغ";
	elseif($count>=$number_exam*1.2)
		return " خیلی شلوغ ";
}
?>
<div class="alert alert-info mt-1" >
	این آمار لحظه ای است و در هر لحظه می تواند تغییر پیدا کند
	<br/>
	تاریخ: <?= $Jdf->jdate('l, j F Y',strtotime($date_start));?>
</div>
<table class="table border  bg-white">
	<tr>
		<th>ساعت</th>
		<th>وضعیت سرور</th>

		<!--<th>تعداد آزمون</th> -->

	</tr>
	<?php foreach($listExamhour as $examHour):?>
	<tr class="<?=classCountExam($examHour->countClass) ?>" >
		<td> ساعت <?= $examHour->hour?> تا <?= $examHour->hour+1?></td>
		<td><?= statusCountExam($examHour->countClass)?></td>
	
		<!--<td><?= $examHour->countClass?></td>-->
		
	</tr>
	<?php endforeach?>
</table>
