<?php require_once "blockPageLoader.php";

$question_id=$Encryption->decode(strip_tags($_GET['question_id']));
$exam_id=$Encryption->decode(strip_tags($_GET['exam_id']));

if(!is_numeric($question_id))
{
    exit("error question id not valid");
}

if(!is_numeric($exam_id))
{
    exit("error exam id not valid");
}


$question=$DB->query("SELECT * FROM {$DB->tablePrefix}exam_question
                     WHERE block_user_id='$block_user_id' AND id='$question_id' AND exam_id='$exam_id' ",true);
if(isset($question->id))
{
    /*
     //delete image
    $text=$question->question.$question->a.$question->b.$question->c.$question->d;
    
    // delete any img form question
    $doc = new DOMDocument();
    @$doc->loadHTML($text);
    $tags = $doc->getElementsByTagName('img');
    foreach ($tags as $tag)
    {
        unlink($_SERVER['DOCUMENT_ROOT'].$tag->getAttribute('src'));
    }
    
    */
    
    //delete question
    $result=$DB->prepare("DELETE FROM {$DB->tablePrefix}exam_question
                         WHERE block_user_id='$block_user_id' AND id='$question_id' AND exam_id='$exam_id' ");
    if($result)
    {
        $Msg->success("سوال با موفقیت حذف شد");
    }
}
else
{
    $Msg->error("سوال مورد نظر یافت نشد");
}

header("location:examForm.php?id={$Encryption->encode($exam_id)}");
exit;
