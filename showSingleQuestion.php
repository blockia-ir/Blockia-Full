       
            <div class="row bg-white border p-2 text-center mb-1">
                <div class="col-md-12" >
                    <b class="text-primary" >امکان  بازگشت به  سوالات قبلی وجود ندارد</b>
                </div>
            </div>
      
<?php
    if(!isset($exam_id))
        exit("خطا: امکان  باز کردن مستقیم  این فایل وجود ندارد");
        
    $i=0;
    $buttonText="نمایش سوال بعدی";
    $buttonClass="btn-outline-primary btnNextQuestion";
    $listQuestion=array();
    $countAnswered=count($arrayDateAnswer);
    
    foreach($listExamQuestion as $question):
    
        //if question is answered not show
        if(isset($arrayDateAnswer[$question->id]))
            continue;
            
        $i++;
    
        $number_question=$i+$countAnswered;
        
        if($count_question==$number_question)
        {
            $buttonText=" ثبت نهایی آزمون ";
            $buttonClass="btn btn-sm btn-success btnNextQuestion";
        }

                
    $listQuestion[$i]="
    <div  id='blockQuestion{$question->id}'
                            class='row bg-white mb-1 p-1  border blockQuestion ".checkDirection($question->question)."' >
          <div class='col-md-12 p-1' >
            <div class='question ".checkDirection($question->question)."' >
              <div class='text-secondary small numberOfQuestion' > سوال $number_question از $count_question </div>
              ".fix($question->question)."
            </div>

          </div>";
          
          if($question->question_type_id!=5):
          $listQuestion[$i].="
          <div class='col-md-12 blockOption h-100 p-1' >
            <input type='radio'  class='radioOption ' ".("a"==@$arrayAnswer[$question->id] ? "checked" : "")." name='answer{$question->id}' id='a{$question->id}' data-id='a{$question->id}'  value='a' />
            <label for='a{$question->id}' class='option w-100 border rounded h-100 p-2 ".checkDirection($question->a)."' >".fix($question->a)."</label>
          </div>
          <div class='col-md-12 blockOption h-100 p-1' >
            <input type='radio'  class='radioOption' ".("b"==@$arrayAnswer[$question->id] ? "checked" : "")." name='answer{$question->id}' id='b{$question->id}'  data-id='b{$question->id}' value='b' />
            <label for='b{$question->id}'  class='option w-100 border rounded h-100 p-2 ".checkDirection($question->b)."' >".fix($question->b)."</label>
          </div>
          <div class='col-md-12 blockOption h-100 p-1 ".(@$question->question_type_id==2 ? "d-none" :"" )."'  >
            <input type='radio'  class='radioOption' ".("c"==@$arrayAnswer[$question->id] ? "checked" : "")." name='answer$question->id' id='c$question->id'  data-id='c$question->id'  value='c' />
            <label for='c{$question->id}'  class='option w-100 border rounded h-100 p-2 ".checkDirection($question->c)."' >".fix($question->c)."</label>
          </div>
          <div class='col-md-12 blockOption h-100 p-1 ".(@$question->question_type_id==2 ? "d-none" :"" )."' >
            <input type='radio'  class='radioOption' ".("d"==@$arrayAnswer[$question->id] ? "checked" : "" )." name='answer$question->id' id='d$question->id'  data-id='d$question->id' value='d' />
            <label for='d{$question->id}'  class='option w-100 border rounded h-100 p-2 ".checkDirection($question->d)."' >".fix($question->d)."</label>
          </div>
          <div class='col-md-12' >
              <div id='msg{$question->id}' class='small bold'>&nbsp;</div>
          </div>
          ";
           endif;
          if($question->question_type_id==5):
          $listQuestion[$i].="
          <div class='col-md-12' >
            <textarea rows='5' class='form-control answerInput ".checkDirection($question->question)."'  name='answer{$question->id}' id='{$question->id}' data-id='{$question->id}'  >".(@$arrayAnswer[$question->id]=="e" ? "":@$arrayAnswer[$question->id])."</textarea>
          </div>
          <div class='col-md-4 col-6 mb-1 mt-1' >
            <button class='btn btn-info btn-sm mt-1 w-100 btnSetAnswerQuestion'  data-id='{$question->id}' data-quetion-id='{$question->id}' > ثبت پاسخ  </button>
          </div>
          <div class='col-md-8 col-6 mt-3 p-0' >
              <div id='msg{$question->id}' class='small bold'>&nbsp;</div>
          </div>";
           endif;
          
          $listQuestion[$i].="
          <div class='col-md-12 mt-1 mb-2 ' >
            <button class='btn btn-sm $buttonClass ' data-id='{$question->id}' >$buttonText</button>
          </div>
          
  </div>";
            endforeach;
            if($number_question==0)
            {
                header("location:$urlFinishExam");
                exit;
            }
        ?>
        
  <div id="showQuestion" ></div>
  <!-- listQuestionNumber -->
  <div class="row bg-white p-2 border mb-2" id="listQuestionNumber" >
    <?php for($i=0;$i<$count_question;$i++):?>
        <div class="col-1 p-1"  >
            <button id="questionNumber<?=$listExamQuestion[$i]->id?>" class="btn btn-sm text-center
            <?=(isset($arrayDateAnswer[$listExamQuestion[$i]->id]) ? "btn-secondary":"btn-outline-secondary") ?> "
            disabled style="width: 30px" ><?=$i+1?></button>
        </div>
    <?php endfor ?>
  </div>

