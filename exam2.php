<?php ob_start();

$id=$exam_id=strip_tags(@$_REQUEST['id']);
if(!is_numeric($id)){
    exit("خطا: آزمون نامعتبر است");
}

header("location:exam.php?id=$id");
exit;