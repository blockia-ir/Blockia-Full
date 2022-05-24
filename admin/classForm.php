<?php require_once "blockPageLoader.php";/*must be top of page*/ ?>
<!DOCTYPE html>
<html lang="fa">
  <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>کلاس ها</title>
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
		<div class="titleForm" >لیست کلاسها</div>
		<?php
			$id="";//define id
			if(!empty($_GET['id']))
			{
				$id=$Encryption->decode($_GET['id']);
				if(!is_numeric($id))
					exit("error: id must be number");
					
				$class=$DB->query("SELECT * FROM {$DB->tablePrefix}class WHERE block_user_id='$blockUserId' AND id='$id'",true);
			}
		?>
		<?= $Msg->show(); ?>
		<div class="row" >
			<div class="col-md-4" >
				<form action="addUpdateClass.php" method="post" >
					<input type="hidden" name="id" value="<?=$Encryption->encode(@$class->id)?>" style="display: none;" />
					<label for="name" >نام کلاس</label>
					<input type="text" id="name" name="name" value="<?= @$class->name ?>" maxlength="255"  class="form-control" autocomplete="off" required />
					<input type="submit" value="ثبت کلاس" class="btn btn-primary mt-1" />
					<?php if($id!=""):?>
						<a href="classForm.php" class="btn btn-warning mt-1" >درج کلاس جدید</a>
					<?php endif ?>
					<button type="button" class="btn btn-secondary mt-1" data-toggle="modal" data-target="#modalHelpClass"> راهنما</button>
                            
					
				</form>
			</div>
			<div class="col-md-8" >
			</div>
		</div>
		<div class="row" >
			<div class="col-md-12">
				<?php require_once "classGrid.php"; ?>
		
			</div>
		</div>
	</div>
<?php require_once "footer.php"?>
<!-- Modal Help register Student -->
<div id="modalHelpClass" class="modal fade rtl" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header ">
        <p class="modal-title"><b>راهنمای کلاس ها</b></p>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
		<p>
			در این  قسمت  می توانید کلاس ها خود را ثبت کنید
			در صورتیکه  در یک کلاس بیش از یک درس دارید برای هر درس
			کلاس جداگانه ای درج نکنید. چرا که در آن صورت برای هر کلاس باید دانش آموزان جداگانه درج شوند
			که از لحاظ ساختاری اشتباه است.
			نام درس در قسمت آزمون ها می توانید وارد کنید
			
		</p>
		<p>
			در صورتیکه یک کلاس را در چند مدرسه دارید
			برای تفکیک کلاس ها می توانید نام مدرسه را به انتهای کلاس اضافه کنید
			به تصویر زیر توجه کنید: <br/>
		  <img src="../help/h01.PNG" class="mw-100" />
		</p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
      </div>
    </div>

  </div>
</div>
<body>
</html>