<script>
    var i=0;
    var urlFinishExam="<?=$urlFinishExam?>";
    var listQuestion=[
      <?php foreach($listQuestion as $question):?>
      "<?= rawurlencode(trim(preg_replace('/\s\s+/', ' ', $question)));?>",
      <?php endforeach ?>
    ];

    $("#showQuestion").html(decodeURIComponent(listQuestion[i]));

    $(document).on("click",".btnNextQuestion , #btnNextQuestionSetNull",function(){
        
        var question_id=$(this).attr("data-id");
        //var answer=$("input[name='answer"+question_id+"']:checked , textarea[name='answer"+question_id+"']").val();
        var setNull=$(this).attr("data-null");
        
        if(objQuestionAnswered[question_id]==false && setNull!="true")
        {
          $("#btnNextQuestionSetNull").attr("data-id",question_id);
          $('#confirmWithOutAnswer').modal('show');
          if(i>=listQuestion.length-1)
          {
            $("#btnNextQuestionSetNull").html("ثبت نهایی آزمون");
            $("#textModal").text("ثبت نهایی آزمون");
          }
      
          return false;
        }

        if(setNull=="true")
        {
            setAnswer(question_id,"");
        }
        
        if(i<listQuestion.length-1)
        {
            $("body,html").animate({scrollTop:0},1000);
            $("#showQuestion").hide().html(decodeURIComponent(listQuestion[++i])).fadeIn();
             
        }
        else
        {
            location.replace(urlFinishExam);
        }
        
        $("#questionNumber"+question_id).removeClass( "btn-outline-secondary" ).addClass( "btn-secondary" );

    });      
        
</script>

<div class=" rtl modal fade" id="confirmWithOutAnswer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <p class="text-danger bold big" >  هشدار ثبت سوال بدون پاسخ </p>
            </div>
            <div class="modal-body">
                <p class=" bold big" >
                    شما به این سوال پاسخ نداده اید.  درصورتیکه  گزینه 
                    <span id='textModal' class="bold" > نمایش سوال بعدی </span>
                    را انتخاب کنید پاسخ خالی برای این  سوال ثبت خواهد شد. امکان  بازگشت به این سوال وجود ندارد
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal" >به سوال پاسخ می دهم</button>
                <button type="button"  class="btn btn-sm btn-outline-danger" id="btnNextQuestionSetNull" data-null="true" data-dismiss="modal" data-id="aa"  > نمایش سوال بعدی</button>
            </div>
        </div>
    </div>
</div>
