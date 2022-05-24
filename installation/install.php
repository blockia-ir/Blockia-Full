<?php require_once "pageLoader.php";
    

$DBname=$_POST['DBname'];
$DBuser=$_POST['DBuser'];
$DBpass=$_POST['DBpass'];
$DBhost=$_POST['DBhost'];
$name=$_POST['name'];
$username=$_POST['username'];
$email=$_POST['email'];
$password=$_POST['password'];
$date=date("Y-m-d H:i:s");
$valid=true;

if($DBname=="")
{
    $Msg->error("نام پایگاه داده را وارد کنید");
    $valid=false;
}

if($DBuser=="")
{
    $Msg->error("نام کاربری پایگاه داده را وارد کنید");
    $valid=false;
}

//if($DBpass=="")
//{
//    $Msg->error("رمز عبور پایگاه داده را وارد کنید");
//    $valid=false;
//}

if($DBhost=="")
{
    $Msg->error("آدرس پایگاه داده را وارد کنید");
    $valid=false;
}

if($name=="")
{
    $Msg->error("نام خود را وارد کنید");
    $valid=false;
}

if($username=="")
{
    $Msg->error("نام کاربری را وارد کنید");
    $valid=false;
}

if($password=="")
{
    $Msg->error("رمز عبور را وارد کنید");
    $valid=false;
}

if($valid==false)
{
    $Msg->show();
    exit;
}


try
{
  $conn = new PDO("mysql:host=$DBhost;dbname=$DBname;charset=utf8mb4", $DBuser, $DBpass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
  //echo "Connected successfully";
}
catch(PDOException $e)
{
    $Msg->error("ارتباط با دیتابیس انجام نشد <br/> اطلاعات وارد شده صحیح نیست");
    $Msg->show();
    exit;
}

$TABLE_PREFIX=$Func->randomString(5,1)."_";
$EncryptionKey=$Func->randomString(32,4);

$sql_database=file_get_contents("database.sql");
$sql_database=str_replace("_tablePrefix_",$TABLE_PREFIX,$sql_database);

if(!$conn->query($sql_database))
{
    $Msg->error("پایگاه داده  بدرستی نصب نشد دوباره  تلاش کنید");
    $Msg->show();
    exit;
}

$config="<?php
define('DBdriver','mysql');
define('DBname','_dbname_');
define('DBuser','_dbuser_');
define('DBpass','_dbpass_');
define('DBhost','_host_');
define('TABLE_PREFIX','_tablePrefix_'); // dont change
define('EncryptionKey','_encryption_key_'); // dont change
define('INSTALL_DATE','_install_date_');";

$arr1=array("_dbname_","_dbuser_","_dbpass_","_host_","_tablePrefix_","_encryption_key_","_install_date_");
$arr2=array($DBname,$DBuser,$DBpass,$DBhost,$TABLE_PREFIX,$EncryptionKey,$date);
$config=str_replace($arr1,$arr2,$config);

$result=file_put_contents("../lib/php/config.php",$config);
if($result)
{
    if(chmod("../lib/php/config.php", 0600))
    {
      //$Msg->success("تنظیمات با موفقیت ثبت شد");
    }
    else
    {
      $Msg->error("سطح دسترسی فایل ../lib/php/config.php بدرستی تنظیم  نشد <br/>پس از نصب به صورت دستی سطح دسترسی را برابر با 600 قرار دهید");
    }
}
else
{
  $Msg->error("خطای در ثبت تنظیمات رخ داده دسترسی فایل را بررسی کنید");
  $Msg->show();
  exit;
}


$Encryption=new Encryption();
$Encryption->setKey($EncryptionKey);
$id=rand(111,999);
$admin=$Encryption->encodeAdmin($id);
$passwordHash=password_hash(($password),PASSWORD_BCRYPT,['cost'=>12]);
$result=$conn->query("INSERT INTO `$TABLE_PREFIX"."block_users`
              (`id`, `name`, `username`, `password`, `email`, `admin`, `date_create`)
             VALUES
              ($id, '$name', '$username', '$passwordHash', '$email', '$admin', '$date')");
if($result)
{

  $Msg->success("نرم افزار باموفقیت نصب شد <br/>
                اطلاعات ورود <br/> نام کاربری: $username <br/> رمز عبور: $password<br/>");
  
  $index=file_get_contents("indexSample.php");
  file_put_contents("../index.php",$index);
  chmod("../index.php", 0644);
  
  //remove installation folder
  $path=dirname(__FILE__);//current folder 
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
  
  if(!rmdir($path))
  {
      $Msg->error("پوشه installation را به صورت دستی حذف کنید");
  }
  
  exit ("
  <script>
  location.replace('../admin/login.php');
  </script>");
  
  
}
else
{
  $Msg->error("کاربرمدیر درج نشد");
  $Msg->show();
  exit;
}







