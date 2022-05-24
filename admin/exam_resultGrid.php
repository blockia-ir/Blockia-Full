<?php require_once "blockPageLoader.php";


	
$table="{$DB->tablePrefix}exam_result";//define var
$where=" {$DB->tablePrefix}exam_result.block_user_id='$blockUserId'  AND ";
if(is_numeric(@$exam_id))
{
	$where.=" {$DB->tablePrefix}exam_result.exam_id=<bind>$exam_id</bind> AND";
}
if(isset($where))
	$where=$where;
else
	$where="";

$allowSearch=array("exam_id","class_name","student_name");
//search if set
if(!empty($_POST['searchData']))
{
  $searchData=strip_tags($_POST['searchData']);
  $searchData=json_decode($searchData,true);
  
  if(!is_array($searchData))
  {
    exit("error: search data not valid");
  }
  
  foreach($searchData as $field=>$search)
  {
    if(!in_array($field,$allowSearch))
    {
      exit("error:  don't allow search in $field");
    }
    
    if($search['value']!="" && $search['dataType']=="text")
    {
        $serachValue=strip_tags(addslashes($search['value']));
        $where.="(`$table`.`$field`=<bind>$serachValue</bind> OR `$table`.`$field` LIKE <bind>%$serachValue%</bind> ) AND ";
    }
		elseif($search['value']!="" && $search['dataType']=="select")
		{
			   $serachValue=strip_tags(addslashes($search['value']));
        $where.=" `$table`.`$field`=<bind>$serachValue</bind> AND ";

		}

  }
}

$where=$Func->strReplaceLast("AND","",$where);

if(!empty($where))
{
    $where="WHERE $where";
}
$pageRow=100;
$pageNum=1;

$countRow=$DB->queryOutPut("SELECT count(0) FROM `$table` $where");
$lastPage=ceil($countRow/$pageRow);


if(isset($_POST['pageNum'])){
   $pageNum=strip_tags($_POST['pageNum']);
}

if(!is_numeric($pageNum))
{
    exit("error  page num not valid");
}

if($pageNum<1)
    $pageNum=1;
    
$limit="LIMIT ".($pageNum-1)*$pageRow.",".$pageRow;


$listExamResult=$DB->query("SELECT {$DB->tablePrefix}exam_result.*,{$DB->tablePrefix}class_student.code FROM {$DB->tablePrefix}exam_result 
                LEFT JOIN {$DB->tablePrefix}class_student on 
                {$DB->tablePrefix}exam_result.class_student_id={$DB->tablePrefix}class_student.id
               $where $limit");

?>


    <?php $Func->pagination("exam_resultGrid.php","exam_resultGrid",$countRow,$pageRow,$pageNum);?>
    <table class="table table-striped" id="tableResult" style="width:100%" >
    <thead class="">
        <tr class=" text-white">
            <th class="p-1">کلاس</th>
            <th class="p-1">کد</th>
            <th class="p-1">نام</th>
            <th class="p-1">وضعیت</th>
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
            <td class="p-2"><?php if($value->marked==1) echo "تصحیح شده";?></td>
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
                    <label for="studentName" >نام دانش آموز</label>
                    <input type="text" id="studentName" class="form-control" />
                    
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
    
  });
    

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
    $("#studentName").val(student_name);
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
    var student_name=$("#studentName").val();
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

