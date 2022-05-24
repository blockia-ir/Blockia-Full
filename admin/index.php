<?php require_once "blockPageLoader.php"; /*must be top of page*/ ?>
<!DOCTYPE html>
<html lang="fa">
  <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>صفحه اصلی</title>
    <link rel="icon" type="image/png" href="../images/iconExam2.png" />
    <link rel="stylesheet" href="../lib/fonts/font.css"  />
    <link rel="stylesheet" href="../lib/font-awesome/css/fontawesome-all.min.css" >
    <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" />
    <link rel="stylesheet" href="../css/panel.css" />
    <script src="../lib/js/jquery.min.js"></script>
    <script src="../lib/js/function.js"></script>
  </head>
  <body class="rtl" >
	<?php require_once "menu.php"?>
	<div class="container-fluid">
		<?=$Msg->show();?>
		<?php
          $amar=$DB->query("SELECT 
          (SELECT COUNT(id) FROM {$DB->tablePrefix}exam WHERE {$DB->tablePrefix}exam.block_user_id=<bind>$block_user_id</bind>) as countExam,
          (SELECT COUNT(id) FROM {$DB->tablePrefix}exam_question WHERE {$DB->tablePrefix}exam_question.block_user_id=<bind>$block_user_id</bind>) as countQuestion,
          (SELECT COUNT(id) FROM {$DB->tablePrefix}class WHERE {$DB->tablePrefix}class.block_user_id=<bind>$block_user_id</bind>) as countClass,
          (SELECT COUNT(id) FROM {$DB->tablePrefix}class_student WHERE {$DB->tablePrefix}class_student.block_user_id=<bind>$block_user_id</bind>) as countStudent",true);
    ?>
		<div class="row" id="homePage" >
			<div class="col-md-3">
				<a href="examPage.php" >
					<div class="card">
						<div class="card-body text-center" >
							<h2>آزمونها</h2>
							<img src="../images/iconExam.png" />
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-3">
				<a href="exam_resultPage2.php" >
					<div class="card" >
						<div class="card-body text-center" >
							<h2>نتایج آزمون</h2>
							<img src="../images/iconRank.png" />
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-3">
				<a href="classForm.php" >
					<div class="card">
						<div class="card-body text-center">
							<h2>کلاس ها</h2>
							<img src="../images/iconClass.png" />
						</div>
					</div>   
				</a>
			</div>
      <div class="col-md-3">
				<a href="class_studentPage.php" >
					<div class="card">
						<div class="card-body text-center">
							<h2>دانش آموزان</h2>
							<img src="../images/student.png" />
						</div>
					</div>   
				</a>
			</div>
			<div class="col-md-6" >
				<div class="card" >
					<div class="card-body text-center" >
						<h2>تعداد آزمونها</h2>
						<h2><?=number_format(@$amar->countExam)?></h2>
					</div>
				</div>
			</div>
			<div class="col-md-6" >
				<div class="card" >
					<div class="card-body text-center" >
						<h2>تعداد سوالات</h2>
						<h2><?=number_format(@$amar->countQuestion)?></h2>
					</div>
				</div>
			</div>
      <div class="col-md-6" >
				<div class="card" >
					<div class="card-body text-center" >
						<h2>تعداد کلاس ها</h2>
						<h2><?=number_format(@$amar->countClass)?></h2>
					</div>
				</div>
			</div>
      <div class="col-md-6" >
				<div class="card" >
					<div class="card-body text-center" >
						<h2>تعداد دانش آموزان</h2>
						<h2><?=number_format(@$amar->countStudent)?></h2>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php require_once "footer.php"?>
<body>
</html>
