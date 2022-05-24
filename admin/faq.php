<?php require_once "blockPageLoader.php"; ?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>راهنما</title>
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
<div class="accordion" id="accordionExample">
  
  <div class="card">
    <div class="card-header p-0" id="heading2">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapseOne">
         <p class="h5" > من در چند مدرسه  درس میدم. چطور می تونم کلاس ها بر حسب مدرسه تفکیک کنم؟ </p>
        </button>
      </h5>
    </div>

    <div id="collapse2" class="collapse " aria-labelledby="heading2" data-parent="#accordionExample">
      <div class="card-body">
          این نرم افزار معلم محور است. بنابراین  قسمت مجزای برای درج مدرسه نیست. ولی می تونید توی قسمت  کلاس بعد از نام
		  کلاس نام مدرسه تون قرار بدید.  این  طوری اگه  کلاس های مشترکی در مدارس مختلف دارید تفکیک می شوند.
		  به تصویر زیر توجه کنید: <br/>
		  <img src="../help/h01.PNG?dd" class="mw-100" />
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header p-0" id="heading3">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapseOne">
         <p class="h5" > آیا میشه  یک امتحان از چند کلاس بگیریم؟ </p>
        </button>
      </h5>
    </div>

    <div id="collapse3" class="collapse " aria-labelledby="heading3" data-parent="#accordionExample">
      <div class="card-body">
          بله <br/>
		  کافیه فقط در هنگام  ثبت یا ویرایش آزمون  چند کلاس انتخاب کنید 
			توجه کنید بعد از تغییرات دکمه  ثبت اطلاعات  آزمون  بزنید <br/>
		  <img src="../help/h02.PNG" class="mw-100" />
      </div>
    </div>
  </div>
    
  <div class="card">
    <div class="card-header p-0" id="heading4">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="true" aria-controls="collapseOne">
         <p class="h5" > پس از ثبت آزمون  امکان ویرایش هست؟ و آیا تغییرات  به  صورت خودکار روی همون لینک اعمال میشه؟</p>
        </button>
      </h5>
    </div>

    <div id="collapse4" class="collapse " aria-labelledby="heading4" data-parent="#accordionExample">
      <div class="card-body">
بله، تغییرات  به  صورت خودکار روی همون  لینک اعمال میشه. نیازی به ارسال مجدد لینک آزمون  نیست. مثلا زمان  آزمون یا تاریخ آزمون  تغییر میدید به  صورت خودکار در لحظه اطلاعات  آزمون  برای دانش آموزان تغییر پیدا میکنه.
فقط توجه کنید پس از هر تغییر گزینه ثبت اطلاعات آزمون بزنید تا تغییرات جدید اعمال بشه		  

در صورتیکه پس از شروع آزمون  تغییراتی در آزمون اعمال کنید تاثیری بر آزمون دانش آموزانی که وارد آزمون شدند نخواهد گذاشت. بنابراین تغییرات  را حتما پیش از شروع آزمون  اعمال کنید

      </div>
    </div>
  </div>
      
  <div class="card">
    <div class="card-header p-0" id="heading5">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse5" aria-expanded="true" aria-controls="collapseOne">
         <p class="h5" >آیا میشه  سوالات  آزمون  یا پاسخ  سوالات پس از ثبت ویرایش کنم؟ </p>
        </button>
      </h5>
    </div>

    <div id="collapse5" class="collapse " aria-labelledby="heading5" data-parent="#accordionExample">
      <div class="card-body">
بله، فقط باید توجه کنید که حتما تغییرات در سوال پیش از شروع آزمون باشه. چرا که زمانیکه دانش آموز وارد آزمون میشه اطلاعات سوالات به هراه پاسخ به صورت خلاصه شده در برای هر دانش آموز در مکانی ذخیره میشود. و سیستم بر اساس پاسخ سوالاتی که ذخیره شده آزمون تصحیح خواهد کرد. در نتیجه در صورتیکه پس از شروع آزمون پاسخ سوالات تغییر بدهید تاثیری بر نتیجه آزمون دانش آموزانی که وارد آزمون شدند نخواهد گذاشت. البته برای دانش آموزانی که بعد از تغییرات شما وارد آزمون بشوند اثر خواهد داشت. اما چرا سیستم این طوری طراحی شده علت اینکه در صورتیکه به هر دلیل سوالات یا پاسخ ها پس از شروع آزمون حذف بشوند خطایی در محاسبه نمره دانش آموز رخ ندهد.	  
      </div>
    </div>
  </div>
 
  <div class="card">
    <div class="card-header p-0" id="heading100">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse100" aria-expanded="true" aria-controls="collapseOne">
         <p class="h5" > آیا فقط یک نفر می تونه آزمون بده؟</p>
        </button>
      </h5>
    </div>

    <div id="collapse100" class="collapse " aria-labelledby="heading100" data-parent="#accordionExample">
      <div class="card-body">
		خیر <br/>
		اما چرا وقتی یکی آزمون  میده نفر بعدی که می خواد آزمون  بده  جلوش میگیره و نمره  نفر قبلی رو نشون میده؟ <br/>
