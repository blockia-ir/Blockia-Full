<?php
require_once "blockPageLoader.php";

$table="{$DB->tablePrefix}exam";//define var
$where=" {$DB->tablePrefix}exam.block_user_id='$blockUserId' AND " ;
if(isset($where))
	$where=$where;
else
	$where="";

$allowSearch=array("name");
//search if set
if(isset($_POST['searchData']))
{
  $searchData=strip_tags($_POST['searchData']);
  $searchData=json_decode($searchData,true);
  
  if(!is_array($searchData))
  {
    exit("error search data not valid");
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

  }
  
}

$where=$Func->strReplaceLast("AND","",$where);

if(!empty($where))
{
    $where="WHERE $where";
}
$pageRow=200;
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

$listExam=$DB->query("SELECT {$DB->tablePrefix}exam.*,
										 (SELECT count(id) FROM {$DB->tablePrefix}exam_question
										 WHERE exam_id={$DB->tablePrefix}exam.id) as countQuestion
										 FROM {$DB->tablePrefix}exam $where  ORDER BY id DESC $limit");
//$countRow=count($listExam);

?>

<div id="examGrid" style="max-width:100%;overflow-x:auto;" >
	<?php $Func->pagination("examGrid.php","examGrid",$countRow,$pageRow,$pageNum);?>
	<table class="table table-striped  border w-100 "  >
		<thead>
			<tr class="">
				<th class="">نام امتحان</th>
				<th class="">تعداد سوال</th>
				<th class="">مدت آزمون </th>
				<th class="">تاریخ  شروع</th>
				<th class="">تاریخ پایان</th>
				<th class="d-none d-md-table-cell">کد آزمون</th>
				<th class=" d-print-none">ویرایش</th>
				<th class=" d-print-none">حذف</th>
			</tr>
		</thead>
		<?php foreach($listExam as $value):	?>
		<tr>
			<td class="name"><a class="text-primary" href="examForm.php?id=<?=$Encryption->encode($value->id)?>" ><b><?=$value->name?></b></a></td>
			<td class=""><?=$value->countQuestion?></td>
			<td class=""><?=$value->duration?></td>
			<td class=""><?=$Jdf->convertToJalali(substr($value->date_start,0,-3))?></td>
			<td class=""><?=$Jdf->convertToJalali(substr($value->date_end,0,-3))?></td>
			<td class="d-none d-md-table-cell"><?=$value->id?></td>
			<td width="40" ><a class="" href="examForm.php?id=<?=$Encryption->encode($value->id)?>" ><i class="iconEdit fas fa-edit"></i></a></td>
			<td width="40" ><a class='btnDelete' data-href='deleteExam.php?exam_id=<?=$Encryption->encode($value->id)?>' data-toggle='modal' data-target='#confirm-delete' href='#' ><i class='iconDelete fas fa-times '></i></a></td>
		</tr>
		<?php endforeach?>
		
	</table>
  <?php //$Script->pagination("examGrid.php","examGrid",$countRow,$pageRow,$pageNum);?>
</div>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <p class="text-danger bold h5" > <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> هشدار حذف آزمون</p>
            </div>
            <div class="modal-body">
              <p class=" bold h5" >
               آیا از حذف آزمون <span class="text-danger h5 bold" id="deleteRecord" ></span> اطمینان دارید؟
              </p>
							 <div class="alert alert-danger">
								توجه کنید تمام نمرات دانش آموزان در قسمت نتایج برای این آزمون نیز حذف می شوند. این عمل غیرقابل بازگشت است
							 </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" >پشیمون شدم</button>
                <a class="btn btn-danger btn-ok"> <i class="fas fa-trash-alt" aria-hidden="true"></i> حذف کن</a>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function(){
		$(".btnDelete").click(function() {
			var $text = $(this).closest("tr").find(".name").text();
			$("#deleteRecord").text($text);
		});
		$('#confirm-delete').on('show.bs.modal', function(e) {
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
		});
	});
</script>


