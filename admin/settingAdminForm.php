<?php require_once "blockPageLoader.php";
if(!admin)
{
    exit("error: you can not access this page");
}

$id="";//define var
$block_user=array();//define var
if(!empty($_GET['id']))
{
    $id=$Encryption->decode(strip_tags($_GET['id']));
    if(!is_numeric($id))
    {
        exit("error: id not vlaid");
    }
    $block_user=$DB->query("SELECT * FROM {$DB->tablePrefix}block_users WHERE id=<bind>$id</bind>",true);
}
?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>تنظیمات آزمون</title>
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
           
            <div class="titleForm" >تنظیمات آزمون ساز</div>
            <?=$Msg->show();?>
            <div class="row">
                <div class="col-md-4">
                    <?= $Msg->show() ?>
                    <form action="setSettingAdmin.php" method="post" class="needs-validation"  novalidate>
                      
                        <div class="form-group">
                          <label for="exam_name">نام آزمون ساز: </label>
                          <input type="text" class="form-control" id="exam_name" name="exam_name" value="<?=@$_setting['exam_name']?>"  maxlength="250" required >
                          <div class="invalid-feedback">لطفا آزمون ساز را وارد کنید</div>
                        </div>
                      
                        <div class="form-group">
                          <label for="register_user"> ثبت نام کاربر: </label>
                          <select id="register_user" name="register_user" class="form-control" >
                            <option value="true"  <?= ($_setting['register_user']==true ? "selected":"")?>  >فعال</option>
                            <option value="false" <?= ($_setting['register_user']==false ? "selected":"")?> >غیر فعال</option>
                          </select>
                          <div class="invalid-feedback" > لطفا تعداد افراد شرکت کننده در هر آزمون را تعیین کنید</div>
                        </div>
                    
                        <div class="form-group">
                            <label for="number_exam_hour">تعداد ثبت آزمون در هر ساعت:</label>
                            <input type="number" class="form-control" id="number_exam_hour" name="number_exam_hour" value="<?=@$_setting['number_exam_hour']?>"  required>
                            <div class="invalid-feedback" > لطفا تعداد افراد شرکت کننده در هر آزمون را تعیین کنید</div>
                        </div>
                    
                      <button type="submit" class="btn btn-primary">ثبت تنظیمات</button>
                    </form>
                </div>
            </div>
        </div>
    <script>
    // Disable form submissions if there are invalid fields
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Get the forms we want to add validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
    </script>
        <?php require_once "footer.php"?>
    <body>
</html>
 
