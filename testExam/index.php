<?php
if(count(@$_REQUEST)==0 || !isset($_REQUEST['exam']) )
{
    header("location:panel/index.php");
    exit;
}
elseif(isset($_REQUEST['exam'])  && is_numeric($_REQUEST['exam']))
{
    header("location:exam.php?id=$_REQUEST[exam]");
    exit;
    
}
elseif( !is_numeric(@$_REQUEST['exam']) && isset($_REQUEST['exam']) )
{
    exit("<h1>صفحه مورد نظر یافت نشد</h1>");
};
?>