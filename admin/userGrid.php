<?php require_once "blockPageLoader.php";
if(!admin)
{
    exit("error: you can not access this page");
}

$table="block_users";//define var

if(isset($where))
	$where=$where;
else
	$where="";

$allowSearch=array("username","id");
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
        exit("error:  dont allow search in $field");
      }
      
      if($search['value']!="" && $search['dataType']=="text")
      {
          $serachValue=strip_tags(addslashes($search['value']));
          $where.="(`$table`.`$field`=<bind>$serachValue</bind> OR `$table`.`$field` LIKE <bind>%$serachValue%</bind> ) AND ";
      }
    
    }
    
    $where=$Func->strReplaceLast("AND","",$where);
}

if(!empty($where))
{
    $where="WHERE $where";
}
$pageRow=100;
$pageNum=1;

$countRow=$DB->queryOutPut("SELECT count(0) FROM `{$DB->tablePrefix}$table` $where");
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

$listUser=$DB->query("SELECT * FROM `{$DB->tablePrefix}$table` $where ORDER BY id DESC $limit");
?>

<div id="userGrid" >

    <?php $Func->pagination("userGrid.php","userGrid",$countRow,$pageRow,$pageNum);?>
    <table class="table table-striped table-condensed border display" id="tableLogins" >
      <thead>
        <tr>
          <th class='' >کد ثبت نام</th>
          <th class='' >نام</th>
          <th class='' >نام کاربری</th>
          <th class='' >ایمیل</th>
          <th class='' >تاریخ ثبت</th>
          <th class='' >ویرایش</th>
          <th class='' >حذف</th>
        </tr> 
      </thead>
      <?php foreach($listUser as $user):?>
      <tr>
        <td class="d-none d-md-table-cell " ><?= $user->id ?></td>
        <td class="d-none d-md-table-cell" ><?= $user->name ?></td>
        <td class="d-none d-md-table-cell name" ><?= $user->username ?></td>
        <td class="d-none d-md-table-cell " ><?= $user->email ?></td>
        <td class="d-none d-md-table-cell " ><?= $Jdf->convertToJalali($user->date_create) ?></td>
        <td width="50" class="d-print-none" ><a class='btnEdit' href='userForm.php?id=<?=$Encryption->encode($user->id)?>' ><i class='iconEdit fas fa-edit'></i></a></td>
        <td width="50" class="d-print-none" ><a class='btnDelete' data-href='deleteUser.php?id=<?=$Encryption->encode($user->id)?>' data-toggle='modal' data-target='#confirm-delete' href='#' ><i class='iconDelete fas fa-times '></i></a></td>
      </tr>
      <?php endforeach?>
    </table>

</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <p class="text-danger bold h5" > <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> هشدار حذف </p>
            </div>
            <div class="modal-body">
              <p class=" bold h5" >
                آیا از حذف کاربر <span class="text-danger h5 bold" id="deleteRecord" ></span> اطمینان دارید؟
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
