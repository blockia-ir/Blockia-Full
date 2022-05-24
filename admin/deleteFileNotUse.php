<?php
if(!admin)
{
    exit("error: you can not access this page");
}


$sql="SELECT * FROM `{$DB->tablePrefix}upload_files` WHERE (SELECT GROUP_CONCAT(question,a,b,c,d) FROM {$DB->tablePrefix}exam_question) NOT LIKE  concat('%',path,'%')";