علت اینکه  سیستم  بر اساس آی پی اینترنت کاربر جلو دوباره  آزمون دادن یک نفر می گیره و وقتیکه نفر بعدی می خواد آزمون بده نمره نفر قبلی ظاهر میشه. در واقع سیستم برای نفر دوم بسته شده. البته برای دیگران  باز است. دیگران  به راحتی می تونند امتحان بدند.چرا که  آی پی اینترنت شون  با شما فرق میکنه. توجه کنید اگر از چند گوشی استفاده می کنید و همه  گوشی ها به  یک اینترنت  متصل باشند مثلا همه به وای فای متصل باشند. بدلیل اینکه آی پی همه گوشی ها یکی میشه آزمون برای همه گوشی های که  به اون  وای فای متصل شدند بسته  میشه.
      </div>
    </div>
  </div>
  
 
  <div class="card">
    <div class="card-header p-0" id="heading101">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse101" aria-expanded="true" aria-controls="collapseOne">
         <p class="h5" >  چرا دانش آموز میگه  نمره  کسی دیگه ای براش ظاهر شده و نتونسته آزمون بده؟ </p>
        </button>
      </h5>
    </div>

    <div id="collapse101" class="collapse" aria-labelledby="heading2" data-parent="#accordionExample">
      <div class="card-body">
علت اینکه  سیستم  بر اساس آی پی اینترنت کاربر جلو دوبار آزمون دادن یک نفر می گیره و وقتیکه نفر بعدی می خواد آزمون بده نمره نفر قبلی ظاهر میشه. در واقع سیستم برای نفر دوم بسته شده. این احتمال وجود داره که دانش آموزان کنار همدیگه  هستند و در حال استفاده از یک اینترنت و یا با یک گوشی آزمون  دادند. بهتره به دانش آموزان  گفته بشه از هر سیستم  و اینترنت فقط یک نفر می تونه آزمون  بده. و از فیلتر شکن استفاده نکنند چرا که گاهی اوقات  فیلتر شکن ها آی پی های چند کاربر مثل هم می کنند. در نتیجه برای آزمون دادن دچار مشکل می شوند.
      <br/>
	  البته شما می تونید خاصیت چک کردن بر حسب آی پی را غیر فعال کنید. این  مشکل تون حل میشه. ولی احتمال اینکه دانش آموزان
	  بیش از یک بار امتحان  بدند افزایش پیدا میکنه.

	  </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header p-0" id="heading6">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse7" aria-expanded="true" aria-controls="collapseOne">
          <p class="h5" > چطور خاصیت جلوگیری از دوبار امتحان دادن بر حسب آی پی را غیر فعال کنیم؟ </p>
        </button>
      </h5>
    </div>

    <div id="collapse7" class="collapse " aria-labelledby="heading6" data-parent="#accordionExample">
      <div class="card-body">
برای غیر فعال کردن این خاصیت وارد آزمون مورد نظر شده و از گزینه تنظیمات بیشتر گزینه جلوگیری از دوبار امتحان دادن بر حسب آی پی را غیر فعال کرده و گزینه ذخیره تنظیمات آزمون را انتخاب می کنیم.
		  <br/>
      در مورد دانش آموزان دو قلو آی پی را غیر فعال کنید و حتما بفرمایید هر کدوم شون از مرورگر دیگه ای استفاده کنند
       <br/>
		  <img src="../help/h20.PNG" />
     
      </div>
    </div>
  </div>
  <!--        

            
  <div class="card">
    <div class="card-header p-0" id="heading9">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse9" aria-expanded="true" aria-controls="collapseOne">
         <p class="h5" > heading9 </p>
        </button>
      </h5>
    </div>

    <div id="collapse9" class="collapse " aria-labelledby="heading9" data-parent="#accordionExample">
      <div class="card-body">
          collapse9
      </div>
    </div>
  </div>
              
  <div class="card">
    <div class="card-header p-0" id="heading10">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse10" aria-expanded="true" aria-controls="collapseOne">
         <p class="h5" > heading10 </p>
        </button>
      </h5>
    </div>

    <div id="collapse10" class="collapse " aria-labelledby="heading10" data-parent="#accordionExample">
      <div class="card-body">
          collapse10
      </div>
    </div>
  </div>
                
  <div class="card">
    <div class="card-header p-0" id="heading11">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse11" aria-expanded="true" aria-controls="collapseOne">
         <p class="h5" > heading11 </p>
        </button>
      </h5>
    </div>

    <div id="collapse11" class="collapse " aria-labelledby="heading11" data-parent="#accordionExample">
      <div class="card-body">
          collapse11
      </div>
    </div>
  </div>
                  
    <div class="card">
    <div class="card-header p-0" id="heading12">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse12" aria-expanded="true" aria-controls="collapseOne">
         <p class="h5" > heading12 </p>
        </button>
      </h5>
    </div>

    <div id="collapse12" class="collapse " aria-labelledby="heading12" data-parent="#accordionExample">
      <div class="card-body">
          collapse12
      </div>
    </div>
  </div>
    
  <div class="card">
    <div class="card-header p-0" id="heading13">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse13" aria-expanded="true" aria-controls="collapseOne">
         <p class="h5" > heading13 </p>
        </button>
      </h5>
    </div>

    <div id="collapse13" class="collapse " aria-labelledby="heading13" data-parent="#accordionExample">
      <div class="card-body">
          collapse13
      </div>
    </div>
  </div>
    
  <div class="card">
    <div class="card-header p-0" id="heading14">
      <h5 class="mb-0 ">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="true" aria-controls="collapseOne">
         <p class="h5" > heading14 </p>
        </button>
      </h5>
    </div>

    <div id="collapse4" class="collapse " aria-labelledby="heading14" data-parent="#accordionExample">
      <div class="card-body">
          collapse4
      </div>
    </div>
  </div>
    

  
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Collapsible Group Item #2
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Collapsible Group Item #3
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>
</div>-->

<style>
.card img 
{
	max-width:100%;
}
</style>
        </div>
        </div>
        <?php require_once "footer.php"?>
    <body>
</html>
