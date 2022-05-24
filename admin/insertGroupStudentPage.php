<?php require_once "blockPageLoader.php";/*must be top of page*/ ?>
<!DOCTYPE html>
<html lang="fa">
  <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>درج گروهی دانش آموزان</title>
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
<?php $listClass=$DB->query("SELECT * FROM {$DB->tablePrefix}class WHERE block_user_id='$blockUserId' "); ?>
<span><a href="class_studentPage.php" ><i class="fas fa-list"></i> لیست درج شده</a></span>
<div class="titleForm" >درج گروهی دانش آموزان</div>
<?= $Msg->show(); ?>
<div class="row" >
	<div class="col-md-4" >
		<form action="#" method="post" >
			<label for="class_id" >نام کلاس</label>
				<select name="class_id" id="class_id" class="form-control" required >
					<option value="" ></option>
					<?php foreach($listClass as $class):?>
						<option value="<?= $class->id ?>" ><?= $class->name ?></option>
					<?php endforeach?>
				</select>
			<label for="listStudent" >لیست دانش آموزان</label>
			<textarea id="listStudent" name="listStudent" class="form-control" rows="15" ></textarea>

			<input type="button" id="btnInsertGroup" value="درج  گروهی دانش آموزان" class="btn btn-primary mt-1" />
			
		</form>
	</div>
	<div class="col-md-8" >
		<br/>
		<div id="result" class="mt-3" ></div>
	</div>
</div>

<script>
 $("#btnInsertGroup").click(function(){
	var class_id=$("#class_id").val();
	var listStudent=$("#listStudent").val();
	
	if(class_id=="" || class_id==undefined)
	{
		alert("کلاس را انتخاب کنید");
		return false;
	}
	
	if(listStudent=="" || listStudent==undefined)
	{
		alert("لیست دانش آموزان را وارد کنید");
		return false;
	}
	
	$("#result").html("در حال درج  لطفا صبر کنید . . . ");
	$.post("ajaxInsertGroupStudent.php",
			{
				class_id:class_id,
				listStudent:listStudent
			},
			function(data)
			{
				$("#result").html(data);
			}
		);
 })
</script>
	</div>
<?php require_once "footer.php"?>
<body>
</html>
