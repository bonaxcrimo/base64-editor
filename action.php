<?php
    include("function.php");
    $base = new base64();
    $aksi = $_GET['aksi'];
    if($aksi=='decode'){
        if(!empty($_FILES)){
            if($_FILES['openFile']['error']!=4){
                 $data = file_get_contents($_FILES["openFile"]["tmp_name"]);
                 $hasil['encode'] = $data;
                 $hasil['aksi'] = $_POST;
                 $decode= $base->decode($data);
                 $hasil['decode'] = $decode;
                 $handle = $_FILES['openFile'];
                 $hasil['path'] = $handle;
                 echo json_encode($hasil);
                 exit();
            }
        }
    }else if($aksi=='decodev2'){
        $data = $_POST;
        $err= '';
        $result = '';
        $result2='';
        $isDecode = false;
        if(isset($data['nilai'])){
            $path =$data['nilai'];
            if (file_exists($path)) {
                $content = file_get_contents($path);
                $isDecode = strstr( $content, 'eval' )==false?false:true;
                $result = $content;
                if($isDecode){
                    $result = $base->decode($content);
                }
            }else{
                $err = 'File tidak ditemukan';
            }
        }else{
            $err = 'File tidak ditemukan';
        }
        $json = array(
            'err'=>$err,
            'result'=>$result,
            'isDecode'=>$isDecode,
            'result2'=>$result2
        );
        echo json_encode($json);
    }else if($aksi=="save"){
        $data =$_POST;
        $content = $data['content'];
        $checked = $data['checked'];
        $isDecode = $data['isDecode'];
        $lokasi = $data['lokasi'];
        $err='';
        $result='';
        if(trim($content)!='' && trim($isDecode)!='' && trim($lokasi)!='' && trim($checked)!=''){
            $content = $isDecode=='true'?$base->encode($content):$content;
            $hasil = $content;
            if($isDecode!=$checked){
                $hasil= $isDecode=='true'?$base->decode($content):$base->encode($content);
            }
            if (!empty($content)){
              if (@file_put_contents($lokasi,htmlspecialchars_decode($hasil))){
                $result = 'Berhasil menyimpan file';
              }else{
                $err='Gagal menyimpan file coba lagi';
              }
            }
        }else{
            $err = 'Terjadi kesalahan coba lagi';
        }
         $json = array(
            'err'=>$err,
            'result'=>$result
        );
         echo json_encode($json);
    }
?>