<?php require_once "blockPageLoader.php";

$listClass=$DB->query("SELECT * FROM {$DB->tablePrefix}class WHERE block_user_id='$blockUserId' ORDER BY id DESC" );

?>
<table class="table table-striped table-condensed border display" id="tableClass" style="max-width: 450px;" >
  <thead>
    <tr>
      <th class='' >نام کلاس</th>
      <th class="d-print-none" >ویرایش</th>
      <th class="d-print-none" >حذف</th>
    </tr> 
  </thead>
  <?php foreach($listClass as $class):?>
  <tr>
    <td class="name" ><?= $class->name ?></td>
    <td width="50" class="d-print-none" ><a class='btnEdit' href='classForm.php?id=<?=$Encryption->encode($class->id)?>' ><i class='iconEdit fas fa-edit'></i></a></td>
    <td width="50" class="d-print-none" ><a class='btnDelete' data-href='deleteClass.php?id=<?=$Encryption->encode($class->id)?>' data-toggle='modal' data-target='#confirm-delete' href='#' ><i class='iconDelete fas fa-times '></i></a></td>
  </tr>
  <?php endforeach?>
</table>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <p class="text-danger bold h5" > <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> هشدار حذف کلاس </p>
            </div>
            <div class="modal-body">
              <p class=" bold h5" >
                آیا از حذف کلاس <span class="text-danger h5 bold" id="deleteRecord" ></span> اطمینان دارید؟
                </p>
              <div class="alert alert-danger">توجه کنید تمام دانش آموزان این کلاس نیز حذف خواهند شد. این عمل غیر قابل بازگشت است.</div>
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

 


