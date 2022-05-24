<?php include "blockPageLoader.php";/*must be top of page*/ ?>
<!DOCTYPE html>
<html lang="fa">
  <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>لیست آزمونها</title>
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
		<div class="pageTitle" >لیست آزمونهای تستی و تشریحی</div>
		<?= $Msg->show() ?>
		<div id="search"  >
		<input type="text" id="name" data-type="text" style="width:270px;" class="form-control mb-1 searchInput" placeholder="نام امتحان"  />
		<button type="button" id="btnSetFilter" class="btn   btn-primary" >جستجو</button>
    <div class="btn-group">
      <button type="button" class="btn btn-warning rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        درج آزمون جدید
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="examForm.php?type=2" >آزمون تستی و تشریحی</a>
        <a class="dropdown-item" href="examForm.php?type=1" >آزمون تستی</a>
      </div>
    </div>
    <button type="button" class="btn btn-secondary " data-toggle="modal" data-target="#modalHelpExam"> راهنما</button>

		
		</div>
		<script>
      var searchData="";
			 $("#btnSetFilter").click(function(){
				searchData="";
				$(".searchInput").each(function(){
				  if($(this).attr("id")!==undefined){
					searchData+='"'+$(this).attr("id")+'":{"value":"'+$(this).val()+'","dataType":"'+$(this).attr("data-type")+'"},';
				  }
				});
				searchData=searchData.replace(/,\s*$/, "");
				searchData="{"+searchData+"}";
				$("#searchData").html(searchData);
				$.post("examGrid.php",{searchData:searchData},function(data)
					{
					  $("#examGrid").html(data);
					}
				  );
			 });
		</script>
		<div style="max-width:100%;overflow:auto;" >
		 <?php require_once "examGrid.php"; ?>   
		</div>

	</div>
<?php require_once "footer.php" ?>
<!-- Modal Help register Student -->
<div id="modalHelpExam" class="modal fade rtl" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header ">
        <p class="modal-title"><b>راهنمای آزمون</b></p>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p class="text-justify" >
          <b>آزمون تشریحی:</b> <br/>
          در آزمون  تشریحی می توانید سوالات  تستی، صحیح وغلط و تشریحی درج کنید.
          بارم  هر سوال  به صورت جداگانه  و به صورت دستی  تعیین می شود.    
        </p>
        <p class="text-justify">
          <b>آزمون تستی:</b><br/>
          در آزمون  تستی  می توانید  سوالات  چهارگزینه ای و صحیح و غلط درج کنید
          بارم سوالات به صورت خودکار تعیین می شود.
          می توانید برای آزمون تستی نمره منفی در نظر بگیرید.
          همچنین می توانید تعیین کنید که از بین سوالات آزمون برای هر دانش آموز
          چند سوال به صورت تصادفی نمایش داده شود.
        </p>
        <p class="text-justify" >
          <b>تبدیل آزمون: </b><br/>
          پس از ثبت آزمون  می توانید آزمون  تستی را به تشریحی و تشریحی را به تستی تبدیل کنید.
          برای اینکار از گزینه  تبدیل استفاده کنید <br/>
          <img src="../help/h06.png" class="mw-100 img-thumbnail" />
        </p>
        <p class="text-justify" >
          <b>تنظیمات آزمون:</b>  <br/>
          برای دسترسی به  تنظیمات آزمون  پس از اینکه آزمون  را ثبت کردید برای گزینه
          <i class='fas fa-1x fa-cog' ></i>
          کلیک کنید.
          توجه کنید تمامی تنظیمات باید پیش از شروع  آزمون  اعمال شود.
          پس از اینکه دانش آموزان وارد آزمون شدند. تغییر در تنظیمات آزمون هیچ  تاثیری
          در نتیجه آزمون  دانش آموز نخواهد گذاشت.
          <img src="../help/h07.png" class="mw-100 img-thumbnail" />
        </p>
        <p class="text-justify">
          <b>مدت امتحان: </b> <br/>
          زمانی است که دانش آموز اجازه دارد به سوالات آزمون پاسخ دهد. 
          در صورتیکه دانش آموز با تاخیر وارد آزمون شود به طوری که پس از ورود 
          زمان باقیمانده تا پایان آزمون کمتر از مدت امتحان باشد. 
          از مدت امتحان دانش آموز کم میشود. تصور کنید آزمونی را به مدت 20 دقیقه تنظیم کردیده‌اید ساعت شروع آزمون را برابر با 10 و ساعت پایان  را برابر  با 11  قرار داده‌اید 
          در صورتیکه دانش آموز ساعت 10:50 وارد آزمون شود به دلیل اینکه تا پایان آزمون فقط 10 باقی است دانش آموز فقط 10 دقیقه برای پاسخ گویی به سوالات فرصت دارد.
           توجه کنید که همیشه باید اختلاف بین شروع و پایان آزمون بیشتر از مدت امتحان باشد.          
        </p>
        <p class="text-justify">
          <b>بارم سوالات: </b> <br/>
          در    تشریحی بارم  سوالات  باید به صورت  دستی تعیین شود.
          به صورت پیش فرض بارم هر سوال برابر با مقدار 1 است.
          که باکلیک در آن قسمت می توانید مقدار آن را تغییر دهید<br/>
          <img src="../help/h08.png" class="mw-100 img-thumbnail" /> <br/>
          بارم هر سوال می تواند مقداری بین 0.25 تا 5 نمره داشته باشد.
          <br/>
          توجه کنید که جمع نمرات  با نمره  آزمون  می بایست برابر باشد<br/>
          <img src="../help/h09.png" class="mw-100 img-thumbnail" /> 
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
