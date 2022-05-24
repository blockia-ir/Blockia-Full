<?php require_once "blockPageLoader.php"; ?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ویرایش حساب کاربری</title>
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
			<div class="titleForm" >ویرایش حساب کاربری</div>
			<?= $Msg->show(); ?>
			<?php $blockUser=$DB->query("SELECT * FROM {$DB->tablePrefix}block_users WHERE id='$blockUserId' ",true);?>
			<div class="row" >
				<div class="col-md-4" >
					<form action="editUser.php" method="post" >
						<input type='hidden' name='editInfo' value='1' />
						
						<label for="id" >کد ثبت نام: </label>
						<input type="text"  value="<?=@$blockUser->id?>"  class="form-control" disabled />
						
						<label for="name" >نام:</label>
						<input type="text" id="name" name="name" value="<?= @$blockUser->name ?>" maxlength="50"  class="form-control" autocomplete="off" required />
						
						<label for="email" >ایمیل:</label>
						<input type="email" id="email" name="email" value="<?= @$blockUser->email ?>" maxlength="100"  class="form-control" autocomplete="off"  />
						
						<label for="username" >نام کاربری:</label>
						<input type="text" class="form-control" value="<?= @$blockUser->username ?>" disabled />
						
						<input type="submit" value="ثبت اطلاعات" class="btn btn-primary mt-1" />
					</form>
					<form action="editUser.php" method="post" >
						<label for="password" >رمز عبور:</label>
						<input type="text" id="password" name="password" value="" maxlength="50"  class="form-control" autocomplete="off" required />
						<input type="submit" value="تغییر رمزعبور" class="btn btn-primary mt-1" />
					</form>
				</div>
				<div class="col-md-8" >
				</div>
			</div>
			<script>
			$(function () {
				$("#password")
					.tooltip({ placement:"top",title: 'توجه کنید رمز عبور به بزرگی و کوچکی حروف حساس است' })
					.blur(function () {
						$(this).popover('hide');
					});
			});
			</script>
        </div>
        <?php require_once "footer.php"?>
    <body>
</html>
