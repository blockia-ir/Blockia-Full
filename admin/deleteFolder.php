<?php exit("exit in line 1");require_once "blockPageLoader.php";
if(!admin)
{
    exit("error: you can not access this page");
}

$folder_name=$Encryption->decode(strip_tags($_GET['folder_name']));

if($folder_name=="")
{
    exit("erro folder name not set");
}

$path="../source/$folder_name";

$files = scandir($path);
foreach($files as $file)
{
    if($file === '.' or $file === '..')
    {
        continue;
    }
    else
    {
        unlink($path . '/' . $file);
    }
}

if(rmdir($path))
{
    $Msg->success("پوشه مورد نظرحذف شد");
    header("location:fileManager.php");
    exit;
}
