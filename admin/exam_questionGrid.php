<?php require_once "blockPageLoader.php";

$listExamQuestion=$DB->query("SELECT * FROM {$DB->tablePrefix}exam_question
                             WHERE block_user_id='$blockUserId' AND exam_id='$exam_id'
                            ORDER BY {$DB->tablePrefix}exam_question.ordr ASC, {$DB->tablePrefix}exam_question.id DESC " );

$sumScore=0;
foreach($listExamQuestion as $val)
  $sumScore+=floatval($val->score);
  
?>

<?php $answer=['a'=>'الف','b'=>'ب','c'=>'ج','d'=>'د']; $listOrder=""; ?>

<div class="small mt-2 mb-2" >
  <span>تعداد سوالات: <?=count($listExamQuestion)?></span>  &nbsp; &nbsp;
  <?php if($exam->type==2 && $exam->base_mark==$sumScore):?>
    <span>جمع نمرات: <?=$sumScore?></span>
  <?php elseif($exam->type==2 && $sumScore>0):?>
    <span class="bg-danger p-1 text-white rounded " data-toggle="tooltip" data-placement="top" title="جمع نمرات با نمره آزمون  برابر نیست"  > <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> جمع نمرات: <?=$sumScore?></span>
  <?php endif ?>
  
</div>
<table class="table table-striped table-condensed border display" id="tableQuestion">
  <thead>
    <tr>
	  <th class='d-none border' >#</th>
      <th>سوال</th>
      <th>بارم</th>
      <th class="d-none d-md-table-cell " >الف</th>
      <th class="d-none d-md-table-cell " >ب</th>
      <th class="d-none d-md-table-cell " >ج</th>
      <th class="d-none d-md-table-cell " >د</th>
      <th class="d-none d-md-table-cell d-print-none" >پاسخ</th>
      <th class="d-print-none" >ویرایش</th>
      <th class="d-print-none" >حذف</th>
    </tr> 
  </thead>
  <?php 
	$i=1;
	foreach($listExamQuestion as $question):
	$listOrder.='<li class="card" style="background:#f7f7f7;"  id="'.$question->id.'" data-id="'.$Encryption->encode($question->id).'" >'.$question->question.'</li>';
  ?>
  
  <tr>
	<td class='d-none border' ><?=$i++?></td>
    <td class='name' >
        <a class="text-dark text-decoration-none" href='examForm.php?id=<?=$Encryption->encode($exam_id)?>&question_id=<?=$Encryption->encode($question->id)?>#btnSetExam' >
          <?=strip_tags($question->question,"<img>")?>
        </a>
      </td>
    <td ><?=($question->score>0 ? $question->score+0:"")?></td>
    <td class="d-none d-md-table-cell " ><?=strip_tags($question->a,"<img>")?></td>
    <td class="d-none d-md-table-cell " ><?=strip_tags($question->b,"<img>")?></td>
    <td class="d-none d-md-table-cell " ><?=strip_tags($question->c,"<img>")?></td>
    <td class="d-none d-md-table-cell " ><?=strip_tags($question->d,"<img>")?></td>
    <td class="d-none d-md-table-cell d-print-none " ><?= @$answer[$question->answer] ?></td>
    <td width="50" class="d-print-none" ><a class='btnEdit' href='examForm.php?id=<?=$Encryption->encode($exam_id)?>&question_id=<?=$Encryption->encode($question->id)?>#btnSetExam' ><i class='iconEdit fas fa-edit'></i></a></td>
    <td width="50" class="d-print-none" ><a class='btnDelete' data-href='deleteExamQuestion.php?question_id=<?=$Encryption->encode($question->id)?>&exam_id=<?=$Encryption->encode($exam_id)?>' data-toggle='modal' data-target='#confirm-delete' href='#' ><i class='iconDelete fas fa-times '></i></a></td>
  </tr>
  <?php endforeach?>
</table>

<style>
  .table img {
    max-height: 100px;
  }
</style>
 

<!-- Modal -->
<div class="modal fade  " id="modalOrderSelectItem"   tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable " role="document">
    <div class="modal-content">
      <div class="modal-header">
    
		<p class='text-info p-0 m-0' > 
			به  صورت پیش فرض شیوه  نمایش سوالات در آزمون  به  صورت  تصادفی است.  در صورتیکه  چیدمان سوالات به  صورت دستی تغییر  دهید شیوه  نمایش سوالات به صورت ترتیبی خواهد شد
		</p>  
        <button type="button" class="close btnClose" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		
      </div>
      <div class="modal-body">
          <ul id="sortable" type="none">
			<?=$listOrder?>
          </ul>
          
      </div>
      <div class="modal-footer">
	  
		<div class="row">
			<div class="col-md-12">

			</div>
		</div>
		

		<div id="msgResult" ></div>
		<button  id="btnSetQuestionOrder" type="button" class="btn btn-success" >ثبت چیدمان</button>
		
        <button type="button" class="btnClose btn btn-secondary" data-dismiss="modal">بستن</button>
      </div> 
    </div>
  </div> 
</div>

  <script>
   var change=false;
   var exam_id="<?=(isset($exam->id) ? $exam->id:0)?>";
   var ids="";
  $( function() {
    $( "#sortable" ).sortable({

         update:function(e,ui) {
          change=true;
           ids = $('#sortable li').map(function(i) { return this.id; }).get();
            //var field_order=ui.item.index();
            //var id=ui.item.attr("data-id");
        }
                
    });
    $( "#sortable" ).disableSelection();
  } );
   
  $("#btnSetQuestionOrder").click(function(){
	  
	$("#msgResult").html(loadingFile);
	$.post("ajaxExamQuestionOrder.php",
		   {
			   ids:ids,
			   exam_id:exam_id
			 },
		   function(data){
			$("#msgResult").html(data.text);
		   },
		   'json'); 
  });
  
 /* $(".btnClose").click(function(){
    if(change)
      location.reload();
  });*/
 
  </script>

<link   href="../lib/datatable/jquery.dataTables.min.css"  rel="stylesheet"/>
<script src="../lib/datatable/jquery.dataTables.min.js" ></script>
<script src="../lib/datatable/dataTables.buttons.min.js" ></script>
<script src="../lib/datatable/jszip.min.js" ></script>
<script src="../lib/datatable/buttons.html5.min.js" ></script>
<!--<script src="../lib/datatable/buttons.print.min.js" ></script>-->
<!--<script src="../lib/datatable/pdfmake.min.js" ></script>
<script src="../lib/datatable/vfs_fonts.js" ></script>-->
<script>
$(document).ready( function () {
    $('#tableQuestion').DataTable({
            paging: false,
            dom: 'Bfrtip',
            buttons: [
                {
                    //extend: 'copyHtml5',
                    text: 'چینش سوالات',
                    className:'btn btn-info mb-1 d-print-none',
					action: function ( e, dt, node, config ) {$("#modalOrderSelectItem").modal('show');;}
                },
                {
                    extend: 'excelHtml5',
                    text: 'خروجی اکسل',
                    className:'btn btn-success mb-1 d-print-none',
                    title:'<?=@$exam->name?>',
                    exportOptions : {columns: [ 0,1,3,4,5,6,2 ]}
                },
                {
                  text: ' چاپ / PDF ', className:'btn btn-warning mb-1 d-print-none',
                  action: function ( e, dt, button, config )
                  {
                    window.location = 'previewQuestionPrint.php?exam_id=<?=$Encryption->encode(@$exam_id)?>';
                  }        
                },
            ],
            "language": {"url": "../lib/datatable/dataTables.fa.lang"},
            complete: function () {
            $('div.dataTables_filter input').addClass("form-control");
        }
        });
    

} );
</script>

<style>
.modal-lg {
    max-width: 80%;
	margin:0px auto;
}
div.dataTables_filter input {
    background: #fff !important;
    width: 295px;
    float: right;
}

@media screen and (max-width: 640px){
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter{
        float: right;
    }
}
</style>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <p class="text-danger bold h5" > <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> هشدار حذف سوال </p>
            </div>
            <div class="modal-body">
              <p class=" bold h5" >
               آیا از حذف سوال<span class="text-danger h5 bold" id="deleteRecord" ></span> اطمینان دارید؟
              </p>
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
