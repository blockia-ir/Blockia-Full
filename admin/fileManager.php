<?php require_once "blockPageLoader.php";
if(!admin)
{
    exit("error: you can not access this page");
}

$totalSize=$DB->query("SELECT sum(size) as totalSize FROM {$DB->tablePrefix}upload_files",true)->totalSize;
$totalSize = number_format($totalSize / 1024, 2) . ' MB ';
?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>مدیریت فایل ها</title>
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
            <h5>حجم کل فایل ها: <?=$totalSize?> </h5>
            <div class="table-responsive" id="folder_table">
            <?php $folder = array_filter(glob('../source3/*'), 'is_dir');?>
            <table class="table table-bordered table-striped">
              <thead>
                  <tr>
                      <th>پوشه</th>
                      <th>تعداد فایل</th>
                      <th>اندازه</th>
                      <th>نمایش فایل ها</th>
                      <th>حذف</th>
                  </tr>   
              </thead>
             <?php 
            if(count($folder) > 0)
            {
             foreach($folder as $name)
             {
              echo  '
               <tr>
                <td class="name" >'.str_replace("../source3/","",$name).'</td>
                <td>'.(count(scandir($name)) - 2).'</td>
                <td>'.get_folder_size($name).'</td>
                <td><a href="showFiles.php?folder_name='.$Encryption->encode($name).'" name="view_files" data-name="'.$name.'" class="view_files ">نمایش فایل ها</a></td>
                <td><a class="btnDelete" data-href="deleteFolder.php?folder_name='.$Encryption->encode($name).'" data-toggle="modal" data-target="#confirm-delete" href="#" ><i class="iconDelete fas fa-times "></i></a></td>
               </tr>';
             }
            }
            else
            {
             echo  '
              <tr>
               <td colspan="6">No Folder Found</td>
              </tr>
             ';
            }
            echo '</table>';
            ?>
            </div>
        </div>
        <?php require_once "footer.php"?>
    <body>
        
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <p class="text-danger bold h5" > <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> هشدار حذف پوشه </p>
            </div>
            <div class="modal-body">
              <p class=" bold h5" >
                آیا از حذف پوشه <span class="text-danger h5 bold" id="deleteRecord" ></span> اطمینان دارید؟
                </p>
              <div class="alert alert-danger">
                توجه کنید تمام سوالات همه کاربرانی که از تصاویر این پوشه استفاده می کنند دچار مشکل خواهند شد. این عمل غیر قابل بازگشت است
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
</html>

<?php
    function format_folder_size($size)
    {
        if ($size >= 1073741824)
        {
            $size = number_format($size / 1073741824, 2) . ' GB';
        }
        elseif ($size >= 1048576)
        {
            $size = number_format($size / 1048576, 2) . ' MB';
        }
        elseif ($size >= 1024)
        {
            $size = number_format($size / 1024, 2) . ' KB';
        }
        elseif ($size > 1)
        {
            $size = $size . ' bytes';
        }
        elseif ($size == 1)
        {
            $size = $size . ' byte';
        }
        else
        {
            $size = '0 bytes';
        }
        return $size;
    }
    
    function get_folder_size($folder_name)
    {
        $total_size = 0;
        $file_data = scandir($folder_name);
        foreach($file_data as $file)
        {
            if($file === '.' or $file === '..')
            {
                continue;
            }
            else
            {
                $path = $folder_name . '/' . $file;
                $total_size = $total_size + filesize($path);
            }
        }
        return format_folder_size($total_size);
    }
?>
 
