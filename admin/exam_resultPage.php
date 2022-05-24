<?php require_once "blockPageLoader.php"; ?>
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
            
            $exam_id="";//define exam_id
            
            //check user select exam
            if(!empty($_GET['exam_id']))
            {
                
              $exam_id=$Encryption->decode(strip_tags($_GET['exam_id']));
              if(!is_numeric($exam_id))
                exit("error: exam id not valid");
                
                // get select exam form listExam
                $exam=$listExam[array_search($exam_id,array_column($listExam,"id"))];
                
                // get list exam result
                $listExamResult=$DB->query("SELECT {$DB->tablePrefix}exam_result.*,{$DB->tablePrefix}class_student.code FROM {$DB->tablePrefix}exam_result 
										LEFT JOIN {$DB->tablePrefix}class_student on 
										{$DB->tablePrefix}exam_result.class_student_id={$DB->tablePrefix}class_student.id
										AND {$DB->tablePrefix}exam_result.exam_id='$exam_id' 
										WHERE {$DB->tablePrefix}exam_result.block_user_id='$blockUserId' AND {$DB->tablePrefix}exam_result.exam_id='$exam_id' ");
                $exam_name=$exam->name; 
                $exam_type=$exam->type; 
            }
            
             
            ?>
            <?=$Msg->show()?>
            <?php if(count($listExam)==0):?>
            <div class="alert  alert-danger" >
                شما هیچ آزمونی ثبت نکرده اید !
            </div>
            <?php endif?>
            <div class="d-print-none" >
            <div class="row" >
              <div class="col-md-4">
                <form action="exam_resultPage.php" method="get" >
                  <select name="exam_id" id="exam_id" class="form-control" required  >
                    <option value="">آزمون را انتخاب کنید</option>
                    <?php foreach($listExam as $value):?>
                    <option value="<?=$Encryption->encode($value->id)?>"  <?= ($value->id==$exam_id) ? "selected":"" ?> ><?= $value->name?></option>
                    <?php endforeach ?>
                  </select>
                  <button type="submit" class="btn btn-primary mt-1" >نمایش نتیجه آزمون</button>
				  <button type="button" class="btn btn-secondary mt-1" data-toggle="modal" data-target="#modalHelpExamResult"> راهنما</button>

                </form>
              </div>
            </div>
            
            </div>
            <div id="listExamResult" ></div><br/><br/>
            
            <?php if(@$exam_id>0):?>
            
            <button class='btn btn-secondary d-print-none' style="margin-right: 16px;margin-bottom: -16px; width: 200px;" data-toggle="modal" data-target="#modalDeleteAllZeroMark" >
                حذف تمام نمرات خالی
            </button>
            
            <div  class="p-3" style="max-width:100%;overflow-x:auto;" >
                <table class="  w-100 display" id="tableResult" style="width:100%" >
                <thead class="">
                    <tr class=" text-white">
                        <th class="p-1">کلاس</th>
                        <th class="p-1">کد</th>
                        <th class="p-1">نام</th>
                        <th class="p-1"><?=(@$exam_type==2 ? "وضعیت":"")?></th>
                        <th class="p-1">نمره</th>
                        <th class="p-1">درست</th>
                        <th class="p-1">غلط</th>
                        <th class="p-1">بدون پاسخ</th>
                        <th class="p-1">درصد</th>
                        <th class="p-1">آی پی</th>
                        <th class="p-1">زمان شروع</th>
                        <th class="p-1">زمان پایان</th>
                        <th class="p-1">پاسخنامه</th>
                        <th class="p-1 d-print-none">ویرایش</th>
                        <th class="p-1 d-print-none">حذف</th>
                    </tr>
                </thead>
                <?php
                  unset($value);
                  foreach($listExamResult as $value):
                
                        $format = 'Y-m-d H:i:s';
                        $date = DateTime::createFromFormat($format, $value->date_create);
                        $time=$date->format('H:i:s');
                  
                        $time_finsh="";
                        if($value->date_finsh!="")
                        {
                          $date_date_finsh = DateTime::createFromFormat($format, $value->date_finsh);
                          $time_finsh=$date_date_finsh->format('H:i:s');
                        }
                   
                  ?>
                    <tr>
                        <td class="p-2"><?=$value->class_name?></td>
                        <td class="p-2"><?=$value->code?></td>
                        <td class="p-2 name" id='name<?=$value->id?>' ><?=($value->student_name)?></td>
                        <td class="p-2"><?php if(@$exam_type==2){if($value->marked==1)echo "تصحیح شده";}?></td>
                        <td class="p-2" id='mark<?=$value->id?>' ><?=($value->mark!="" ? $value->mark+0:"")?></td>
                        <td class="p-2" id='true<?=$value->id?>' ><?=$value->count_true?></td>
                        <td class="p-2" id='false<?=$value->id?>' ><?=$value->count_false?></td>
                        <td class="p-2" id='empty<?=$value->id?>' ><?=$value->count_empty?></td>
                        <td class="p-2" id='percent<?=$value->id?>' ><?=$value->percent?></td>
                        <td class="p-2"><?=$value->ip?></td>
                        <td class="p-2"><?=$time?></td>
                        <td class="p-2"><?=@$time_finsh?></td>
                        <td class="p-2  ">
                          <a href="answerSheet.php?i=<?=$Encryption->encode( $value->id)?>" target="_blank"
                        onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,left=300,width=600,height=600');return false;" >پاسخنامه</a>
                        </td>          
                   <td class="d-print-none"><button class="btn btn-warning btn-sm btnEdit" data-id="<?=$value->id?>" data-toggle="modal" data-target="#modalEditExamResult" >ویرایش</button></td>	
                  <td>
                        <a class="btnDelete d-print-none" data-toggle="modal" data-target="#confirmDelete" href="#"
                        data-href="deleteExamResult.php?id=<?= $Encryption->encode($value->id) ?>" ><i class="iconDelete fas fa-times " ></i></a>
                        </td>
                    </tr>
                    <?php endforeach?>
                
                </table>
            </div>
            
            <link rel="stylesheet" href="../lib/datatable/jquery.dataTables.min.css" />
            <script src="../lib/datatable/jquery.dataTables.min.js" ></script>
            <script src="../lib/datatable/dataTables.buttons.min.js" ></script>
            <script src="../lib/datatable/jszip.min.js" ></script>
            <script src="../lib/datatable/buttons.print.min.js" ></script>
            <!--<script src="../lib/datatable/pdfmake.min.js"  charset='utf-8' ></script>
            <script src="../lib/datatable/vfs_fonts.js"  charset='utf-8' ></script>-->
            <script src="../lib/datatable/buttons.html5.min.js" ></script>
            <script>
            
            $(document).ready( function () {
                $('#tableResult').DataTable({
                        paging: false,
						scrollX: 1000,
                        dom: 'Bfrtip',

                        buttons: [
                            {
                                extend: 'copyHtml5',
                                text: 'کپی',
                                className:'btn btn-info mb-1 d-print-none'
                            },
                            {
                                extend: 'excelHtml5',
                                text: 'خروجی اکسل',
                                className:'btn btn-success mb-1 d-print-none',
                                title:'<?=@$exam_name?>',
                                exportOptions : {columns: [ 0,1, 2, 4, 5,6,7,8,9,10,11 ]}
                            },
                            {
                                        text: 'چاپ',
                                        className:'btn btn-warning mb-1 d-print-none',
                              action: function ( e, dt, node, config ) {window.print();}
                            },
                            /*
                            {
                              text: ' چاپ کلی پاسخنامه  ', className:'btn btn-warning mb-1 d-print-none',
                              action: function ( e, dt, button, config )
                              {
                                window.location = 'answerSheetAll.php?exam_id=<?=$Encryption->encode(@$exam_id)?>';
                              }        
                            },
                            */
                        ],
                        "language": {"url": "../lib/datatable/dataTables.fa.lang"},
                        complete: function () {
                        $('div.dataTables_filter input').addClass("form-control  d-print-none");
                    }
                    });
                
            
            } );
            </script>
            
            <style>
            div.dataTables_filter input {
                background: #fff !important;
                width: 200px;
                float: right;
            }
            @media screen and (max-width: 640px){
                .dataTables_wrapper .dataTables_length,
                .dataTables_wrapper .dataTables_filter{
                    float: right;
                }
            }
            </style>
            <?php endif?>
            <!-- Button to Open the Modal -->
            
            <!-- The Modal -->
            <div class="modal fade" id="modalEditExamResult">
              <div class="modal-dialog">
                <div class="modal-content">
            
                  <!-- Modal Header -->
                  <div class="modal-header">
                    <p class="modal-title">ویرایش نتیجه آزمون </p>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
            
                  <!-- Modal body -->
                  <div class="modal-body">
                    <input type="hidden" id="exam_result_id" />
                    <label for="student_name" >نام دانش آموز</label>
                    <input type="text" id="student_name" class="form-control" />
                    
                    <label for="mark" >نمره</label>
                    <input type="number" id="mark" class="form-control" step="0.25" /> 
                    
                    <label for="countTrue" >تعداد درست</label>
                    <input type="number" id="count_true" class="form-control" />
                    
                    <label for="countFalse" >تعداد غلط</label>
                    <input type="number" id="count_false" class="form-control" />
                    
                    <label for="countEmpty" >تعداد بی پاسخ</label>
                    <input type="number" id="count_empty" class="form-control" />
                    
                    <label for="percent" >درصد</label>
                    <input type="number" id="percent" class="form-control" />
                  </div>
                  
            
                  <!-- Modal footer -->
                  <div class="modal-footer">
                <div id="msgEditExamResult" class="text-right" ></div>
                    <button type="button" id="btnEditExamResult" class="btn btn-primary" >ثبت نتیجه</button> 
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button> 
                  </div>
            
                </div>
              </div>
            </div>
            
            
            <!-- The Modal -->
            <div class="modal fade" id="modalDeleteAllZeroMark">
              <div class="modal-dialog">
                <div class="modal-content">
            
                  <!-- Modal Header -->
                  <div class="modal-header">
                  <p class="modal-title">حذف نمرات خالی </p>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
            
                <!-- Modal body -->
                <div class="modal-body">
                  <?php if(@strtotime($exam->date_end)<strtotime("now")):?>
                  <div class='text text-danger m-3 h5' >
                    آیا از حذف تمام  نمرات خالی آزمون <span class='text-dark h5' ><?=@$exam_name?></span> اطمینان دارید ؟ 
                  </div>	
                  <p></p>
                  <p class='h6' >
                  با استفاده از این روش می
                  تونید نمرات خالی تکراری را حذف کنید. نگران نباشید فقط نمرات  خالی حذف می شوند
                  </p>
                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">پشیمون شدم</button>
                    <a href="deleteAllEmptyMark.php?id=<?=$Encryption->encode(@$exam_id)?>" id="btnDeleteAllEmptyMark" class="btn btn-danger" >حذف تمام نمرات خالی</a>  
                  </div>
                  <?php else:?>
                    <p class='h6' >لطفا صبر کنید تا زمان آزمون به پایان برسد</p>
                  <?php endif?>
                </div>
              </div>
              </div>
            </div>
            
            
            <div class="modal fade" id="confirmDelete"  >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                          <p class="text-danger bold h5" > <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> هشدار حذف نتیجه دانش  آموز</p>
                        </div>
                        <div class="modal-body">
                          <p class=" bold h5" >
                            آیا از حذف نتیجه دانش آموز <span class="text-danger h5 bold" id="deleteRecord" ></span> اطمینان دارید؟
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" >پشیمون شدم</button>
                            <a class="btn btn-danger btn-ok"> <i class="fas fa-trash-alt"></i> حذف کن</a>
                        </div>
                    </div>
                </div>
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
					<p>
						برای مرتب سازی کافی است روی نام ستون ها در جدول نتایج کلیک کنید
						<br/>
						<img src="../help/h03.png" class="mw-100 img-thumbnail" />
					</p>
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
            
            
            <script>
              $(document).ready(function()
              {
                $(".btnDelete").click(function() {
                  var $text = $(this).closest("tr").find(".name").text();
                  $("#deleteRecord").text($text);
                });
                $('#confirmDelete').on('show.bs.modal', function(e) {
                  $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
                });
            
                $('#exam_id').select2({theme: 'bootstrap4'});
                
              });
                
            </script>
            <script>
                var exam_result_id="";
                var student_name="";
                var mark="";
                var count_true="";
                var count_false="";
                var count_empty="";
                var percent="";
                
            $(document).on("click",".btnEdit",
              function(){
                var exam_result_id=$(this).attr("data-id");
                var student_name=$("#name"+exam_result_id).text();
                var mark=$("#mark"+exam_result_id).text();
                var count_true=$("#true"+exam_result_id).text();
                var count_false=$("#false"+exam_result_id).text();
                var count_empty=$("#empty"+exam_result_id).text();
                var percent=$("#percent"+exam_result_id).text();
                
                $("#exam_result_id").val(exam_result_id);
                $("#student_name").val(student_name);
                $("#mark").val(mark);
                $("#count_true").val(count_true);
                $("#count_false").val(count_false);
                $("#count_empty").val(count_empty);
                $("#percent").val(percent);	
              }
            );
            
            $("#btnEditExamResult").click(function()
              {
                var exam_result_id=$("#exam_result_id").val();
                var student_name=$("#student_name").val();
                var mark=$("#mark").val();
                var count_true=$("#count_true").val();
                var count_false=$("#count_false").val();
                var count_empty=$("#count_empty").val();
                var percent=$("#percent").val();
                
                if(exam_result_id=="" || exam_result_id==undefined)
                {
                  $("#msgEditExamResult").html("<span class='text-danger' >شماره برگه خالی است</span>");
                  return false;
                }
                if(student_name=="" || student_name==undefined)
                {
                  $("#msgEditExamResult").html("<span class='text-danger' >نام را وارد کنید</span>");
                  return false;
                }
                
                if(mark=="" || mark<0 || mark>20)
                {
                  $("#msgEditExamResult").html("<span class='text-danger' >لطفا نمره را به  طور صحیح وارد کنید</span>");
                  return false;
                }
                if(count_true=="")
                {
                  $("#msgEditExamResult").html("<span class='text-danger' >لطفا تعداد درست را وارد کنید</span>");
                  return false;
                }
                if(count_false=="")
                {
                  $("#msgEditExamResult").html("<span class='text-danger' >لطفا تعداد غلط را وارد کنید</span>");
                  return false;
                }
                if(count_empty=="")
                {
                  $("#msgEditExamResult").html("<span class='text-danger' >لطفا تعداد بی پاسخ را وارد کنید</span>");
                  return false;
                }
                if(percent=="" || percent<0 || percent>100)
                {
                  $("#msgEditExamResult").html("<span class='text-danger' >لطفا درصد را وارد کنید</span>");
                  return false;
                }
                $("#msgEditExamResult").html("در حال ویرایش نتیجه آزمون . . . ");
                $.post("ajaxEditExamResult.php",
                    {
                      exam_result_id:exam_result_id,
                      student_name:student_name,
                      mark:mark,
                      count_true:count_true,
                      count_false:count_false,
                      count_empty:count_empty,
                      percent:percent
                    },
                    function(data)
                    {
                      if(data=="true")
                      {
                        $("#name"+exam_result_id).text(student_name);
                        $("#mark"+exam_result_id).text(mark);
                        $("#true"+exam_result_id).text(count_true);
                        $("#false"+exam_result_id).text(count_false);
                        $("#empty"+exam_result_id).text(count_empty);
                        $("#percent"+exam_result_id).text(percent);
                        
                        $("#msgEditExamResult").html("<span class='text-success' >نتیجه آزمون ویرایش شد</span>");
                      }
                      else
                      {
                        $("#msgEditExamResult").html("<span class='text-danger' >"+data+"</span>");
                      }
                      
                    }
                );
              }
            );
            </script>

        </div>
        <?php require_once "footer.php"?>
    <body>
</html>
