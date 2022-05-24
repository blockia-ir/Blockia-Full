<?php require_once "blockPageLoader.php";

$exam_id="";
if(!empty($_GET['exam_id']))
{
    $exam_id=$Encryption->decode($_GET['exam_id']);
    if(!is_numeric($exam_id))
        exit("error: exam id not valid");
}
?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>نتایج آزمون</title>
        <link rel="icon" type="image/png" href="../images/iconExam2.png" />
        <link rel="stylesheet" href="../lib/fonts/font.css"  />
        <link rel="stylesheet" href="../lib/font-awesome/css/fontawesome-all.min.css" >
        <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" />
        <link rel="stylesheet" href="../css/panel.css" />
        <link rel="stylesheet" href="../lib/select2/css/select2.min.css" />
        <link rel="stylesheet" href="../lib/select2/css/select2-bootstrap4.min.css" />
        <script src="../lib/js/jquery.min.js"></script>
        <script src="../lib/js/function.js"></script>
        <script src="../lib/select2/js/select2.min.js"></script>
    </head>
    <body class="rtl" >
        <?php require_once "menu.php"?>
        <div class="container-fluid">
            <?php
            
            //get list exam teacher 
            $listExam=$DB->query("SELECT * FROM {$DB->tablePrefix}exam WHERE block_user_id='$blockUserId' ORDER BY id DESC ");          
            
            if(count($listExam)==0)
                echo '<div class="alert  alert-danger" >شما هیچ آزمونی ثبت نکرده اید !</div>';
                
            echo $Msg->show();
            ?>      
            
            <div class="row p-0" id="search"  >
                <div class="col-md-2 p-1">              
                    <select name="exam_id" id="exam_id"  data-type="select" class="form-control searchInput" required  >
                        <option value="">آزمون را انتخاب کنید</option>
                        <?php foreach($listExam as $value):?>
                        <option value="<?=$value->id?>" <?=($exam_id==$value->id ? "selected" :"")?> > <?= $value->name?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1 p-1">
                    <input type="text" id="class_name" data-type="text" class="form-control searchInput w-100" placeholder="کلاس"  />
                </div>
                <div class="col-md-1 p-1">
                    <input type="text" id="student_name" data-type="text" class="form-control searchInput w-100" placeholder="نام دانش آموز"  />
                </div>
                <div class="col-md-1 col-4 p-1">
                    <button type="button" id="btnSetFilter" class="btn btn-primary w-100" >نمایش نتیجه</button>
 
                </div>
                <div class="col-md-1 col-4 p-1">
                    <button id="btnExport" class="btn btn-success w-100" >خروجی اکسل</button>
                </div>
<!--                <div class="col-md-1 p-0">
                    <button class='btn btn-secondary d-print-none w-100'  data-toggle="modal" data-target="#modalDeleteAllZeroMark" >
                        حذف  نمرات خالی
                    </button>
                </div>-->
                <div class="col-md-1 col-4 p-1" >
                    <button type="button" class="btn btn-secondary w-100" data-toggle="modal" data-target="#modalHelpExamResult"> راهنما</button>
                </div>
            </div>
            <div id="exam_resultGrid"  style="max-width:100%;overflow-x:auto" >
                <?php
                if(!empty($exam_id))
                {
                    require_once "exam_resultGrid.php";
                }
                ?>
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
                    $("#exam_resultGrid").html("در حال دریافت اطلاعات . . . ");
                    $.post("exam_resultGrid.php",{searchData:searchData},function(data)
                        {
                          $("#exam_resultGrid").html(data);
                        }
                      );
                 });
                 
                $("#btnExport").click(function(){
                    searchData="";
                    $(".searchInput").each(function(){
                      if($(this).attr("id")!==undefined){
                        searchData+='"'+$(this).attr("id")+'":{"value":"'+$(this).val()+'","dataType":"'+$(this).attr("data-type")+'"},';
                      }
                    });
                    searchData=searchData.replace(/,\s*$/, "");
                    searchData="{"+searchData+"}";
                    $("#searchData").html(searchData);
                    window.open('exportExamResult.php?searchData='+searchData, '_blank'); 
                });
                
                $('#exam_id').select2({theme: 'bootstrap4'});
            </script>


        </div>
        <!-- Modal Help Exam Result -->
			<div id="modalHelpExamResult" class="modal fade rtl" role="dialog">
			  <div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header ">
					<p class="modal-title"><b>راهنمای نتایج آزمون</b></p>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				  </div>
				  <div class="modal-body">
					<p>
						برای نمایش نتایج ابتدا آزمون  را انتخاب کرده و سپس روی گزینه  نمایش نتیجه کلیک کنید
					</p>
					<!--<p>
						برای مرتب سازی کافی است روی نام ستون ها در جدول نتایج کلیک کنید
						<br/>
						<img src="../help/h03.png" class="mw-100 img-thumbnail" />
					</p>-->
					<p>
						در صورتیکه از گوشی استفاده می کنید و تمام ستونها جدول را مشاهده نمی کنید
						جدول نتایج  را به سمت راست بکشید <br/>
						<img src="../help/h04.png" class="mw-100 img-thumbnail" />
					</p>
					<p  >
						برای تصحیح سوالات  تشریحی روی گزینه  پاسخنامه  کلیک کنید
						<br/>
						<img src="../help/h05.png" class="mw-100 img-thumbnail" />
					</p>
					<p>
						توجه کنید در آزمون های تشریحی تعداد درست،تعداد غلط بی معنی است
						چرا که احتمال دارد یک دانش آموز به طور ناقص  به سوال پاسخ دهد
						در این  صورت  نه کاملا درست خواهد بود و نه  کاملا غلط
						با توجه به اینکه تعداد درست وغلط در آزمون تشریحی بی معنی است
						محاسبه  درصد  در آزمون  تشریحی برحسب نمره کسب شده تعیین خواهد شد
						نه تعداد درست و غلط
					</p>
					
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
				  </div>
				</div>

			  </div>
			</div>
        <?php require_once "footer.php"?>
    <body>
</html>
