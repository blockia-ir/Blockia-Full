    <header>
      <nav class="navbar navbar-expand-md fixed-top navbar-dark navbar-default" id="navbar" >
        <span class="navbar-brand title" ><?= @$_COOKIE['nameUser'] ?></span>
        <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExample04">
          <ul class="navbar-nav mr-auto">
            
            <li class="nav-item" ><a class="nav-link" href="index.php"><i class="fas fa-home"></i></a></li>
            <li class="nav-item" ><a class="nav-link" href="examPage.php" >آزمونها</a></li> 
            <li class="nav-item" ><a class="nav-link" href="exam_resultPage2.php" >نتایج آزمون</a></li>
            <li class="nav-item" ><a class="nav-link" href="countExamHour.php" >وضعیت شلوغی</a></li> 
            <li class="nav-item" ><a class="nav-link" href="classForm.php" >کلاسها</a></li>
            <li class="nav-item" ><a class="nav-link" href="class_studentPage.php" >دانش آموزان</a></li>
            <li class="nav-item" ><a class="nav-link" href="editblockUserForm.php" >حساب کاربری</a></li>
			<li class="nav-item" ><a class="nav-link" href="faq.php" >سوالات متداول</a></li>
            <li class="nav-item" ><a class="nav-link" href="blockSignOut.php?exit=1" >خروج</a></li>
            
            <?php if(admin):?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">تنظیمات مدیر</a>
              <div class="dropdown-menu" aria-labelledby="dropdown1">
                  <a class="dropdown-item" href="userPage.php" >کاربران</a>
                  <a class="dropdown-item" href="settingAdminForm.php" >تنظیمات</a>
                  <a class="dropdown-item" href="responsiveFileManager.php" >فایل ها</a>
                  <a class="dropdown-item" href="listLogins.php" >ورود ها</a>
                  <a class="dropdown-item" href="about.php" >درباره</a>
              </div>
            </li>
            <?php endif?>
           
          </ul>
        </div>
      </nav>
    </header>
