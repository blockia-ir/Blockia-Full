<?php
    if(!isset($exam_id))
        exit("خطا امکان  باز کردن مستقیم  این فایل وجود ندارد");
        
    $i=0;
    foreach($listExamQuestion as $question):
    $i++;
?>
<div class="row flex-row bg-white mb-1 p-1  border blockQuestion <?=checkDirection($question->question);?>" >

    <div class="col-md-12 p-1" >
        <div class="question <?=checkDirection($question->question);?>" >
          <?= $i.") ".fix($question->question);?>
        </div>
    </div>
    
    <?php if($question->question_type_id!=5):?>
    <div class="col-md-3 col-sm-6  p-1" >
        <input type="radio"  class="radioOption " <?=("a"==@$arrayAnswer[$question->id]) ? "checked" : "" ?> name="answer<?=$question->id?>" id="a<?=$question->id?>" data-id="a<?=$question->id?>"  value="a" />
        <label for="a<?=$question->id?>" class='option w-100 border rounded h-100 p-2 <?=checkDirection($question->a)?>' ><?=fix($question->a)?></label>
    </div>
    
    <div class="col-md-3 col-sm-6  p-1" >
        <input type="radio"  class="radioOption" <?=("b"==@$arrayAnswer[$question->id]) ? "checked" : "" ?> name="answer<?=$question->id?>" id="b<?=$question->id?>"  data-id="b<?=$question->id?>" value="b" />
        <label for="b<?=$question->id?>"  class='option w-100 border rounded h-100 p-2 <?=checkDirection($question->b)?>' ><?=fix($question->b)?></label>
    </div>
    
    <div class="col-md-3 col-sm-6  p-1 <?=(@$question->question_type_id==2 ? "d-none" :"" )?>"  >
        <input type="radio"  class="radioOption" <?=("c"==@$arrayAnswer[$question->id]) ? "checked" : "" ?> name="answer<?=$question->id?>" id="c<?=$question->id?>"  data-id="c<?=$question->id?>"  value="c" />
        <label for="c<?=$question->id?>"  class='option w-100 border rounded h-100 p-2 <?=checkDirection($question->c)?>' ><?=fix($question->c)?></label>
    </div>
    
    <div class="col-md-3 col-sm-6  p-1 <?=(@$question->question_type_id==2 ? "d-none" :"" )?>" >
        <input type="radio"  class="radioOption" <?=("d"==@$arrayAnswer[$question->id]) ? "checked" : "" ?> name="answer<?=$question->id?>" id="d<?=$question->id?>"  data-id="d<?=$question->id?>" value="d" />
        <label for="d<?=$question->id?>"  class='option w-100 border rounded h-100 p-2 <?=checkDirection($question->d)?>' ><?=fix($question->d)?></label>
    </div>
    
    <div class="col-md-12" >
        <div id="msg<?=$question->id?>" class="small"></div>
    </div>
    
    <?php endif?>
    
    <?php if($question->question_type_id==5):?>
    <div class="col-md-9" >
        <textarea class="form-control answerInput <?=checkDirection($question->question);?>"  name="answer<?=$question->id?>" id="<?=$question->id?>" data-id="<?=$question->id?>"  ><?= (@$arrayAnswer[$question->id]=="e" ? "":@$arrayAnswer[$question->id]) ?></textarea>
    </div>
    
    <div class="col-md-3" >
        <div class="row" >
            <div class="col-md-12 col-6" >
                <button class="btn btn-info btn-sm mt-1 w-100 btnSetAnswerQuestion" id="<?=$question->id?>" data-id="<?=$question->id?>" data-quetion-id="<?=$question->id?>" > ثبت پاسخ  </button>
            </div>
            <div class="col-md-12 col-6" >
                <div id="msg<?=$question->id?>" class="small text-center mt-1"></div>
            </div>
        </div>
    </div>
    <?php endif?>
</div>
<?php endforeach?>

<div class="row mb-5" >
    <div class="col-md-12" >
        <a href="<?=$urlFinishExam?>"  id="btnSetExam" class="btn btn-success w-100"  >ثبت آزمون</a>
    </div>
</div>
