<?php 
class Func {
	
	//مناسب کردن  رشته  برای ارسال به پایگاه  داده
	public function fixDbStr($string){
		$string=strip_tags($string);
		$string=addslashes($string);
		return $string;
	}
	
	// fix question , answer  string
	public function fixStr($string)
	{
		$pattern = "/<p[^>]*><\\/p[^>]*>/"; 
	  //$pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";  use this pattern to remove any empty tag
	  $string=preg_replace($pattern, '', $string);
	  $string=str_replace('<p dir="ltr">&nbsp;</p>','',$string);
	  $string=str_replace('<p dir="ltl">&nbsp;</p>','',$string);
	  $string=strip_tags($string,"<img><span>");
	  //$string=str_replace('="../','="/',$string);
	  return nl2br($string);
	}
	
	//ایجاد فایل
	public function createFile($filename,$content) { 
		$f=fopen($filename,"w"); 
		fwrite($f,$content); 
		fclose($f); 
	}
	
	//تبدیل واحد بایت به نوع بزرگتر
	public function byteToLargeType($byte){
		if($byte>1024 and $byte<1048575) return $file_size=round($byte/1024,2) .' KB';
        if($byte>1048576)return $file_size=round($byte/1048576,2) .' MB';
	}
	
	// تبدیل عدد فارسی به انگلیسی
	public function faNumToEn($string) {
		$persian1 = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
		$persian2 = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
		$num = range(0, 9);
		$string=str_replace($persian1, $num, $string);
		return str_replace($persian2, $num, $string);
	}
	
	//تبدیل عدد انگلیسی به فارسی
	public function enNumToFa($string){
		$en_num=array('0','1','2','3','4','5','6','7','8','9');
		$fa_num=array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
		return str_replace($en_num,$fa_num,$string);
	}
	
	//بدست آوردن آدرس اولین تصویر
	public function getFirstImageSrc($string){
	   $temp=strstr($string,"<img");
	   $img=strstr($temp,"/>",true);
	   $temp=strstr($img,'src="');
	   $temp=str_replace('src="',"",$temp);
	   $img_src=strstr($temp,'"',true);
	   return $img_src;
	}
 
	//حذف اولین اولین تصویر
	public function removeFirstImage($string){
	   $temp=strstr($string,"<img");
	   $img=strstr($temp,"/>",true)."/>";
	   $temp=str_replace($img,"",$string);
	   return $temp;
	}
 
	//دریافت تمام تگ های  تصویر
	public function getAllImage($string){
		preg_match_all('/<img[^>]+>/i',$string, $result);
		return $result;
	}
	
	//حذف تمام تصویر های متن
	public function removeAllImage($string){
		return preg_replace("/<img[^>]+\>/i", "", $string); 	
	}
	
	//دریافت صفت تگ
	public function getTagAttribute($tag,$attribute){
		//preg_match_all("/(src)=('[^']*')/i",$img_tag, $img);
		
		preg_match_all('/('.$attribute.')=("[^"]*")/i',$tag, $result);
		if(isset($result[2][0])){
			return str_replace('"',"",$result[2][0]);
		}
		else{
			preg_match_all("/(".$attribute.")=('[^']*')/i",$tag, $result);
			return str_replace("'","",$result[2][0]);
		}
	}
	
