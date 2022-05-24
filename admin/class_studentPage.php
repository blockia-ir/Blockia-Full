<?php require_once "blockPageLoader.php";
$listClass=$DB->query("SELECT * FROM {$DB->tablePrefix}class WHERE block_user_id='$blockUserId' ");
$countClass=count($listClass);
if($countClass==0)
{
  $Msg->error("پیش از استفاده از قسمت دانش آموزان ابتدا باید کلاس ها خود را وارد کنید");
  header("location:classForm.php");
  exit;
}
?>
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
<div class="alert alert-info p-1" >
     <p>
          <b>این  قسمت اختیاری است</b><br/>
          در صورتیکه می خواهید فقط دانش آموزان  خودتان در امتحان  شرکت کنند و یا مایل نیستید که یک دانش آموز بیش از یکبار امتحان بدهد می توانید از این قسمت استفاده کنید. در غیر اینصورت می توانید از این قسمت  صرف نظر کنید.
     </p>
</div>

<?= $Msg->show() ?>
<div id="search"  >
<input type="text" id="name" data-type="text" class="form-control searchInput" placeholder="نام دانش آموز"  />
<input type="text" id="code" data-type="text" class="form-control searchInput" placeholder="کد ورود " style="max-width: 150px;"  />
<button type="button" id="btnSetFilter" class="btn btn-primary mt-1" >جستجو</button>
<a href="class_studentForm.php" class="btn btn-warning mt-1" >درج دانش آموز</a>
<a href="insertGroupStudentPage.php" class="btn btn-success mt-1" >درج گروهی</a>
<a href="link_register_student.php" class="btn btn-secondary mt-1"  >درج از طریق لینک</a>
<button type="button" class="btn btn-info mt-1" data-toggle="modal" data-target="#modalHelpClassStudent">
راهنمای استفاده از دانش آموزان
</button>
<button type="button" class="btn btn-danger mt-1" data-toggle="modal" data-target="#modalDeleteGroupClassStudent">
 <i class="fas fa-trash-alt" aria-hidden="true"></i> حذف گروهی
</button>
</div>
<script>
    var searchData="";
     $("#btnSetFilter").click(function(){
        searchData="";
        $(".searchInput").each(function(){
          if($(this).attr("id")!==undefined)
          {
            searchData+='"'+$(this).attr("id")+'":{"value":"'+$(this).val()+'","dataType":"'+$(this).attr("data-type")+'"},';
          }
        });
        searchData=searchData.replace(/,\s*$/, "");
        searchData="{"+searchData+"}";
        $("#searchData").html(searchData);
        $.post("class_studentGrid.php",{searchData:searchData},function(data)
            {
              $("#class_studentGrid").html(data);
            }
          );
     });
</script>

  <?php require_once "class_studentGrid.php"; ?>



<!-- Modal Help Class Student -->
<div id="modalHelpClassStudent" class="modal fade rtl" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header ">
        <p class="modal-title"><b>راهنمای استفاده از قسمت دانش آموزان</b></p>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>
         به منظور اینکه  دانش آموزان از طریق کد وارد آزمون  بشوند پس از اینکه  دانش آموزان را وارد کردید و کد های ورود در اختیار آنها قرار دادید. وارد قسمت تنظیمات آزمون شده و حالت ورود به آزمون بر روی گزینه  ورود فقط با کد تنظیم کنید. و تنظیمات را ذخیره کنید.
        </p>

        <img src="../images/helpClassStudent.png" style="max-width: 100%" />
		<p class='alert alert-warning mt-2 text-danger blod' >توجه کنید دانش آموزانی می توانند با کد در آزمون  شرکت کنند که کلاس آنها در آزمون ثبت شده باشد</p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal DElETE Group Class Student -->
<div id="modalDeleteGroupClassStudent" class="modal fade rtl" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <p class="text-danger bold h5" > <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> هشدار حذف دانش آموزان</p>
            </div>
            <div class="modal-body">
              <p class=" bold h6" >
               به منظور حذف گروهی دانش آموزان ابتدا کلاس خود را انتخاب کنید. سپس روی گزینه حذف کلیک کنید
              </p>
              <form action="deleteGroupClassStudent.php" method="get" >
                <label for="class_id" >کلاس خود را انتخاب کنید</label>
                <select name="class_id" id="class_id" class="form-control" required >
                  <option value="" ></option>
                  <?php foreach($listClass as $class):?>
                    <option value="<?= $Encryption->encode($class->id) ?>" ><?= $class->name ?></option>
                  <?php endforeach?>
                </select>
                
                <label for="i_sure" >آیا از حذف دانش آموزان کلاس اطمینان دارید؟</label>
                <select name="i_sure" id="i_sure" class="form-control" required >
                  <option value="" >خیر</option>
                  <option value="1" >بله</option>
                </select>
                <br/><br/>
                
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" >پشیمون شدم</button>
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash-alt" aria-hidden="true"></i> حذف کن</button>
              </div>
              
              </form>
            </div>
        </div>
    </div>
</div>

	</div>
<?php require_once "footer.php"?>
<body>
</html>
