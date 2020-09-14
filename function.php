<?php
defined('SESSION_TIME') OR define('SESSION_TIME', '1800');
Class base64{
    function decode($content){
        $content=preg_replace('/^\<\?(php)*/','',$content);
        $content=preg_replace('/\?\>$/','',$content);
        $content=str_replace(['$qb33ffa3="\x62\x61\x73\x65\66\x34\x5f\x64\x65\143\x6f\144\145"','$qb33ffa3','eval','base64_decode','(',')','"',';'],'',$content);
        $data= base64_decode($content);
        $print="<?php $data ?>";
        return $print;
    }
    function encode($content){
        $content=preg_replace('/^\<\?(php)*/','',$content);
        $content=preg_replace('/\?\>$/','',$content);
        $data = base64_encode($content);
        // $print="<?php eval(base64_decode("$data"));\n
        $print='<?php $qb33ffa3="\x62\x61\x73\x65\66\x34\x5f\x64\x65\143\x6f\144\145";@eval($qb33ffa3("'.$data.'")); ?>';
        return $print;
    }
    function genereatePin(){
         $random = rand(10,99)."";
         $user= Date("y").(Date("md")).$random;
         $data=file_get_contents(".temp");
         $data= explode("|",$data);
         if(trim(@$data[0])==""){
              $fp = fopen('.temp', 'w');
              fwrite($fp,$user."|");
              fclose($fp);
         }
    }
    function save($nama,$code){
        if (!empty($code)) if (@file_put_contents($nama,htmlspecialchars_decode($code))) return true;
        return false;
    }
    function generate($user,$pass){
      // $data=file_get_contents(".temp");
      // $data= explode("|",$data);
      $fp = fopen('.temp', 'w');
      fwrite($fp,@$user."|".$pass);
      fclose($fp);
    }
}
?>