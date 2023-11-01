<?php
class Encrypt{
    private $text="";
    function __construct($name){
        $this->text=$name;
    }
    private function MD5($text){
        $i=0;
        $len=strlen($text);
        $cipher="";
        for($i=0;$i<$len;$i++){
            $char=$text[$i];
            $cipher.=md5($char);
        }
        return $cipher;
        
    }
    private function SHA_256($text){
        $i=0;
        $len=strlen($text);
        $cipher="";
        for($i=0;$i<$len;$i++){
            $char=$text[$i];
            $cipher.=crypt($char,'$5$');
        }
        return $cipher;
    }
    private function gost_crypto($text){
        $i=0;
        $len=strlen($text);
        $cipher="";
        for($i=0;$i<$len;$i++){
            $char=$text[$i];
            $cipher.=hash('gost-crypto',$char);
        }
        return $cipher;

    }

    private function AES256($data,$key="Cipher"){
        $iv = openssl_random_pseudo_bytes(16);
     $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
     return base64_encode($iv . $encrypted);
     }

     public function decryptAES256($data) {
        $key="Cipher";
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        }

    public function Encrypt_alg(){
        $text=$this->MD5($this->text);
        $right=substr($text,0,strlen($text)/2);
        $left=substr($text,strlen($text)/2);
        $i=0;
        $len=strlen($right);
        $cipher_left="";
        for($i=0;$i<$len;$i++){
            $char=$left[$i];
            $cipher_left.=$this->SHA_256($char);
        }
        $i=0;
        $len=strlen($left);
        $cipher_right="";
        for($i=0;$i<$len;$i++){
            $char=$right[$i];
            $cipher_right.=$this->gost_crypto($char);
        }
       $swap=$cipher_right.$cipher_left;
       $swap=strtolower($swap) ^ strtoupper($swap) ^ $swap;


       //To convert each char in md5 results number of bits in 10Lakhs


     //  $i=0;
     //  $cipher="";
    //   for($i=0;$i<strlen($swap);$i++){
    //   $char=$swap[$i];
    //  $cipher.=$this->MD5($char);
  //    }
      
    
       $cipherNew="";
       $array=str_split($swap);
       $hashset = array();
       foreach ($array as $value){
               if (!array_key_exists($value, $hashset)){
                    $cipherNew.=$value;
                    $hashset[$value] = true;
    }
}


echo $cipherNew;
//$cipherNew=$this->AES256($cipherNew);
//return $cipherNew;

    }
}

$email1=new Encrypt($_POST['text']);
$email=$email1->Encrypt_alg();

?>