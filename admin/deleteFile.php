<?php exit("exit in line 1"); require_once "blockPageLoader.php";
if(!admin)
{
    exit("error: you can not access this page");
}

$file_name=$Encryption->decode(strip_tags($_GET['file_name']));

if($file_name=="")
{
    exit("error file name not set");
}

$path="../source/$file_name";

if(file_exists($path))
{
    unlink($path);
    $Msg->success("فایل باموفقیت حذف شد");
}
else
{
    $Msg->error("فایل وجود نداشت");
}
header("location:{$_SERVER['HTTP_REFERER']}");
exit;
