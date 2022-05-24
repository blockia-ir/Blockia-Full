<?php require_once "blockPageLoader.php";

$exam_id=$Encryption->decode(strip_tags($_GET['exam_id']));

if(!is_numeric($exam_id))
    exit("error exam id not valid");
      
$exam=$DB->query("SELECT * FROM {$DB->tablePrefix}exam WHERE block_user_id='$blockUserId' AND id='$exam_id' ",true);
if(!isset($exam->id))
  exit("error exam not found");
  
$listExamQuestion=$DB->query("SELECT * FROM {$DB->tablePrefix}exam_question
                             WHERE block_user_id='$blockUserId' AND exam_id='$exam_id'
                            ORDER BY {$DB->tablePrefix}exam_question.ordr ASC,{$DB->tablePrefix}exam_question.id DESC " );

$options=array('rtl'=>["الف","ب","ج","د"],"ltr"=>["a","b","c","d"]);

if(!empty($_GET['dir']))
    $dir=strip_tags($_GET['dir']);
else
{
    if($exam->dir==1)
      $dir="ltr";
    else
      $dir="rtl";
}

?>

<!DOCTYPE html>
<html lang="fa">
  <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$exam->name?></title>
    <link rel="icon" type="image/png" href="../images/iconExam2.png" />
    <link rel="stylesheet" href="../lib/fonts/font.css"  />
    <link rel="stylesheet" href="../lib/font-awesome/css/fontawesome-all.min.css" >
    <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" />
    <link rel="stylesheet" href="../css/panel.css" />
    <script src="../lib/js/jquery.min.js"></script>
    <script src="../lib/js/function.js"></script>
  </head>
<body dir="<?=$dir?>" bgcolor="fff">
    <div class="container" >
        <div class="d-print-none" >
            <!--<a class="btn btn-warning" href="#" id="btnPDF"  >pdf</a>-->
            <a class="btn mt-2 btn-warning" href="#" onclick="window.print();" > چاپ  PDF </a>
            <a class="btn mt-2 <?= ($dir=='rtl') ? 'btn-info':'btn-light' ?>" href="?exam_id=<?=$Encryption->encode($exam_id)?>&dir=rtl"  >راست چین</a>
            <a class="btn mt-2 <?= ($dir=='ltr') ? 'btn-info':'btn-light' ?>" href="?exam_id=<?=$Encryption->encode($exam_id)?>&dir=ltr" >چپ چین </a>
            <a class="btn mt-2 btn-secondary"  href="examForm.php?id=<?=$Encryption->encode($exam_id)?>" >بازگشت به صفحه سوالات</a>
        </div>
        <div id="pdfContent"  style="overflow: auto;" >
            <div dir="<?=$dir?>" style="overflow: auto;" >
              <table class="w-100 rtl" border="1">
                <tr>
                  <td width="300" >امتحان:<?=$exam->name?></td>
                  <td colspan="2" rowspan="3" class="p-1">
                    <textarea id="schoolName" class="w-100 text-center" placeholder="در این قسمت می توانید نام مدرسه را وارد کنید" ></textarea>
                  </td>
                  <td width="200" >تاریخ امتحان: <input style="width: 80px" value="" /></td>
                  <td rowspan="4" class="text-center" width="170" >مهر آموزشگاه</td>
                </tr>
                  <tr>
                  <td>نام دانش آموز:</td>
                  <td>مدت امتحان: <input style="width: 40px" value="<?=$exam->duration?>" /> دقیقه</td>
                </tr>
                  <tr>
                  <td>کلاس:<input style="width: 150px" value="" /></td>
                  <td>تعداد صفحات:<input style="width: 30px" value="" /></td>
                </tr>
                <tr>
                  <td>پایه تحصیلی:<input style="width: 150px" value="" /></td>
                  <td class="p-1">نوبت:<input style="width: 80px" value="" /></td>
                  <td>سال تحصیلی:<input style="width: 100px" value="" /></td>
                  <td>نام دبیر:<input style="width: 100px" value="" /></td>
                </tr>
              </table>
                <table class="w-100"  border="1" cellspacing="0" cellpadding="5"  id="tableQuestion">
                    <tr>
                      <td class='text-center' width="40" >#</td>
                      <td>سوال</td>
                      <td>بارم</td>
                    </tr> 
                  <?php $i=1;foreach($listExamQuestion as $question):?>
                  <tr>
                    <td class='text-center' ><?=$i++?></td>
                    <td class='name' >
                        <?=$Func->fixStr($question->question,"<img>")?>
                        <br/>
                        <?php if($question->question_type_id==1 || $question->question_type_id==""):?>
                        <table cellpadding="5"  border="0" >
                            <tr>
                                <td class="p-1" width="250" ><?=$options[$dir][0]?>) <?=$Func->fixStr($question->a)?></td>
                                <td class="p-1" width="250" ><?=$options[$dir][1]?>) <?=$Func->fixStr($question->b)?></td>
                                <td class="p-1" width="250" ><?=$options[$dir][2]?>) <?=$Func->fixStr($question->c)?></td>
                                <td class="p-1" width="250" ><?=$options[$dir][3]?>) <?=$Func->fixStr($question->d)?></td>
                            </tr>
                        </table>
                        <?php elseif($question->question_type_id==2):?>
                        <table cellpadding="5" >
                            <tr>
                                <td class="p-1" width="250" ><?=$options[$dir][0]?>) <?=$Func->fixStr($question->a)?></td>
                                <td class="p-1" width="250" ><?=$options[$dir][1]?>) <?=$Func->fixStr($question->b)?></td>
                            </tr>
                        </table>
                        <?php else:?>
                          <br/><br/>
                        <?php endif ?>
                    </td>
                    <td ><?=($question->score=="" || $question->score==0 ? "":$question->score+0)?></td>
                  </tr>
                  <?php endforeach?>
                </table>
                <style>
                    body
                    {
                        background: #fff;
                        padding: 10px;
                    }
                    
                    body[dir="ltr"] #tableQuestion tr td
                    {
                      font-family:arial;
                    }

                    #tableQuestion tr td
                    {
                      vertical-align: top;
                    }
                    
                    table tr td,
                    textarea,input,p,span
                    {
                        font-size: 18px !important;
                        word-break: break-word;

                    }
                    
                    textarea,input
                    {
                      text-align: center;
                    }
                    
                    #schoolName
                    {
                      height: 100px !important;
                      resize: none;
                    }
                    
                    @media screen and (max-width: 480px)
                    {
                       table tr td,
                        textarea,input,p,span
                        {
                            font-size: 8px !important;
                            word-break: break-word;
    
                        }
                    }
                    
                    @media print
                    {
                        button {
                          display: none !important;
                        }
                       input,
                        textarea {
                          border: none !important;
                          box-shadow: none !important;
                          outline: none !important;
                        }
            
                        ::-webkit-input-placeholder { /* WebKit browsers */
                            color: transparent;
                        }
                        :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
                            color: transparent;
                        }
                        ::-moz-placeholder { /* Mozilla Firefox 19+ */
                            color: transparent;
                        }
                        :-ms-input-placeholder { /* Internet Explorer 10+ */
                            color: transparent;
                        }
                       @page { margin: 20px; }
                        body { margin: 1.6cm; }
                    }
                </style>
            </div>
        </div>
    </div>
</body>
<script>
    //$("#btnPDF").click(function()
    //{
    //    var data=$("#pdfContent").html();
    //  
    //});
    //
</script>
</html>
