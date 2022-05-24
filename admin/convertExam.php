<?php require_once "blockPageLoader.php";

$exam_id=$Encryption->decode(strip_tags($_GET['exam_id']));
$score=strip_tags($_GET['score']);

if(!is_numeric($exam_id))
    exit("error: exam id not valid");
    
if(!empty($score))
{
    if(!is_numeric($score))
        exit("error: score not valid");
        
    if($score>5 || $score<0.25)
        exit("error: score must between 0.25 to 5 ");

}

$exam=$DB->query("SELECT * FROM {$DB->tablePrefix}exam WHERE id='$exam_id' AND block_user_id='$blockUserId' " ,true);
if(!isset($exam->id))
    exit("error: exam not found");
    
if($exam->type==1)
{
    $result=$DB->update("{$DB->tablePrefix}exam",['type'=>2,"negative"=>0,"number_question"=>0],"WHERE id='{$exam->id}' AND block_user_id='$blockUserId'");
    if($result)
    {
        $DB->update("{$DB->tablePrefix}exam_question",['score'=>$score],"WHERE exam_id='{$exam->id}' AND block_user_id='$blockUserId'" );
        $Msg->success("آزمون  تستی به  آزمون  تشریحی تبدیل شد");
    }
    
}
elseif($exam->type==2)
{
    $result=$DB->update("{$DB->tablePrefix}exam",['type'=>1,"negative"=>0,"number_question"=>0],"WHERE id='{$exam->id}' AND block_user_id='$blockUserId' ");
    if($result)
    {
        $DB->update("{$DB->tablePrefix}exam_question",['score'=>0],"WHERE exam_id='{$exam->id}' AND block_user_id='$blockUserId'" );
        $DB->prepare("DELETE FROM {$DB->tablePrefix}exam_question
                     WHERE question_type_id=5 AND exam_id='{$exam->id}' AND block_user_id='$blockUserId' ");
        $Msg->success("آزمون تشریحی به آزمون تستی تبدیل شد");
    }
}

header("location:examForm.php?id={$Encryption->encode($exam_id)}");
exit;

?>
