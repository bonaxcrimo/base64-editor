<?php
Class base64{
    function decode($content){
        $content=preg_replace('/^\<\?(php)*/','',$content);
        $content=preg_replace('/\?\>$/','',$content);
        $content=str_replace(['eval','base64_decode','(',')','"',';'],'',$content);
        $data= base64_decode($content);
        $print="<?php $data ?>\n";
        return $print;
    }
    function encode($content){
        $content=preg_replace('/^\<\?(php)*/','',$content);
        $content=preg_replace('/\?\>$/','',$content);
        $data = base64_encode($content);
        $print="<?php eval(base64_decode(\"$data\")); ?>\n";
        return $print;
    }
    function save($nama,$code){
        if (!empty($code)) if (@file_put_contents($nama,htmlspecialchars_decode($code))) return true;
        return false;
    }
}
?>