<?php require_once "blockPageLoader.php";
if(!admin)
{
    exit("error: you can not access this page");
}

?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>نمایش لیست فایل ها</title>
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
            <?=$Msg->show();?>
            <?php
            $folder_name=$Encryption->decode(strip_tags($_GET['folder_name']));
            
             if($folder_name!="")
                {
                 $file_data = scandir($folder_name);
                 $output = '
                 <table class="table table-bordered table-striped">
                 <thead>
                    <tr>
                     <th>تصویر</th>
                     <th>نام فایل</th> 
                     <th>حذف</th>
                    </tr>
                  </thead>
                 ';
                 
                 foreach($file_data as $file)
                 {
                  if($file === '.' or $file === '..')
                  {
                   continue;
                  }
                  else
                  {
                   $path = $folder_name . '/' . $file;
                   $output .= '
                   <tr>
                    <td class="name" ><img src="'.$path.'" class="img-thumbnail" height="100" width="100" /></td>
                    <td  contenteditable="true" data-folder_name="'.$folder_name.'"  data-file_name = "'.$file.'" class="change_file_name">'.$file.'</td>
                    <td><a class="btnDelete" data-href="deleteFile.php?file_name='.$Encryption->encode($path).'" data-toggle="modal" data-target="#confirm-delete" href="#" ><i class="iconDelete fas fa-times "></i></a></td>
                   </tr>
                   ';
                  }
                 }
                 $output .='</table>';
                 echo $output;
                }
            ?>
        </div>
        <?php require_once "footer.php"?>
    <body>
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <p class="text-danger bold h5" > <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> هشدار حذف فایل </p>
            </div>
            <div class="modal-body">
              <p class=" bold h5" >
                آیا از فایل<span class="text-danger h5 bold" id="deleteRecord" ></span> اطمینان دارید؟
                
                </p>
              <div class="alert alert-danger">توجه کنید تمام سوالات که از این فایل استفاده می کنند. دچار مشکل خواهند شد. این عمل غیر قابل بازگشت است</div>
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
			var $text = $(this).closest("tr").find(".name").html();
			$("#deleteRecord").html($text);
		});
		$('#confirm-delete').on('show.bs.modal', function(e) {
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
		});
	});
</script>
</html>
