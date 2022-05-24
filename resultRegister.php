<?php require_once "pageLoader.php";
if(empty($_GET['id']))
{
    exit("خطا اطلاعات ارسالی ناقص است");
}

$id=$Encryption->decode(strip_tags($_GET['id']));

if(!is_numeric($id))
{
    exit("خطا اطلاعات ارسالی نامتعبر است");
}

$student=$DB->query("SELECT {$DB->tablePrefix}class_student.*,{$DB->tablePrefix}class.name as class_name
                    FROM {$DB->tablePrefix}class_student
                    LEFT JOIN {$DB->tablePrefix}class ON {$DB->tablePrefix}class_student.class_id={$DB->tablePrefix}class.id 
                    WHERE  {$DB->tablePrefix}class_student.id=<bind>$id</bind> LIMIT 1",true);

if(!isset($student->id))
{
   exit("خطا موردی یافت نشد"); 
}
?>
<!DOCTYPE html>
<html lang="fa">
  <head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#F7F7F7" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>نتیجه  ثبت اطلاعات</title>
    <link rel="icon" type="image/png" href="images/iconExam2.png" />
    <link rel="stylesheet" href="lib/fonts/font.css" />
    <link rel="stylesheet" href="lib/font-awesome/css/fontawesome-all.min.css"  />
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap-rtl.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="lib/js/jquery.min.js"></script>
  </head>

  <body class="rtl" >
    
        <div class="container " >
            <div class="row mt-5" >
                <div class="col-md-3"></div>
                
                <div class="col-md-6 text-center">
                    <div class="card p-4" >
                        <?=$Msg->show()?>
                        <div class="" >نام و نام خانوادگی <br/> <span class="text-info h5" ><?=$student->name?></span></div>
                        <div class="" >کد ورود به آزمون <br/> <span class="text-primary h5" ><?=$student->code?></span></div>
                        <div class="" >کلاس <br/> <span class="text-secondary h5"> <?=$student->class_name?></span></div>
                    </div>
                </div>
                
                <div class="col-md-3" ></div>
            </div>
        </div>
  </body>
</html>