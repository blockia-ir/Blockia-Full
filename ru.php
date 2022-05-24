<?php require_once "pageLoader.php";
require_once "lib/captcha/captcha.php";
$_SESSION['captcha'] = captcha();


if(empty($_GET['u']))
{
    exit("خطا: لینک  ثبت نام ناقص است");
}

$username=$Encryption->decode(strip_tags($_GET['u']));

if($username=="")
{
    exit("خطا: لینک ثبت نام معتبر نیست");
}

$block_user=$DB->query("SELECT id,class_link_register,description_link_register FROM {$DB->tablePrefix}block_users
                     WHERE username=<bind>$username</bind> AND active_link_register=1",true);
if(!isset($block_user->id))
{
    exit("خطا لینک ثبت دانش آموز یافت نشد و یا غیر فعال شده");
}

$listClass=$DB->query("SELECT * FROM {$DB->tablePrefix}class WHERE block_user_id='{$block_user->id}'
                      AND id IN ({$block_user->class_link_register})");

if(!empty($_COOKIE['registerStudent']))
{
  $id=$Encryption->decode(strip_tags($_COOKIE['registerStudent']));
  $student=$DB->query("SELECT {$DB->tablePrefix}class_student.*,{$DB->tablePrefix}class.name as class_name
                    FROM {$DB->tablePrefix}class_student
                    LEFT JOIN {$DB->tablePrefix}class ON {$DB->tablePrefix}class_student.class_id={$DB->tablePrefix}class.id 
                    WHERE  {$DB->tablePrefix}class_student.id=<bind>$id</bind> LIMIT 1",true);

  if(!isset($student->id))
  {
     setcookie("registerStudent","",time()-(365*24*60*60),"/");
  }
}


?>
<!DOCTYPE html>
<html lang="fa">
  <head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#F7F7F7" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>لینک ثبت برای شرکت در آزمون آنلاین</title>
    <meta name="description" content="<?= $block_user->description_link_register ?>  " />
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
                
                <div class="col-md-6">
                  <div class="card p-3" >
                    <?=$Msg->show();?>
                    
                    <?php if(isset($student->id)):?>
                    <div class="alert alert-warning text-center" >
                        اطلاعات زیر را مجددا ثبت نکنید:
                        <div class="" >نام و نام خانوادگی <br/> <span class="text-info h5" ><?=$student->name?></span></div>
                        <div class="" >کد ورود به آزمون <br/> <span class="text-primary h5" ><?=$student->code?></span></div>
                        <div class="" >کلاس <br/> <span class="text-secondary h5"> <?=$student->class_name?></span></div>
                    </div>
                    <?php endif ?>
                    
                    <div class="h5" >فرم ثبت اطلاعات جهت شرکت  در آزمون</div>
                   <div class="" >
                    <?=$block_user->description_link_register?>
                   </div>
                   <form action="registerStudent.php" method="post" >
                    <input type="hidden" name="username" value="<?=$Encryption->encode($username)?>" />
                    
                    <label for="class_id" >کلاس:</label>
                    <select name="class_id" id="class_id" class="form-control" required >
                      <option value="" >لطفا کلاس خود را انتخاب کنید</option>
                      <?php foreach($listClass as $class):?>
                      <option value="<?= $Encryption->encode($class->id)?>" ><?=$class->name?></option>
                      <?php endforeach ?>
                    </select>
                    
                    <label for="name" >نام و نام خانوادگی: </label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="نام و نام خانوادگی خود را به طور صحیح وارد کنید" required />
                    
                    <label for="code" >کد ورود به  آزمون:</label>
                    <input type="number" name="code" id="code" class="form-control" placeholder="فقط عدد وارد کنید "  required />
                    
                    <label for="password">کد امنیتی زیر را وارد کنید:</label>
                    <input type="text" class="form-control" id="captcha"  name="captcha"  required>
                    <div class="invalid-feedback">لطفا عبارت امنیتی را وارد کنید</div>
                    <br/>
                    <?='<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">';?>
                    <br/>
                    <button type="submit" class="btn btn-primary mt-2" > ثبت اطلاعات </button>
                   </form>
                  </div>
                </div>
                
                <div class="col-md-3" ></div>
            </div>
        </div>
  </body>
</html>
