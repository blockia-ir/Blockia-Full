<?php require_once "blockPageLoader.php";

require_once "../lib/Excel/PHPExcel.php";


$table="{$DB->tablePrefix}exam_result";//define var
$where=" {$DB->tablePrefix}exam_result.block_user_id='$blockUserId'  AND ";
if(isset($where))
	$where=$where;
else
	$where="";

$allowSearch=array("exam_id","class_name","student_name");
//search if set
if(!empty($_GET['searchData']))
{
  $searchData=strip_tags($_GET['searchData']);
  $searchData=json_decode($searchData,true);
  
  if(!is_array($searchData))
  {
    exit("error: search data not valid");
  }
  
  foreach($searchData as $field=>$search)
  {
    if(!in_array($field,$allowSearch))
    {
      exit("error:  don't allow search in $field");
    }
    
		if($field=="exam_id" && empty($search['value']))
		{
		 exit("خطا: لطفا آزمون  را انتخاب کنید" );
		}
		
    if($search['value']!="" && $search['dataType']=="text")
    {
        $serachValue=strip_tags(addslashes($search['value']));
        $where.="(`$table`.`$field`=<bind>$serachValue</bind> OR `$table`.`$field` LIKE <bind>%$serachValue%</bind> ) AND ";
    }
		elseif($search['value']!="" && $search['dataType']=="select")
		{
			   $serachValue=strip_tags(addslashes($search['value']));
					$where.=" `$table`.`$field`=<bind>$serachValue</bind> AND ";

		}

  }
}
else
{
    exit("خطا: آزمون  را انتخاب کنید");
}

$where=$Func->strReplaceLast("AND","",$where);

if(!empty($where))
{
    $where="WHERE $where";
}

$listExamResult=$DB->query("SELECT {$DB->tablePrefix}exam_result.*,{$DB->tablePrefix}class_student.code FROM {$DB->tablePrefix}exam_result 
                LEFT JOIN {$DB->tablePrefix}class_student on 
                {$DB->tablePrefix}exam_result.class_student_id={$DB->tablePrefix}class_student.id
               $where LIMIT 100000");

if(count($listExamResult)==0)
	exit("خطا: نتیجه ای برای آزمون  مورد نظر وجود ندارد");
	
if (PHP_SAPI == 'cli')
	die('This  should only be run from a Web Browser');


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$col=array("","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","","","","","",);

// Set document properties
$objPHPExcel->getProperties()->setCreator("blockiaExam")
							 ->setLastModifiedBy("blockiaExam")
							 ->setTitle("Office 2007 XLSX exam result List")
							 ->setSubject("Office 2007 XLSX exam result List")
							 ->setDescription("exam result list for Office 2007 XLSX, generated using blockiaExam.")
							 ->setKeywords("exam result list blockiaExam")
							 ->setCategory("exam_result");

$objPHPExcel->getActiveSheet()->mergeCells('A1:L1');

// Add some data 
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'کلاس')
            ->setCellValue('B2', 'کد')
            ->setCellValue('C2', 'نام')
            ->setCellValue('D2', 'وضعیت')
            ->setCellValue('E2', 'نمره')
            ->setCellValue('F2', 'درست')
            ->setCellValue('G2', 'غلط')
            ->setCellValue('H2', 'بدون پاسخ')
            ->setCellValue('I2', 'درصد')
            ->setCellValue('J2', 'آی پی')
            ->setCellValue('K2', 'زمان  شروع')
            ->setCellValue('L2', 'زمان پایان');

for($i=1;$i<=12;$i++)
{
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($col[$i]."2")->applyFromArray(
       array(
           'fill' => array(
               'type' => PHPExcel_Style_Fill::FILL_SOLID,
               'color' => array('rgb' => '022f5d')
           ),
           'font'=>array(
            'color' => array('rgb' => 'ffffff'),
            'bold'  => true
           )
       )
    );
}
 
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

                                                    
$i=3;
foreach($listExamResult as $examResult):

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$i", $examResult->class_name)
                ->setCellValue("B$i", $examResult->code)
                ->setCellValue("C$i", $examResult->student_name)
                ->setCellValue("D$i", $examResult->marked==1 ? "تصحیح شده":"")
                ->setCellValue("E$i", $examResult->mark)
                ->setCellValue("F$i", $examResult->count_true)
                ->setCellValue("G$i", $examResult->count_false)
                ->setCellValue("H$i", $examResult->count_empty)
                ->setCellValue("I$i", $examResult->percent)
                ->setCellValue("J$i", $examResult->ip)
                ->setCellValue("K$i", $Jdf->convertToJalali($examResult->date_create))
                ->setCellValue("L$i", $Jdf->convertToJalali($examResult->date_finsh));
    $i++;

endforeach;

//// Miscellaneous glyphs, UTF-8
//$objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('A4', 'Miscellaneous glyphs')
//            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');


// $Func->faNumToEn($Jdf->now());

$exam_name='نتایج آزمون: '.$listExamResult[0]->exam_name;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $exam_name);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle("Exam Result");
$objPHPExcel->getActiveSheet()->setRightToLeft(true);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$fileName=$exam_name;
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
