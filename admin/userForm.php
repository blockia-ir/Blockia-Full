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
	
	if(!isset($block_user->id))
		exit("error: user not found");
	
	$amar=$DB->query("SELECT 
	(SELECT COUNT(id) FROM {$DB->tablePrefix}exam WHERE {$DB->tablePrefix}exam.block_user_id={$block_user->id}) as countExam,
	(SELECT COUNT(id) FROM {$DB->tablePrefix}exam_question WHERE {$DB->tablePrefix}exam_question.block_user_id={$block_user->id}) as countQuestion,
	(SELECT COUNT(id) FROM {$DB->tablePrefix}class WHERE {$DB->tablePrefix}class.block_user_id={$block_user->id}) as countClass,
	(SELECT COUNT(id) FROM {$DB->tablePrefix}class_student WHERE {$DB->tablePrefix}class_student.block_user_id={$block_user->id}) as countStudent",true);
	
	$lastLogin=$DB->query("SELECT {$DB->tablePrefix}logins.*,{$DB->tablePrefix}block_users.name,
                    {$DB->tablePrefix}block_users.username FROM {$DB->tablePrefix}logins
                    LEFT JOIN {$DB->tablePrefix}block_users on {$DB->tablePrefix}logins.block_user_id={$DB->tablePrefix}block_users.id
                    WHERE {$DB->tablePrefix}logins.block_user_id={$block_user->id}
					ORDER BY {$DB->tablePrefix}logins.date DESC LIMIT 10   ");
					

}
?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>فرم کاربر</title>
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
            <span><a href="userPage.php" ><i class="fas fa-list"></i> لیست کاربران</a></span>
            <div class="titleForm" >فرم  ثبت کاربر</div>
            <?=$Msg->show();?>
            <div class="row">
                <div class="col-md-3">
                    <?= $Msg->show() ?>
                    <form action="addUpdateUser.php" method="post" class="needs-validation"  novalidate>
                      <input type="hidden" name="id" value="<?=$Encryption->encode(@$block_user->id)?>" style="display: none;" />
                      <div class="form-group">
                        <label for="name">نام دبیر:</label>
                        <input type="text" class="form-control" id="name" value="<?=@$block_user->name?>" name="name" maxlength="50" required>
                        <div class="invalid-feedback">لطفا  نام دبیر را وارد کنید</div>
                      </div>
                      <div class="form-group">
                        <label for="username">نام کاربری:</label>
                        <input type="text" class="form-control" id="username" value="<?=@$block_user->username?>" name="username" maxlength="50" placeholder="فقط از حروف و اعداد انگلیسی استفاده کنید بدون فاصله" required>
                        <div class="invalid-feedback" >لطفا  نام  کاربری را وارد کنید</div>
                        <span id="msgUsername" class="text-danger"></span>
                      </div>
                      <div class="form-group">
                        <label for="email" >ایمیل:</label>
						<input type="email" id="email" name="email" value="<?= @$block_user->email ?>" maxlength="100" placeholder="اختیاری" class="form-control" autocomplete="off"  />
                      </div>
                      <?php  if($id==""):?>
                      <div class="form-group">
                        <label for="password">رمز عبور:</label>
                        <input type="number" class="form-control" id="password"  name="password" min="100000"  placeholder="فقط عدد وارد کنید حداقل 6 رقم" required>
                        <div class="invalid-feedback">لطفا رمز عبور را به طور صحیح وارد کنید</div>
                      </div>
                      <?php endif ?>
                      <button type="submit" class="btn btn-primary">ثبت اطلاعات</button>
                      <?php if($id!=""): ?>
                      <a href="userForm.php" class="btn btn-warning">درج کاربر جدید</a>
                      <?php endif ?> 
                    </form>
                    <?php if($id!=""):?>
                    <form action="changePasswordUser.php" method="post" class="needs-validation"  novalidate>
                        <input type="hidden" name="id" value="<?=$Encryption->encode(@$block_user->id)?>" style="display: none;" />
                        <div class="form-group">
                            <label for="password">رمز عبور:</label>
                            <input type="number" class="form-control" id="password"  name="password" min="100000"  placeholder="فقط عدد وارد کنید حداقل 6 رقم" required>
                            <div class="invalid-feedback">لطفا رمز عبور را به طور صحیح وارد کنید</div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">تغییر رمز عبور</button>
                    </form>
                    <?php endif ?>
                </div>
				<?php if($id!=""):?>
				<div class="col-md-9">
					<div class="row">
						<div class="col-md-3  text-center mb-2">
							<div class="card">
								تعداد آزمون <br/> <?= $amar->countExam ?> 
							</div>
						</div>
						
						<div class="col-md-3  text-center mb-2">
							<div class="card">
								تعداد سوالات <br/><?= $amar->countQuestion ?> 
							</div>
						</div>
						
						<div class="col-md-3  text-center mb-2">
							<div class="card">
								تعداد کلاس ها <br/><?= $amar->countClass ?> 
							</div>
						</div>
						<div class="col-md-3  text-center mb-2">
							<div class="card">
								تعداد دانش آموزان <br/><?= $amar->countStudent ?> 
							</div>
						</div>
					</div>
					<div id="listLoginsGrid" class="overflow-auto"  >

						<table class="table table-striped table-condensed border display" id="tableLogins" >
						  <thead>
							<tr>
							  <th class='' >آی پی</th>
							  <th class='' >مرورگر</th>
							  <th class='' >سیستم عامل</th>
							  <th class='' >تاریخ</th>
							</tr> 
						  </thead>
						  <?php foreach($lastLogin as $login):?>
						  <tr>
							<td class="" ><?= $login->ip ?></td>
							<td class="" ><?= $login->browser ?></td>
							<td class="" ><?= $login->os ?></td>
							<td class="" ><?= $Jdf->convertToJalali($login->date) ?></td>
							
						  </tr>
						  <?php endforeach?>
						</table>

					</div>
				</div>
				<?php endif ?>
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
    
     $("#username").change(function(){
          var val=$("#username").val();
          $("#username").val(val.replace(/ /g,''));
     });
     
    $("#username").keypress(function(event){
          var val=$("#username").val();
          $("#username").val(val.replace(/ /g,''));
          var ew = event.which;
          if(ew == 32)
          {
              $("#msgUsername").html("");
              return true;
          }
          if(48 <= ew && ew <= 57)
          {
              $("#msgUsername").html("");
              return true;
          }
          if(65 <= ew && ew <= 90)
          {
              $("#msgUsername").html("");
              return true;
          }
          if(97 <= ew && ew <= 122)
          {
              $("#msgUsername").html("");
              return true;
          }
          $("#msgUsername").html("فقط از حروف و عدد انگلیسی استفاده کنید");
          return false;
      });
    </script>
        <?php require_once "footer.php"?>
    <body>
</html>
 
