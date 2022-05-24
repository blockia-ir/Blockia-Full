<?php require_once "pageLoader.php";

$ip=$Func->getIP();
$examResultUser=false;
$examFinsh=false;

$exam_id=strip_tags($_REQUEST['id']);
if(!is_numeric($exam_id))
{
    exit("آزمون نامعتبر است");
}


$exam=$DB->query("SELECT {$DB->tablePrefix}exam.*,
            (SELECT COUNT(id) FROM {$DB->tablePrefix}exam_question WHERE
            {$DB->tablePrefix}exam_question.exam_id=<bind>$exam_id</bind>
            AND {$DB->tablePrefix}exam_question.question_type_id=5) as countTashrihi
            FROM {$DB->tablePrefix}exam 
            WHERE {$DB->tablePrefix}exam.id=<bind>$exam_id</bind>",true);

if(!isset($exam->id))
{
    exit("آزمون یافت نشد");
}

if($exam->countTashrihi>0)
{
  $tashrihi=true;
}
else
{
  $tashrihi=false;
}
$tdate_start=strtotime($exam->date_start);
$tdate_end=strtotime($exam->date_end);
$tdate_now=strtotime($Jdf->date());

if($tdate_now>$tdate_end)
{
    $examFinsh=true;
    if($exam->show_list_mark<21 && $exam->show_list_mark!=103)
    {
      if($tashrihi)
        $sqlMarked="AND {$DB->tablePrefix}exam_result.marked=1";
      else
      $sqlMarked="";
        
      $listExamResult=$DB->query("SELECT {$DB->tablePrefix}exam_result.* FROM {$DB->tablePrefix}exam_result 
                                WHERE {$DB->tablePrefix}exam_result.exam_id=<bind>$exam_id</bind> $sqlMarked
                                AND {$DB->tablePrefix}exam_result.mark>={$exam->show_list_mark}
                                LIMIT 1000 ");
    }
}

$jdate_end=$Jdf->jdate('l, j F Y ساعت H:i',$tdate_end,0);


if((isset($_COOKIE[$exam_id]) && is_numeric($_COOKIE[$exam_id])) || isset($_GET['eri']))
{
  $exam_result_id=@$_COOKIE[$exam_id];
  
  if(isset($_GET['eri']) && is_numeric($_GET['eri']) )
  {
    $exam_result_id=strip_tags($_GET['eri']);
  }
  
  if(!is_numeric($exam_result_id))
		exit("خطا: شماره برگه  پاسخنامه نامعتبر است");
	
  $exam_result=$DB->query("SELECT {$DB->tablePrefix}exam_result.* FROM {$DB->tablePrefix}exam_result 
							WHERE {$DB->tablePrefix}exam_result.id=<bind>$exam_result_id</bind>",true);
  
  if(isset($exam_result->id))
  {
    $examResultUser=true;
  }
  else
  {
     setcookie($exam_id,"",time()-(365*24*60*60),"/");
  }
  
}


$title=" لیست نمرات ".$exam->name;

?>
<!DOCTYPE html>
<html lang="fa">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="theme-color" content="#F7F7F7" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= @$title ?></title>
    <meta name="description" content="<?= @$description ?> " />
    <link rel="icon" type="image/png" href="images/iconExam2.png" />
    <link rel="stylesheet" href="lib/fonts/font.css" />
    <!--<link rel="stylesheet" href="lib/font-awesome/css/fontawesome-all.min.css"  />-->
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap-rtl.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <!-- <script src="lib/js/jquery.min.js"></script> -->
    <script src="lib/Chart/Chart.min.js" ></script>
  </head>

  <body class="rtl"  >
        <div class="container " >
            <div class="row mt-5" >
                <div class="col-md-3" ></div>
                <div class="col-md-6 pt-2 text-center bg-white   border rounded "  >
					<div id="info" >
					<?=$Msg->show();?>
                    <?php if($examResultUser && !$tashrihi):?>
                        <p class="mb-0 mt-2 text-secondary" >آزمون: <?=@$exam->name?> </p>
                        <div class="text-info"><?=@$exam_result->student_name?></div>
                      <?php if($exam->show_list_mark!=103):?>
                        <div id="canvas-holder" class="w-100" >
                            <canvas id="chart-area"></canvas>
                        </div>
                        <p class="mb-0 mt-2 text-primary" >نمره: <?=$exam_result->mark+0?> </p>
                        <p class="mb-0 text-success" >تعداد درست: <?=$exam_result->count_true?></p>
                        <p class="mb-0 text-danger" >تعداد غلط: <?=$exam_result->count_false?></p>
                        <p class="mb-0 text-secondary" >تعداد بدون پاسخ: <?=$exam_result->count_empty?></p>
                        <p class="mb-0 text-dark" >در صد: <?=$exam_result->percent?> %</p>
                        <?php if($exam->show_answer==1):?>
                        <p class="mb-0 text-info"> <a href="ea.php?i=<?=$Encryption->encode($exam_result->id)?>" >مشاهده پاسخنامه</a></p>
                        <?php endif?>
                        
                        <?php if($exam_result->base_mark!=20):?>
                        <p class="mb-0 text-secondary small" >نمره این آزمون از <?=$exam_result->base_mark?> محاسبه شده</p>
                        <?php endif?>
                        <script type="text/javascript" >
                            window.chartColors = {
                                red: 'rgb(255, 99, 132)',
                                orange: 'rgb(255, 159, 64)',
                                yellow: 'rgb(255, 205, 86)',
                                green: 'rgb(75, 192, 192)',
                                blue: 'rgb(54, 162, 235)',
                                purple: 'rgb(153, 102, 255)',
                                grey: 'rgb(201, 203, 207)'
                            };
                        
                            var randomScalingFactor = function() {
                                return Math.round(Math.random() * 100);
                            };
                        
                            var config = {
                                type: 'doughnut',
                                data: {
                                    datasets: [{
                                        data: [<?= $exam_result->count_true.",".$exam_result->count_false.",".$exam_result->count_empty?>],
                                        backgroundColor: [window.chartColors.green,window.chartColors.red,window.chartColors.grey],
                                        label: 'Dataset 1'
                                    }],
                                    labels: ['صحیح','غلط','بدون پاسخ']
                                },
                                options: {
                                    responsive: true,
                                    legend: {
                                        display:false,
                                        position: 'top',
                                    },
                                    title: {
                                        display: false,
                                        text: 'نتیجه آزمون'
                                    },
                                    animation: {
                                        animateScale: true,
                                        animateRotate: true
                                    }
                                }
                            };
                        
                            window.onload = function() {
                                var ctx = document.getElementById('chart-area').getContext('2d');
                                window.myDoughnut = new Chart(ctx, config);
                            };
                        
                        </script>
						<?php else:?>
							<p class='text-success'>آزمون شما با موفقیت ثبت شده</p>
						<?php endif?>
					</div>
                    <hr/>
                    <?php elseif($examResultUser && @$exam_result->marked==1 && $exam->show_list_mark!=103):?>
                        <p class="mb-0 mt-2 text-secondary" >آزمون: <?=@$exam->name?> </p>
                        <div class="text-info"><?=@$exam_result->student_name?></div>
                        <p class="mb-0 mt-2 text-primary" >نمره: <?=@$exam_result->mark+0?> </p>
						<?php if($exam->show_answer==1):?>
                        <p class="mb-0 text-info"> <a href="ea.php?i=<?=$Encryption->encode($exam_result->id)?>" >مشاهده پاسخنامه</a></p>
                        <?php endif?>
                        <hr/>
                    <?php elseif($examResultUser && @$exam_result->marked!=1):?>
						<p>نام: <?=$exam_result->student_name?></p>
						<p>این آزمون  تشریحی بوده پس از تصحیح توسط معلم  نمره  شما در این قسمت نمایش داده خواهد شد</p>
                    <?php endif?>
                   
                    <?php if($examFinsh):?>
                      <div class="text-primary" ><?=$exam->name?></div>
                      <div class="text-dark small" > آزمون در تاریخ: <?=$jdate_end?> به پایان رسیده است</div>
                      <?php if($exam->base_mark!=20):?>
                      <div class="text-secondary small" >نمره این آزمون از <?=$exam->base_mark?> محاسبه شده</div>
                      <?php endif?>
                      <?php if($exam->show_list_mark<21 && $exam->show_list_mark!=103):?>
                      <table class="table  table-striped table-condensed border " >
                          <tr class="bg-dark text-white">
                              <th class="p-1">نام</th>
                              <th class="p-1">نمره</th>
                            <?php if(!$tashrihi):?>
                              <th class="p-1">درست</th>
                              <th class="p-1">غلط</th>
                              <th class="p-1">بدون پاسخ</th>
                              <th class="p-1">درصد</th>
                            <?php endif ?>
                          </tr>
                          <?php foreach($listExamResult as $value):?>
                          <tr>
                              <td class="p-2 text-left"><?=$value->student_name?></td>
                              <td class="p-2 text-left"><?=$value->mark+0?></td>
                            <?php if(!$tashrihi):?>
                              <td class="p-2"><?=$value->count_true?></td>
                              <td class="p-2"><?=$value->count_false?></td>
                              <td class="p-2"><?=$value->count_empty?></td>
                              <td class="p-2"><?=$value->percent?></td>
                            <?php endif ?>
                          </tr>
                          <?php endforeach?>
                      </table>
                      <?php endif?>
                    <?php elseif($exam->show_list_mark==21 || $exam->show_list_mark==103):?>
                     <p>  لیست  نمرات آزمون  <?=$exam->name?> پنهان شده است</p>
                    <?php else:?>
                      <p>  لیست  نمرات این آزمون  در تاریخ <?=$jdate_end?> نمایش داده خواهد شد</p>
                    <?php endif ?>
                </div>
                <div class="col-md-3" ></div>
            </div> 
        </div>
  <?php if($examResultUser  &&  !$tashrihi && $exam->show_list_mark!=103):?>
  <!-- make pattren with mark student -->
    <script>  
    const markTemplate = (name) => {
      return `<svg xmlns='http://www.w3.org/2000/svg' version='1.1' stroke="0x000000" height='55px' width='55px'><text transform='translate(10, 55) rotate(-45)' fill='rgba(45,45,45,0.08)' font-size='20' font-family='Shabnam' >${name}</text></svg>`;
    };
    const base64Mark = btoa(markTemplate(("<?=@$exam_result->mark?>")));
    document.getElementById("info").style.backgroundImage = `url("data:image/svg+xml;utf8;base64,${base64Mark}")`;
    </script>
  <?php endif ?>
    <!--<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>-->
    <?php unset($_SESSION['exam_result_id']);?>
  </body>

</html>
