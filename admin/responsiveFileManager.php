<?php require_once "blockPageLoader.php";
if(!admin)
    exit("خطا: شما به این صفحه دسترسی ندارید");
?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>مدیریت فایلها</title>
        <link rel="icon" type="image/png" href="../images/iconExam2.png" />
        <link rel="stylesheet" href="../lib/fonts/font.css"  />
        <link rel="stylesheet" href="../lib/font-awesome/css/fontawesome-all.min.css" >
        <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" />
        <link rel="stylesheet" href="../css/panel.css" />
        <script src="../lib/js/jquery.min.js"></script>
        <script src="../lib/js/function.js"></script>
    </head>
    <body class="rtl"  >
        <?php require_once "menu.php"?>
        <div class="container-fluid">
            <?=$Msg->show();?>
            <div class="alert alert-danger" >
                توجه کنید در صورتیکه تصاویر زیر حذف کنید تمام سوالات همه کاربرانی که از این تصاویر استفاده می کنند دچار مشکل خواهند شد.
                این عمل غیرقابل بازگشت است.
            </div>
                 <iframe src="../lib/filemanager/dialog.php?type=0&akey=<?=$_SESSION['admin']?>"
            frameborder="0" style="overflow:hidden;width:100%" height="500" width="100%" ></iframe>
            
           
        </div>
        <?php require_once "footer.php"?>
    <body>
</html>
