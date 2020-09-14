<?php
function simple_hash($str, $size=6, $characters='0123456789') {
    $hash_array = array();
    $hash = '';
    for($i=0;$i<$size;$i++){
        $hash_array[$i]=0;
    }
    for($s=0;$s<strlen($str);$s++){
        for($i=0;$i<$size;$i++){
            $hash_array[$i]=($hash_array[$i]+ord($str[$s])+$i+$s+$size)%strlen($characters);
        }
    }
    for($i=0;$i<$size;$i++){
        $hash .= $characters[$hash_array[$i]];
    }
    return $hash;
}
function generateRandomString($length =6) {
    $characters = 'secretkey';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function generate(){
  $fp = fopen('.temp', 'w');
  fwrite($fp, 'Cats chase mice');
  fclose($fp);
}
// generate();
function checkPassword($pass){
  $pass =substr_replace($pass, '', 2, 2);
  $pass =substr_replace($pass, '',strlen($pass)-2, 2);
  // $pass =substr_replace($pass, '', 0, 1);
  echo $pass;
}
function randSecret($word){
  $rand=(ord($word[rand(0,strlen($word)-1)])-97);
  $rand =  sprintf('%02d', $rand);
  return $rand;
}
// echo randSecret()."=".randSecret();
$random = rand(100,999)."";
$data = "975319";
$date= Date("d").randSecret(generateRandomString()).Date("H").randSecret(generateRandomString());
$key = simple_hash($date).Date("i");
echo simple_hash($key)."=".$data;
// $a=file_get_contents(".temp");
// echo $a;
// echo $key."<br>";
// echo checkPassword($key);
?>