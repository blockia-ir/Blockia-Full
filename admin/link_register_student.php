<?php require_once "blockPageLoader.php"; ?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>لینک ثبت نام دانش آموز</title>
        <link rel="icon" type="image/png" href="../images/iconExam2.png" />
        <link rel="stylesheet" href="../lib/fonts/font.css"  />
        <link rel="stylesheet" href="../lib/font-awesome/css/fontawesome-all.min.css" >
        <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" />
        <link rel="stylesheet" href="../css/panel.css" />
        <script src="../lib/js/jquery.min.js"></script>
        <script src="../lib/js/function.js"></script>
        <link rel="stylesheet" href="../lib/multiple-select/multiple-select.css">
        <script type="text/javascript" src="../lib/multiple-select/multiple-select.js"></script>
    </head>
    <body class="rtl" >
        <?php require_once "menu.php"?>
        <div class="container-fluid">
            <?=$Msg->show();?>
            <?php
                $block_user=$DB->query("SELECT * FROM $tbl_block_users WHERE id=<bind>$block_user_id</bind>",true);
                $linkRegisterStudent="http://".$_SERVER['HTTP_HOST'].strstr($_SERVER['PHP_SELF'],basename(getcwd()),true)."ru.php?u={$Encryption->encode($block_user->username)}";
                $listClassTeacher=$DB->query("SELECT * FROM {$DB->tablePrefix}class WHERE block_user_id='$blockUserId'");    
            ?>
                <div class="row">
                    <div class="col-md-4" >
                        <form action="updateLinkRegister.php" method="post" >
                            <label for="active_link_register" >وضعیت لینک  ثبت دانش آموز</label>
                            <select name="active_link_register" id="active_link_register" class="form-control" >
                                <option value="0" <?= ($block_user->active_link_register==0 ? "selected":"")?> >غیر فعال</option>
                                <option value="1" <?= ($block_user->active_link_register==1 ? "selected":"")?> >فعال</option>
                            </select>
                            
                            <label for="class_link_register" >کلاس</label>
                            <select id="class_link_register" name="class_link_register[]"  class=" multiple" multiple="multiple"  >
                                <?php foreach($listClassTeacher as $class):?>
                                    <option value="<?=@$class->id?>" <?=(@strstr(@$block_user->class_link_register,@$class->id)!="" ? "selected" :"" )?> ><?=@$class->name?></option>
                                <?php endforeach?>
                            </select>
                            <div id="msgClass" ></div>
                            <label for="description_link_register">توضیحات</label>
                            <textarea name="description_link_register" id="description_link_register"
                                      class="form-control" rows="5" placeholder="حداکثر 255 کاراکتر" ><?=$block_user->description_link_register?></textarea>
                            
                            <button type="submit" id="btnSubmit" class="btn btn-primary mt-2" >ثبت تغییرات</button>
                            
                            <button type="button" class="btn btn-secondary mt-2" data-toggle="modal" data-target="#modalHelpRegisterStudent"> راهنما</button>
                            
                        </form>
                        <hr/>
                        <button class="btn btn-info btn-sm mb-3" tabindex="0"  id="btnCopyLink" for="linkExam"
                                data-toggle="popover" data-trigger="focus" title="" data-content="لینک ثبت نام دانش آموز کپی شد" >
                        <i class='fas fa-copy' ></i> کپی لینک ثبت نام دانش آموز </button> 
						<span id="msgCopy" ></span>
                        
                        <textarea id="linkExam" class=" ltr readonly  bg-white form-control" ><?=$linkRegisterStudent?></textarea>
                    </div>
                </div>
        </div>
        <?php require_once "footer.php"?>
    <body>
    <script>
        $(document).ready(function(){
            $('.multiple').multipleSelect();
        });
        
        $('#btnSubmit').click(function() {
        
            var valid=true;
            var msg="";
           if($("#active_link_register").val()==1 && $("#class_link_register").val()=="")
           {
               msg=("<div class='alert alert-danger' >لطفا کلاس را انتخاب کنید</div>");
               valid=false;
           }

       
           if(valid==false)
           {
               event.preventDefault();
               event.stopPropagation();
               $("#msgModal").modal("show");
           }
           $("#msg").html(msg);
           
        });	

        $("#btnCopyLink").click(function(){
          var copyText = document.getElementById("linkExam");
          copyText.select();
          copyText.setSelectionRange(0, 99999); /*For mobile devices*/
          document.execCommand("copy");
            $("#msgCopy").html("<b class='text-success' >کپی شد</b>");
        });
        
        
    </script>
    
    <div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                   <p class=" bold h6" > خطا</p>
                </div>
                <div class="modal-body">
                    <div class="modal-body" id="msg" ></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" >بستن</button>
                </div>
            </div>
        </div>
    </div>
	
<!-- Modal Help register Student -->
<div id="modalHelpRegisterStudent" class="modal fade rtl" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header ">
        <p class="modal-title"><b>راهنمای درج  دانش آموزان از طریق لینک</b></p>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
		<p>
		
		در این روش نیازی به وارد کردن دانش آموزان توسط معلم نیست
		کافیست لینک ثبت نام را فعال کرده، کلاس ها را  تعیین کنید و لینک 
		ثبت نام  را در اختیار دانش آموزان  قرار دهید 
		دانش آموزان  با داشتن  لینک  می توانند  اطلاعات ورود برای شرکت در آزمون را خود ثبت کنند
		</p>
		<p class="alert alert-warning" >
		توجه کنید پس از اینکه دانش آموز اطلاعات خود را ثبت کردند حتما لینک ثبت نام را غیر فعال کنید
		</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
      </div>
    </div>

  </div>
</div>
</html>
