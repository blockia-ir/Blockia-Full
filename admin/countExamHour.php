<?php require_once "blockPageLoader.php"; ?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>وضعیت شلوغی</title>
        <link rel="icon" type="image/png" href="../images/iconExam2.png" />
        <link rel="stylesheet" href="../lib/fonts/font.css"  />
        <link rel="stylesheet" href="../lib/font-awesome/css/fontawesome-all.min.css" >
        <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" />
        <link rel="stylesheet" href="../css/panel.css" />
        <script src="../lib/js/jquery.min.js"></script>
        <script src="../lib/js/function.js"></script>
		<link rel="stylesheet" href="../lib/jsPersianCal/js-persian-cal.css">
		<script type="text/javascript" src="../lib/jsPersianCal/js-persian-cal.min.js"></script>
    </head>
    <body class="rtl" >
        <?php require_once "menu.php"?>
        <div class="container-fluid">
			<?= $Msg->show() ?>
			<div class='row' >
				<div class='col-md-4'>
					<label for="date_start" >تاریخ شروع آزمون</label>
					<div class="input-group" >
						<input type="text" id="date_start" name="date_start" value="<?= @$_GET['dateStart'] ?>" placeholder='تاریخ را انتخاب کنید و روی گزینه نمایش کلیک کنید' maxlength=""  class="form-control p-1" autocomplete="off" required />
						<div class="input-group-prepend">
							<span class="input-group-text p-2" id="btnCalc1">
							<i class='fas fa-1x fa-calendar-alt' ></i>
							</span>
						</div>
						<button type="button" id="btnShowCountExam"  class="btn btn-primary" >نمایش</button>
					</div>
				</div>
			</div>
			<div class="row" >
				<div class="col-md-12" id="showCountExam" ></div>
			</div>
			
			<script>
				var objCal1 = new AMIB.persianCalendar('date_start');
				$("#btnCalc1").click(function(){
				 objCal1.showHidePicker();
				});
				
				$("#btnShowCountExam").click(function()
					{
						var date_start=$("#date_start").val();
						
						if(date_start=="" || date_start==undefined)
						{
							return false;
						}
						
						$("#showCountExam").html("در حال دریافت  اطلاعات . . . ");
						$.post("ajaxCountExamHour.php",
							   {date_start:date_start},
							   function(data)
							   {
								$("#showCountExam").html(data);
							   }
							);
					}
				);
				
				
				var date_start=$("#date_start").val();
						
				if(date_start!="" )
				{
					$("#showCountExam").html("در حال دریافت  اطلاعات . . . ");
					$.post("ajaxCountExamHour.php",
					   {date_start:date_start},
					   function(data)
					   {
						$("#showCountExam").html(data);
					   }
					);
					
				}
			</script>
        </div>
        <?php require_once "footer.php"?>
    <body>
</html>
