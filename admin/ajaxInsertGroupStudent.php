<?php require_once "blockPageLoader.php";

$class_id=strip_tags($_POST['class_id']);
$listStudent=strip_tags($_POST['listStudent']);
$code=$name="";

if(!is_numeric($class_id))
{
	exit("error: class id not valid");
}

$countClass=$DB->queryOutPut("SELECT count(*) FROM {$DB->tablePrefix}class WHERE block_user_id='$block_user_id' AND id='$class_id' ");

if($countClass==0)
{
	exit("error dont access this class  id:  $class_id");
}
     
$table='<table class="table bg-white"  style="border:0px;max-width: 500px;" dir="rtl">
    <thead><tr align="center" class="bg-success" ><th>ردیف</th><th>کد ورود</th><th>نام دانش آموز</th><th>وضیعت</th></tr> </thead>';
$count=0;
$str= strtok($listStudent, "\n");
$array=explode("\n",$listStudent);
for($i=0;$i<count($array);$i++)
{
    $value=explode("\t",$array[$i]);
    
	if( @$value[0]!="" && @$value[1]!=""  )
	{
        
        $value[0]=$Func->faNumToEn($value[0]);
        $value[1]=$Func->faNumToEn($value[1]);
		//jsut for rtl and ltr in Excel
        if(is_numeric($Func->removeAllSpace($value[0])))
		{
            $code=$Func->removeAllSpace($value[0]); 
            $name=$value[1];
        }
		elseif(is_numeric($Func->removeAllSpace($value[1])))
		{
            $code=$Func->removeAllSpace($value[1]);
            $name=$value[0];
        }
    }
	else
	{
        continue;
    }
	
	
	if(is_numeric($code) && $name!="")
	{
		$countClassStudent=$DB->queryOutPut("SELECT count(*) FROM {$DB->tablePrefix}class_student WHERE block_user_id='$block_user_id' AND code=<bind>$code</bind> ");
		if($countClassStudent==0)
		{
			$result=$DB->insert("{$DB->tablePrefix}class_student",["block_user_id"=>"$block_user_id","name"=>"$name","code"=>"$code","class_id"=>"$class_id"]);
			if($result)
			{
				$count++;
				$table.="<tr class=''>
							<td>$count</td>
							<td>$code</td>
							<td>$name</td>
							<td class='text-success font-weight-bold'>درج شد</td>
							</tr>";
			}
		}
		else
		{
			$count++;
			$table.="<tr class='' >
						<td>$count</td>
						<td>$code</td>
						<td>دانش آموز <b><i>$name</i></b> وجود دارد</td>
						<td class='text-danger font-weight-bold' >درج نشد</td>
					</tr>";
		}
	}
	 
}

echo $table.="</table>";

?>
