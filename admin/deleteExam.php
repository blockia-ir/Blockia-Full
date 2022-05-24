<?php require_once "blockPageLoader.php";

$exam_id=$id=$Encryption->decode(strip_tags($_GET['exam_id']));

if(!is_numeric($exam_id))
{
    exit("error exam id not valid");
}

//find exam user
$exam=$DB->query("SELECT * FROM {$DB->tablePrefix}exam
                 WHERE block_user_id=<bind>$block_user_id</bind> AND
                 id=<bind>$id</bind> ",true);
if(isset($exam->id))
{
    //delete student answer
    $result=$DB->prepare("
                DELETE {$DB->tablePrefix}student_answer FROM {$DB->tablePrefix}student_answer
                INNER JOIN {$DB->tablePrefix}exam_result on
                {$DB->tablePrefix}student_answer.exam_result_id={$DB->tablePrefix}exam_result.id
                AND {$DB->tablePrefix}exam_result.exam_id=<bind>$exam_id</bind>
                WHERE {$DB->tablePrefix}exam_result.block_user_id=<bind>$block_user_id</bind> ");
    if($result)
    {
        //delete exam result
        $result=$DB->prepare("DELETE FROM {$DB->tablePrefix}exam_result
                             WHERE block_user_id=<bind>$block_user_id</bind> AND
                             {$DB->tablePrefix}exam_result.exam_id=<bind>$exam_id</bind> ");
        if($result)
        {
                /*
                
                //delete image 
                $exam=$DB->query("SELECT GROUP_CONCAT(CONCAT(COALESCE(question,''),COALESCE(a,''),
                        COALESCE(b,''),COALESCE(c,''),COALESCE(d,'')),',') as text FROM `exam_question`
                        WHERE block_user_id=<bind>$block_user_id</bind> AND exam_id=<bind>$exam_id</bind>",true);
             
                $text=$exam->text;
                 //delete any img form question
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
                                     WHERE block_user_id=<bind>$block_user_id</bind>
                                     AND exam_id=<bind>$exam_id</bind> ");
                if($result)
                {
                    //delete exam
                    $result=$DB->prepare("DELETE FROM {$DB->tablePrefix}exam
                                         WHERE block_user_id=<bind>$block_user_id</bind>
                                         AND id=<bind>$exam_id</bind> ");
                    if($result)
                    {
                        $Msg->success("آزمون با موفقیت حذف شد");
                    }
                    
                }
        }
    }

}
else
{
    $Msg->error("آزمون مورد نظر یافت نشد");
}

header("location:examPage.php");
exit;
