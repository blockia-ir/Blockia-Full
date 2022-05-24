<?php require_once "blockPageLoader.php";/*must be top of page*/ ?>
<!DOCTYPE html>
<html lang="fa">
  <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>دانش آموزان</title>
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
	<?php
	
		$id="";//define id
		if(!empty($_GET['id']))
		{
			$id=$Encryption->decode(strip_tags($_GET['id']));
			if(!is_numeric($id))
				exit("error: id is not valid !");
			
			$classStudent=$DB->query("SELECT * FROM {$DB->tablePrefix}class_student WHERE block_user_id='$blockUserId' AND id='$id' ",true);
		}
		
		$listClass=$DB->query("SELECT * FROM {$DB->tablePrefix}class WHERE block_user_id='$blockUserId' ");
    
    $countClass=count($listClass);
    if($countClass==0)
    {
      $Msg->error("پیش از اینکه دانش آموزان  درج کنید ابتدا باید کلاس های خود را وارد کنید");
      header("location:classForm.php");
      exit;
    }
	?>
	<span><a href="class_studentPage.php" ><i class="fas fa-list"></i> لیست درج شده</a></span>
	<div class="titleForm" ><?= $id>0 ? " ویرایش ".@$classStudent->name :" درج  دانش آموز جدید " ?></div>
	<?= $Msg->show(); ?>
	<div class="row" >
		<div class="col-md-4" >
			<form action="addUpdateClassStudent.php" method="post" >
				<input type="hidden" name="id" value="<?=$Encryption->encode(@$classStudent->id)?>" style="display: none;" />
				<label for="class_id" >نام کلاس</label>
				<select name="class_id" id="class_id" class="form-control" required >
					<option value="" ></option>
					<?php foreach($listClass as $class):?>
						<option value="<?= $class->id ?>" <?= ($class->id==@$classStudent->class_id) ? "selected":""?> ><?= $class->name ?></option>
					<?php endforeach?>
				</select>
				<label for="name" >نام کامل دانش آموز</label>
				<input type="text" id="name" name="name" value="<?= @$classStudent->name ?>" maxlength="50"  class="form-control" autocomplete="off"  />
				<label for="code" >کد ورود به آزمون</label>
				<input type="number" id="code" name="code" value="<?= @$classStudent->code ?>" maxlength="10" min="1" max="9999999999" placehoder="کد ورود باید به  صورت عددی باشد"
				oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  class="form-control" plachoder="کد عددی حداکثر 10 رقم" autocomplete="off"  />
				<input type="submit" value="ثبت دانش آموز" class="btn btn-primary mt-1" />
				<?php if($id!=""):?>
					<a href="class_studentForm.php" class="btn btn-warning mt-1" >درج دانش آموز جدید </a>
				<?php endif ?>
			</form>
		</div>
		<div class="col-md-8" >
		</div>
	</div>
<style>
	.bs-tooltip-auto[x-placement^=bottom] .arrow::before,
.bs-tooltip-bottom .arrow::before {
  border-bottom-color: #f00; /* Red */
}
</style>

<script>
$(function () {
    $("#code")
        .tooltip({ placement:"top",title: 'کد آزمون می تونه هر عددی باشه  مثلا کد ملی دانش آموز، شماره دانش آموزی و یا هر عدد دیگری که خودتون مایل هستید' })
        .blur(function () {
            $(this).popover('hide');
        });
});
</script>
	</div>
<?php require_once "footer.php"?>
<body>
</html>
