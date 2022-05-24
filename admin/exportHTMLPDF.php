<?php

require_once "blockPageLoader.php";

require_once('../lib/tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
//$pdf->SetCreator(PDF_CREATOR);
//$pdf->SetAuthor('Nicola Asuni');
//$pdf->SetTitle('TCPDF Example 006');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// use the font
$pdf->SetFont('ShabnamFd', '', 14, '', false);
//$pdf->SetFont('arial', '', 10);

// add a page
$pdf->AddPage();

// test some inline CSS
$html = '
            <div dir="rtl">
                <table class="w-100" border="1" cellspacing="0" cellpadding="5" cid="tableQuestion">
                    <tbody><tr>
                        <td colspan="3" class="p-3">
                             کپی  امتحان میان ترم تگ های HTML                            <br><br>
                        </td>
                    </tr>
                    <tr>
                      <td class="">#</td>
                      <td>سوال</td>
                      <td>بارم</td>
                    </tr> 
                                    <tr>
                    <td class="">1</td>
                    <td class="name">
                        ئصضصت&nbsp; ود ثمد
ثضقنضصئضصنم&nbsp;
ثقدقثمنث                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) صثنئثصنئقصث</td>
                                <td class="">ب) صثتصث</td>
                                <td class="">ج) صثصث</td>
                                <td class="">د) صثنصثن</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>1</td>
                  </tr>
                                    <tr>
                    <td class="">2</td>
                    <td class="name">
                        برای ایجاد یک لنگر (پیوند) به کار می رود&nbsp;                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) a</td>
                                <td class="">ب) b</td>
                                <td class="">ج) c</td>
                                <td class="">د) d</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>1</td>
                  </tr>
                                    <tr>
                    <td class="">3</td>
                    <td class="name">
                        برای ایجاد جداول به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) table</td>
                                <td class="">ب) grid</td>
                                <td class="">ج) column</td>
                                <td class="">د) body</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">4</td>
                    <td class="name">
                        برای&nbsp;توپر کردن&nbsp;نوشته&nbsp;( Bold )&nbsp;به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) a</td>
                                <td class="">ب) b</td>
                                <td class="">ج) c</td>
                                <td class="">د) d</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">5</td>
                    <td class="name">
                        باعث بزرگتر نمایش داده شدن نوشته در یک متن می شود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) صحیح</td>
                                <td class="">ب) غلط</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>1</td>
                  </tr>
                                    <tr>
                    <td class="">6</td>
                    <td class="name">
                        برای ایجاد یک زیر نویس به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) sup</td>
                                <td class="">ب) upper</td>
                                <td class="">ج) subtitle</td>
                                <td class="">د) sub</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">7</td>
                    <td class="name">
                        برای نمایش متن همانند محیط های برنامه نویسی به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) dev</td>
                                <td class="">ب) code</td>
                                <td class="">ج) console</td>
                                <td class="">د) base</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">8</td>
                    <td class="name">
                        برای برقراری ارتباط بین صفحه با یک فایل خارجی به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) link</td>
                                <td class="">ب) href</td>
                                <td class="">ج) a</td>
                                <td class="">د) src</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">9</td>
                    <td class="name">
                        برای ایجاد لیست های ترتیبی به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) ul</td>
                                <td class="">ب) li</td>
                                <td class="">ج) ol</td>
                                <td class="">د) dd</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">10</td>
                    <td class="name">
                        برای نمایش متن به همان صورت اولیه به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) p</td>
                                <td class="">ب) pre</td>
                                <td class="">ج) div</td>
                                <td class="">د) span</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">11</td>
                    <td class="name">
                        برای نمایش نوشته به صورت کج به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) i</td>
                                <td class="">ب) b</td>
                                <td class="">ج) em</td>
                                <td class="">د) الف و ج</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">12</td>
                    <td class="name">
                        در بر گیرنده اطلاعات کلی درباره محتویات یک صفحه جهت استفاده موتورهای جستجو است                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) beta</td>
                                <td class="">ب) data</td>
                                <td class="">ج) meta</td>
                                <td class="">د) charset</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">13</td>
                    <td class="name">
                        برای ایجاد لیست های نشانه ای به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) ol</td>
                                <td class="">ب) ul</td>
                                <td class="">ج) li</td>
                                <td class="">د) il</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">14</td>
                    <td class="name">
                        نوع و نسخه زبان برنامه نویسی مورد استفاده در صفحه وب را مشخص می کند                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) TYPEDOC</td>
                                <td class="">ب) DOCHTML</td>
                                <td class="">ج) DOCTYPE</td>
                                <td class="">د) DOCWEB</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">15</td>
                    <td class="name">
                        برای ایجاد یک کادر متن به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) textbox</td>
                                <td class="">ب) textarea</td>
                                <td class="">ج) textinput</td>
                                <td class="">د) textfield</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">16</td>
                    <td class="name">
                        برای ایجاد یک خط در عرض صفحه به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) br</td>
                                <td class="">ب) div</td>
                                <td class="">ج) hr</td>
                                <td class="">د) p</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">17</td>
                    <td class="name">
                        برای قرار دادن تصویر در صفحه به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) src</td>
                                <td class="">ب) image</td>
                                <td class="">ج) img</td>
                                <td class="">د) href</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">18</td>
                    <td class="name">
                        برای ایجاد انواع تیترها در نوشته به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) h1</td>
                                <td class="">ب) h2</td>
                                <td class="">ج) h3</td>
                                <td class="">د) همه موارد</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">19</td>
                    <td class="name">
                        برای نمایش متن حذف شده درصفحه به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) d</td>
                                <td class="">ب) del</td>
                                <td class="">ج) div</td>
                                <td class="">د) pre</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">20</td>
                    <td class="name">
                        در برگیرنده اطلاعات کلی درباره سند وب است                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) thead</td>
                                <td class="">ب) title</td>
                                <td class="">ج) html</td>
                                <td class="">د) head</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                    <tr>
                    <td class="">21</td>
                    <td class="name">
                        برای نوشتن یک آدرس در متن به کار می رود                        <br>
                                                <table cellpadding="" class="w-75">
                            <tbody><tr>
                                <td class="">الف) adress</td>
                                <td class="">ب) address</td>
                                <td class="">ج) addres</td>
                                <td class="">د) adres</td>
                            </tr>
                        </tbody></table>
                                            </td>
                    <td>0</td>
                  </tr>
                                  </tbody></table>
                <style>
                    body
                    {
                        background: #fff;
                        padding: 10px;
                    }
                    table tr td
                    {
                        font-size: 18px;
                    }
                </style>
            </div>
        ';

$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
