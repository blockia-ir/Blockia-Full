<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <h4 class="text-danger bold" >هشدار حذف رکورد</h4>
            </div>
            <div class="modal-body">
               <h4 class="text-info bold" > آیا از حذف رکورد زیر اطمینان دارید؟ </h4>
			   <h4 class="bg-primary" id="deleteRecord" ></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" >پشیمون شدم</button>
                <a class="btn btn-danger btn-ok"> <i class="fa fa-trash-o fa-lg"></i> حذف کن</a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirm-delete2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <h4 class="text-danger bold" >هشدار حذف دسته ای</h4>
            </div>
            <div class="modal-body">
               <h4 class="text-info bold" > آیا از حذف رکورد های دسته ای اطمینان  دارید؟</h4>
			  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-" data-dismiss="modal" >پشیمون شدم</button>
                <a class="btn btn-danger btn-ok" > <i class="fa fa-trash-o fa-lg"></i> حذف کن</a>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function(){
		$(".btnDelete").click(function() {
			var $row = $(this).closest("tr");
			var $text = $row.text();
			$("#deleteRecord").text($text);
		});
		$('#confirm-delete').on('show.bs.modal', function(e) {
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
		});
		
		$("#deleteAll").click(function() {
			//var allChecked=[];
			//$(".table tr td :checked").each(function(){
			//	allChecked.push($(this).val());
			//});
			//$("#data").attr("href","vbDelelteAll.php?data="+allChecked);
		});
		$('#confirm-delete2').on('show.bs.modal', function(e) {
			var allChecked=[];
			$(".table tr td :checked").each(function(){
				allChecked.push($(this).val());
			});
			$("#data").attr("href","vbDelelteAll.php?data="+allChecked);
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
		});
	});
</script>