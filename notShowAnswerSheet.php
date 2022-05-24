<?php require_once "pageLoader.php";
if(!isset($date_end))
    exit("خطا: امکان  باز کردن مستقیم  این فایل وجود ندارد");
$jdate_end=$Jdf->jdate('l, j F Y ساعت H:i',$date_end+60,0);
?>
<!DOCTYPE html>
<html lang="fa">
  <head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#F7F7F7" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>پاسخنامه</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/png" href="images/iconExam2.png" />
    <link rel="stylesheet" href="lib/fonts/font.css" />
    <link rel="stylesheet" href="lib/font-awesome/css/fontawesome-all.min.css"  />
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap-rtl.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="lib/js/jquery.min.js"></script>
  </head>

  <body class="rtl" >
    <div class="container-fluid " style="max-width:600px;" >
        <div class="row mt-5">
            <div class="col-md-12"> 
                <div class="card p-2 text-center w-100">
                    <div class="m-2 text-danger" >پاسخنامه  پس از پایان آزمون قابل مشاهده است</div>
                    <div class="m-2"  >پاسخنامه  در تاریخ  <span class="" ><?= $jdate_end ?></span> فعال خواهد شد</div>
                    <div class="m-2"  >لینک پاسخنامه را کپی کنید بعد از پایان آزمون  وارد شوید</div>
                    <textarea class="form-control ltr" rows="4"  ><?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?></textarea>
                </div>
            </div>   
        </div>
    </div>
    <!--<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>-->
  </body>

</html>
