<?php
class Encryption{
	
   
    protected $method = 'AES-256-CBC';// AES-256-CBC OR AES-256-ECB
    private $key ; 
	

    public function __construct() 
	{
        require_once "config.php";
        
        if(defined("EncryptionKey"))
            $this->key=EncryptionKey;
        
        if (!extension_loaded('mbstring'))
        {
            exit( 
                "<div style='direction:rtl;' >
                	<b style='color:red;' >
                	extensions  mbstring 
                	در هاست شما فعال نیست
                	</b>
                	<br/>
                		برای فعال سازی در هاست سی پنل ابتدا در صفحه  اصلی گزینه 
                        select php version 
                        را انتخاب کنید. 
                        سپس از قسمت  extensions  افزونه mbstring  فعال کنید.
                        <br/>
                        درصورتیکه  موفق به  فعال سازی  mbstring نشدید با شرکت ارائه دهنده  هاست خود تماس حاصل فرمایید.
                        <br/>
                        <img src='../../help/enable_mbstring.png' style='max-width:100%' />
                </div>"
                );
    	}
        
    }
    
	public function setKey($key)
    {
        $this->key=$key;
    }
    
    public  function safe_b64encode($string) {
	
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
 
	public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
    
    /**
     * Encrypts the data
     * 
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to FALSE to prevent base64-encoded 
     * @return string (raw binary)
     */
    public  function encode($message, $key="", $encode = true)
    {
        if($message=="")
            return $message;
        
        if($key=="")
        {
            $key=$this->key;
        }
        
        $nonceSize = openssl_cipher_iv_length($this->method);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        try{
			$ciphertext = @openssl_encrypt($message,$this->method,$key,OPENSSL_RAW_DATA,$nonce);
			$sendEncrypt=$nonce.$ciphertext;
			if(!$ciphertext)
			   exit('Unable To Encrypt Data, change method in Encryption');
        }catch(Exception $e){
            $encode=false;
            echo $sendEncrypt=$e->getMessage();
        }

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            $sendEncrypt=$this->safe_b64encode($sendEncrypt);
        }
        return $sendEncrypt;
    }

    /**
     * Decrypts the data
     * 
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - set to FALSE to prevent base64-decode
     * @return string
     */
    public  function decode($message, $key="", $encoded = true)
    {
         if($message=="")
            return $message;
        
        if($key=="")
        {
            $key=$this->key;
        }
        
        if ($encoded) {
         try{
            $message = $this->safe_b64decode($message, true);
            if ($message === false) {
                exit('Unable To Decrypt Data, change method in Encryption');
            }else{
                $nonceSize = openssl_cipher_iv_length($this->method);
                $nonce = mb_substr($message, 0, $nonceSize, '8bit');
                $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

                $plaintext = @openssl_decrypt($ciphertext,$this->method,$key,OPENSSL_RAW_DATA,$nonce);
            }
         }catch(Exception $e){
            $plaintext=$e->getMessage();
         }
        }

        return $plaintext;
    }
	
    public function encodeAdmin($id)
    {
        return $this->encode($id.substr($this->key,0,3));
    }
    
    
    public function decodeAdmin($admin)
    {
        return substr($this->decode($admin),0,-3);
    }
    
	public function makeArray($reciveString){
        $var=$this->decode($reciveString);
        $var=explode(",",$var);
        foreach($var as $value){
            $key=strstr($value,"=",true);
            $val=ltrim(strstr($value,"="),"=");
            $data[$key]=$val;
        }
        return $data;
    }
}
?>