	//دریافت تگ
	public function getTags( $html, $tag, $selfclosing = null, $return_the_entire_tag = false, $charset = 'ISO-8859-1' ){
		
	   if ( is_array($tag) ){
		   $tag = implode('|', $tag);
	   }
		
	   //If the user didn't specify if $tag is a self-closing tag we try to auto-detect it
	   //by checking against a list of known self-closing tags.
	   $selfclosing_tags = array( 'area', 'base', 'basefont', 'br', 'hr', 'input', 'img', 'link', 'meta', 'col', 'param' );
	   if ( is_null($selfclosing) ){
		   $selfclosing = in_array( $tag, $selfclosing_tags );
	   }
		
	   //The regexp is different for normal and self-closing tags because I can't figure out 
	   //how to make a sufficiently robust unified one.
	   if ( $selfclosing ){
		   $tag_pattern = 
			   '@<(?P<tag>'.$tag.')           # <tag
			   (?P<attributes>\s[^>]+)?       # attributes, if any
			   \s*/?>                   # /> or just >, being lenient here 
			   @xsi';
	   } else {
		   $tag_pattern = 
			   '@<(?P<tag>'.$tag.')           # <tag
			   (?P<attributes>\s[^>]+)?       # attributes, if any
			   \s*>                 # >
			   (?P<contents>.*?)         # tag contents
			   </(?P=tag)>               # the closing </tag>
			   @xsi';
	   }
		
	   $attribute_pattern = 
		   '@
		   (?P<name>\w+)                         # attribute name
		   \s*=\s*
		   (
			   (?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)    # a quoted value
			   |                           # or
			   (?P<value_unquoted>[^\s"\']+?)(?:\s+|$)           # an unquoted value (terminated by whitespace or EOF) 
		   )
		   @xsi';
	
	   //Find all tags 
	   if ( !preg_match_all($tag_pattern, $html, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE ) ){
		   //Return an empty array if we didn't find anything
		   return array();
	   }
		
	   $tags = array();
	   foreach ($matches as $match){
			
		   //Parse tag attributes, if any
		   $attributes = array();
		   if ( !empty($match['attributes'][0]) ){ 
				
			   if ( preg_match_all( $attribute_pattern, $match['attributes'][0], $attribute_data, PREG_SET_ORDER ) ){
				   //Turn the attribute data into a name->value array
				   foreach($attribute_data as $attr){
					   if( !empty($attr['value_quoted']) ){
						   $value = $attr['value_quoted'];
					   } else if( !empty($attr['value_unquoted']) ){
						   $value = $attr['value_unquoted'];
					   } else {
						   $value = '';
					   }
						
					   //Passing the value through html_entity_decode is handy when you want
					   //to extract link URLs or something like that. You might want to remove
					   //or modify this call if it doesn't fit your situation.
					   $value = html_entity_decode( $value, ENT_QUOTES, $charset );
						
					   $attributes[$attr['name']] = $value;
				   }
			   }
				
		   }
			
		   $tag = array(
			   'tag_name' => $match['tag'][0],
			   'offset' => $match[0][1], 
			   'contents' => !empty($match['contents'])?$match['contents'][0]:'', //empty for self-closing tags
			   'attributes' => $attributes, 
		   );
		   if ( $return_the_entire_tag ){
			   $tag['full_tag'] = $match[0][0];            
		   }
			 
		   $tags[] = $tag;
	   }
		
	   return $tags;
   }
   
	//کوچک کردن رشته
	public function minifyString($str,$num){
	   $temp="";
	   if ($str && $num){
		   if(strlen($str)>$num){
			   $result=substr($str,0,$num);
			   $array=explode(" ",$result);
			   $n=sizeof($array);
			   for($i=0;$i<$n-1;$i++)
			   $temp.=$array[$i]." ";
			   $result=$temp."...";
		   }
		   else{
			   $result=$str;
		   }
		   return $result;
	   }else return false;
	}
	
	 //بدست آوردن نوع فایل
	public function getFileType ($filename){
	  $filename = strtolower($filename) ;
	  $exts = explode(".", $filename) ;
	  $n = count($exts)-1;
	  $exts = $exts[$n];
	  return $exts;
	}
	
	//دریافت سیستم عامل کاربر
	public function getOS() { 
	
		$user_agent=$_SERVER['HTTP_USER_AGENT'];
		$os_platform="Unknown OS Platform";
	
		$os_array=array(
						'/windows nt 10/i'     =>  'Windows 10',
						'/windows nt 6.3/i'     =>  'Windows 8.1',
						'/windows nt 6.2/i'     =>  'Windows 8',
						'/windows nt 6.1/i'     =>  'Windows 7',
						'/windows nt 6.0/i'     =>  'Windows Vista',
						'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
						'/windows nt 5.1/i'     =>  'Windows XP',
						'/windows xp/i'         =>  'Windows XP',
						'/windows nt 5.0/i'     =>  'Windows 2000',
						'/windows me/i'         =>  'Windows ME',
						'/win98/i'              =>  'Windows 98',
						'/win95/i'              =>  'Windows 95',
						'/win16/i'              =>  'Windows 3.11',
						'/macintosh|mac os x/i' =>  'Mac OS X',
						'/mac_powerpc/i'        =>  'Mac OS 9',
						'/linux/i'              =>  'Linux',
						'/ubuntu/i'             =>  'Ubuntu',
						'/iphone/i'             =>  'iPhone',
						'/ipod/i'               =>  'iPod',
						'/ipad/i'               =>  'iPad',
						'/android/i'            =>  'Android',
						'/blackberry/i'         =>  'BlackBerry',
						'/webos/i'              =>  'Mobile'
					);
	
		foreach ($os_array as $regex => $value) { 
	
			if (preg_match($regex, $user_agent)) {
				$os_platform    =   $value;
			}
	
		}   
	
		return $os_platform;
	
	}
	
	//دریافت مرورگر کاربر
	public function getBrowser() {
	
		$user_agent=$_SERVER['HTTP_USER_AGENT'];
		$browser="Unknown Browser";
	
		$browser_array=array(
							'/msie/i'       =>  'Internet Explorer',
							'/firefox/i'    =>  'Firefox',
							'/safari/i'     =>  'Safari',
							'/chrome/i'     =>  'Chrome',
							'/edge/i'       =>  'Edge',
							'/opera/i'      =>  'Opera',
							'/netscape/i'   =>  'Netscape',
							'/maxthon/i'    =>  'Maxthon',
							'/konqueror/i'  =>  'Konqueror',
							'/mobile/i'     =>  'Handheld Browser'
							);
		
		foreach ($browser_array as $regex => $value) { 
		
			if (preg_match($regex, $user_agent)) {
				$browser    =   $value;
			}
		
		}
		return $browser;
	}
	
	//دریافت  آدرس آی پی کاربر
	public function getIP(){
		$ip="";
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	//جایگزینی اولین الگو در رشته
	public function strReplaceFirst($search, $replace, $subject)
	{
		$search = '/'.preg_quote($search, '/').'/';
	
		return preg_replace($search, $replace, $subject, 1);
	}
	
	//جایگزینی آخرین الگو در رشته
	public function strReplaceLast($search, $replace, $subject)
	{
		$pos = strrpos($subject, $search);
	
		if($pos !== false)
		{
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		}
	
		return $subject;
	}
	
	/**
	 *جدا کردن حروف در رشته های فارسی
	 *$strting input,output array
	 */
	function utf8Split($str, $len = 1)
	{
		$arr = array();
		$strLen = mb_strlen($str, 'UTF-8');
		for ($i = 0; $i < $strLen; $i++)
		{
		  $arr[] = mb_substr($str, $i, $len, 'UTF-8');
		}
		return $arr;
	}

	public function removeAllSpace($string){
		return preg_replace('/\s+/', '', $string);
	}
	
	//بدست  آوردن  آخرین کلید آرایه
	public function endKey($array){
		end($array);
		return key($array);
	}
	
	
	//ایجاد آرایه  با کلید
	public function makeArrayKey($str,$separator,$keySeparator){
		$var=explode($separator,$str);
		foreach($var as $key=>$value){
			$k=strstr($value,$keySeparator,true);
			$val=ltrim(strstr($value,$keySeparator),$keySeparator);
			$array[$k]=$val;
		}
		return $array;
	}
	
	/**
	 *convert array with key to string
	 *@array
	 *@separator
	 *@keySeparator
	 */
	public function makeStringKey($array,$separator,$keySeparator){
		$str="";
		foreach($array as $key=>$value){
			$str.=$key.$keySeparator.$value.$separator;
		}
		$str=rtrim($str,",");
		return $str;
	}
	
	
	protected $words = [ [ "","یک","دو", "سه", "چهار", "پنج", "شش", "هفت", "هشت", "نه" ], [ "ده", "یازده", "دوازده", "سیزده", "چهارده", "پانزده", "شانزده", "هفده", "هجده", "نوزده", "بیست" ], [ "", "", "بیست", "سی", "چهل", "پنجاه", "شصت", "هفتاد", "هشتاد", "نود" ], [ "", "یکصد", "دویست", "سیصد", "چهارصد", "پانصد", "ششصد", "هفتصد", "هشتصد", "نهصد" ], [ '', " هزار ", " میلیون ", " میلیارد ", " بیلیون ", " بیلیارد ", " تریلیون ", " تریلیارد ", " کوآدریلیون ", " کادریلیارد ", " کوینتیلیون ", " کوانتینیارد ", " سکستیلیون ", " سکستیلیارد ", " سپتیلیون ", " سپتیلیارد ", " اکتیلیون ", " اکتیلیارد ", " نانیلیون ", " نانیلیارد ", " دسیلیون " ] ];
	protected $splitter = " و ";
	
	public function numToWord($input) {
		//global $words, $splitter;
		$zero = "صفر";
		if ($input == 0) {
			return $zero;
		}
		if (strlen($input) > 66) {
			return "خارج از محدوده";
		}
		//Split to sections
		$splittedNumber = $this->prepareNumber($input);
		$result = [];
		$splitLength = count($splittedNumber);
		for ($i = 0; $i < $splitLength; $i++) {
			$sectionTitle = $this->words[4][$splitLength - ($i + 1)];
			$converted    = $this->threeNumbersToLetter($splittedNumber[$i]);
			if ($converted !== "") {
				array_push($result, $converted . $sectionTitle);
			}
		}
		return join($this->splitter, $result);
	}
	
	protected function prepareNumber($num) {
		if (gettype($num) == "integer" || gettype($num) == "double") {
			$num = (string) $num;
		}
		$length = strlen($num) % 3;
		if ($length == 1) {
			$num = "00" . $num;
		} else if ($length == 2) {
			$num = "0" . $num;
		}
		return str_split($num, 3);
	}
	
	protected function threeNumbersToLetter($num) {
		//global $words, $splitter;
		if ((int) preg_replace('/\D/', '', $num) == 0) {
			return "";
		}
		$parsedInt = (int) preg_replace('/\D/', '', $num);
		if ($parsedInt < 10) {
			return $this->words[0][$parsedInt];
		}
		if ($parsedInt <= 20) {
			return $this->words[1][$parsedInt - 10];
		}
		if ($parsedInt < 100) {
			$one = $parsedInt % 10;
			$ten = ($parsedInt - $one) / 10;
			if ($one > 0) {
				return $this->words[2][$ten] . $this->splitter . $this->words[0][$one];
			}
			return $this->words[2][$ten];
		}
		$one        = $parsedInt % 10;
		$hundreds   = ($parsedInt - $parsedInt % 100) / 100;
		$ten        = ($parsedInt - (($hundreds * 100) + $one)) / 10;
		$out        = [$this->words[3][$hundreds]];
		$secondPart = (( $ten * 10 ) + $one);
		if ($secondPart > 0) {
			if ($secondPart < 10) {
				array_push($out, $this->words[0][$secondPart]);
			} else if ($secondPart <= 20) {
				array_push($out, $this->words[1][$secondPart - 10]);
			} else {
				array_push($out, $this->words[2][$ten]);
				if ($one > 0) {
					array_push($out, $this->words[0][$one]);
				}
			}
		}
		return join($this->splitter, $out);
	}
	
	/**
	* round mark like 14.36 => 14.50 
	*/
	public function roundMark($mark){
		if($mark=="" || !is_numeric($mark))
			return false;
		
		$mark=round($mark,2);
		
		if(strstr($mark,".",false)=="")
		    return $mark;
			
		$decimal="0".(strstr($mark,".",false));
		$int=(strstr($mark,".",true));

		if($decimal==0 || $decimal==0.25 || $decimal==0.50 || $decimal==0.75)
		{
			 //nothing
		}
		elseif($decimal<0.25)
		{
			 $decimal=0.25;
		}
		elseif($decimal<0.50)
		{
			 $decimal=0.50;
		}
		elseif($decimal<0.75)
		{
			 $decimal=0.75;
		}
		elseif($decimal<=0.99)
		{
			 $decimal=1;
		}
		
		$mark=$int+$decimal;
		
		return $mark;
	}
	
	/*
	 *generateRandomString
	 */
	function randomString($length = 5,$type=1)
	{
		if($type==1)
			$characters = 'abcdefghijklmnopqrstuvwxyz';
		elseif($type==2)
			$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		elseif($type==3)
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		elseif($type==4)
			$characters = '0123456789abcdefghijklmnopqrstuvwxyz?!@#%&(){}[]*-+ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			 $randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	/**
   *pagination
   */
    function pagination($url,$showId,$countRow,$pageRow,$pageNum){
        if($url=="")
            exit("Script error in pagination: url not set");
        if($showId=="")
            exit("Script error in pagination: showId not set");
        if($countRow=="")
            exit("Script error in pagination: countRow not set");
        if($pageRow=="")
            exit("Script error in pagination: pageRow not set");
        if($pageNum=="")
            exit("Script error in pagination: pageNum not set");
        
        $lastPage=ceil($countRow/$pageRow);
        if($pageNum<1)
            $pageNum=1;
        elseif($pageNum>$lastPage)
            $pageNum=$lastPage;
          
          $paginationCtrl='';
          if($lastPage!=1){
            if($pageNum>1){
              $previousPage=$pageNum-1;
              $paginationCtrl.='
              <li class="page-item">
                <a class="page-link btnPageLink" data-pageNum="'.$previousPage.'" href="#" aria-label="Previous" >
                  <span aria-hidden="true">&laquo;</span>
                  <span class="sr-only">Previous</span>
                </a>
              </li>';
              
              for($i=$pageNum-3;$i<$pageNum;$i++){
                if($i>0)
                  $paginationCtrl.='<li class="page-item" ><a class="page-link btnPageLink" data-pageNum="'.$i.'" href="#">'.$i.'</a></li>';
              }
            }
            
            $paginationCtrl.='<li class="page-item active" ><a class="page-link " href="#">'.$pageNum.'</a></li>';
            
            for($i=$pageNum+1;$i<=$lastPage;$i++){
                $paginationCtrl.='<li class="page-item" ><a class="page-link btnPageLink" data-pageNum="'.$i.'" href="#">'.$i.'</a></li>';
                if($i>$pageNum+3)
                  break;
            }
              
            if($pageNum!=$lastPage){
              $nextPage=$pageNum+1;
              $paginationCtrl.='
              <li class="page-item">
                <a class="page-link btnPageLink" data-pageNum="'.$nextPage.'" href="#" aria-label="Next" >
                  <span aria-hidden="true">&raquo;</span>
                  <span class="sr-only">Next</span>
                </a>
              </li>';
            }
            
          }
          
        
        $paginationCtrl='
            <nav aria-label="Page navigation example" class="mt-2" >
                <ul class="pagination  pagination">'.$paginationCtrl.
                '</ul>
            </nav>';
        
        $script="\r\n\t\t\t".'<script>
                    $(".btnPageLink").click(function(){
                      var pageNum=$(this).attr("data-pageNum");
                      if(window.hasOwnProperty("searchData")){
                      
                         $.post("'.$url.'",{pageNum:pageNum,searchData:searchData},function(data){
                            $("#'.$showId.'").html(data);
                        });                        
                      }else{
                        $.post("'.$url.'",{pageNum:pageNum},function(data){
                            $("#'.$showId.'").html(data);
                        });
                      }

                    });
            </script>'."\n\r";
                  
        if($countRow>$pageRow)
            echo $paginationCtrl.$script;
                  
    }
	
}
