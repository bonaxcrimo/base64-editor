<?php
include("function.php");
session_start();
$ses = @$_SESSION['SESSIONEDITOR'];
$base = new base64();
$base->genereatePin();
if(!isset($_SESSION['SESSIONEDITOR']))
{
  $msg = '';
  if(!empty($_POST)){
    $data=file_get_contents(".temp");
    $data= explode("|",$data);
    if($data[0]==$_POST['fm_usr'] && $data[1]==$_POST['fm_pwd']){
       $_SESSION['SESSIONEDITOR'] = $data[0];
       $base->generate("","");
       if(!isset($_SESSION['last_activity'])){
          $_SESSION['last_activity'] = time();
        }
      header("Refresh:0");
    }else{
      $msg = ' <p class="message error">Username atau password salah</p>';
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex">
    <link rel="icon" href="Stnkjkt?img=favicon" type="image/png">
    <title>Seditor</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body.fm-login-page{background-color:#f7f9fb;font-size:14px}
        .fm-login-page .brand{width:121px;overflow:hidden;margin:0 auto;margin:40px auto;margin-bottom:0;position:relative;z-index:1}
        .fm-login-page .brand img{width:100%}
        .fm-login-page .card-wrapper{width:360px}
        .fm-login-page .card{border-color:transparent;box-shadow:0 4px 8px rgba(0,0,0,.05)}
        .fm-login-page .card-title{margin-bottom:1.5rem;font-size:24px;font-weight:300;letter-spacing:-.5px}
        .fm-login-page .form-control{border-width:2.3px}
        .fm-login-page .form-group label{width:100%}
        .fm-login-page .btn.btn-block{padding:12px 10px}
        .fm-login-page .footer{margin:40px 0;color:#888;text-align:center}
        @media screen and (max-width: 425px) {
            .fm-login-page .card-wrapper{width:90%;margin:0 auto}
        }
        @media screen and (max-width: 320px) {
            .fm-login-page .card.fat{padding:0}
            .fm-login-page .card.fat .card-body{padding:15px}
        }
        .message{padding:4px 7px;border:1px solid #ddd;background-color:#fff}
        .message.ok{border-color:green;color:green}
        .message.error{border-color:red;color:red}
        .message.alert{border-color:orange;color:orange}
    </style>
</head>
<body class="fm-login-page">
<div id="wrapper" class="container-fluid">
      <?= $msg ?>
            <section class="h-100">
            <div class="container h-100">
                <div class="row justify-content-md-center h-100">
                    <div class="card-wrapper">
                        <div class="text-center">
                            <h1 >S-Editor</h1>
                        </div>
                        <div class="card fat">
                            <div class="card-body">
                                <form class="form-signin" action="" method="post" autocomplete="off">
                                    <div class="form-group">
                                        <label for="fm_usr">Key</label>
                                        <input type="text" class="form-control" id="fm_usr" name="fm_usr" required="" readonly="">
                                    </div>

                                    <div class="form-group">
                                        <label for="fm_pwd">Pin</label>
                                        <input type="number" class="form-control" id="fm_pwd" name="fm_pwd" required autofocus="">
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block" role="button">
                                           Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
  $(document).ready(function(){
    $.ajax({
      url:"action.php?aksi=getkey",
      success:function(result){
        result = JSON.parse(result);
        $("#fm_usr").val(result.user);
      }})
  })
</script>
</body>
</html>

<?php
}else{
?>
<html>
    <head>
        <title>editor</title>
        <link rel="stylesheet" href="lib/codemirror.css">
        <script src="lib/codemirror.js"></script>
        <script src="addon/edit/matchbrackets.js"></script>
        <script src="mode/htmlmixed/htmlmixed.js"></script>
        <script src="mode/xml/xml.js"></script>
        <script src="mode/javascript/javascript.js"></script>
        <script src="mode/css/css.js"></script>
        <script src="mode/clike/clike.js"></script>
        <script src="mode/php/php.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="styles/index.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    </head>
    <body>
      <div class="container-fluid" style="margin-top: 10px;">
          <input type="hidden" value="" id="lokasiFile">
          <input type="hidden" value="" id="isDecodeFile">
          <div class="row">

            <div class="col-md-8">
              <p>
                <span id="lokasi"></span>
              </p>
            </div>
            <div class="col-md-4 text-right">
             <button type="button" class="bmd-modalButton btn btn-success" data-toggle="modal"
             data-bmdSrc="fm.php" id="btnModal" data-bmdWidth="750" data-bmdHeight="540" data-target="#myModal"  data-bmdVideoFullscreen="true">Browse File</button>

             <button type="button" class="btn btn-primary"  id="btnClear">Clear</button>
             <span id="spanCheck"><input type="checkbox"  id="checkCode">Encoded</span>
             <button type="button" class="btn btn-warning " id="btnSave" disabled="">Save</button>
             <button type="button" id="logout" class="btn btn-danger">Logout</button>
            </div>
          </div>

      </div>

        <div class="modal fade modal-frame" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content bmd-modalContent">
                    <div class="modal-body">
                      <div class="close-button">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      </div>
                      <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" id="frame-fm" frameborder="0"></iframe>
                      </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <h3 style="margin:0" id="title">Code</h3>
        <textarea id="code" name="code"></textarea>
        <!-- <h3>Result</h3> -->
        <!-- <textarea id="codehasil" name="codehasil"></textarea> -->
        <script>
          setInterval(function(){
              var time = "<?= SESSION_TIME ?>";
              $.ajax({
                  url:"action.php?aksi=lastactivity",
                  success:function(result){
                    console.log(result);
                      if(result==(time-160)){
                        var ya = confirm("Waktu anda tinggal 5 menit apakah ingin melanjutkan?");
                        if(ya){
                          $.ajax({
                          url:"action.php?aksi=tambah",
                          success:function(result){
                          }});
                        }
                      }
                      if(parseInt(result)>=parseInt(time)){
                         $.ajax({
                          url:"action.php?aksi=activity",
                          success:function(result){
                            location.reload();
                          }});
                      }
                      // if(result>parseInt(time)){
                      //     location.reload();
                      // }
                  }
              })
          },1000);
          </script>
        <script>
          let editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            lineNumbers: true,
            matchBrackets: true,
            lineWrapping: true,
            mode: "application/x-httpd-php",
            indentUnit: 4,
            indentWithTabs: true,
            readOnly:true
          });
          $('#myModal').on('hidden.bs.modal', function () {
              // do something…
              $.ajax({
              url:"action.php?aksi=lastlocation",
              success:function(result){
                $("#btnModal").attr('data-bmdSrc','fm.php?p=' + result)
              }});
          })
          var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
          if(isChrome){
           editor.setSize(null,$(window).height() - $("#title").offset().top-30);
          }else{
            editor.setSize(null,700);
            // editor.setSize(null,$(window).height() - $("#title").offset().top-30);
          }

          // let editor2 = CodeMirror.fromTextArea(document.getElementById("codehasil"), {
          //   lineNumbers: true,
          //   matchBrackets: true,
          //   lineWrapping: true,
          //   mode: "application/x-httpd-php",
          //   indentUnit: 4,
          //   indentWithTabs: true
          // });
          function ambilData(link){
            let values = {
                'nilai': link
            };
            $.ajax({
                url: "action.php?aksi=decodev2",
                type: 'POST',
                data:values,
                success: function (data) {
                    data = JSON.parse(data);
                    if(data.err==''){
                      editor.getDoc().setValue(data.result);
                      // editor2.getDoc().setValue(data.result2);
                      $("#lokasi").html(`File Location : <b>${link}</b> (<span class='text-danger'>Status ${data.isDecode==false?"Not Encoded":"Encoded"})</span>`);
                      $("#isDecodeFile").val(data.isDecode);
                      editor.setOption("readOnly", false)
                      let checkBoxes = $("#checkCode");
                      checkBoxes.prop("checked", data.isDecode);
                      $("#spanCheck").show();
                      // if(data.isDecode==false){
                      //   editor2.setOption("readOnly", true)
                      // }else{
                      //   editor2.setOption("readOnly", false)
                      // }
                      $("#lokasiFile").val(link);
                      $("#btnSave").removeAttr('disabled');
                    }else{
                      alert(data.err);
                    }
                    return true;
                }
            });
          }
          (function($) {
              //clear button

              $("#logout").click(function(){
                $.ajax({
                  url:"action.php?aksi=logout",
                  success:function(result){
                    location.reload();
                  }});
              });
              $("#spanCheck").hide();
              $("#btnClear").click(function(){
                $("#btnSave").attr('disabled','');
                $("#lokasi").html("");
                $("#lokasiFile").val("");
                $("#spanCheck").hide();
                editor.getDoc().setValue("");
                editor.setOption("readOnly", true)
                 // editor2.getDoc().setValue("");
                $("#isDecodeFile").val("");
                 // editor2.setOption("readOnly", false)
              });
              $("#btnSave").click(function(){
                 let lokasiFile = $("#lokasiFile").val();
                 let decodeFile =$("#isDecodeFile").val();
                 let checked = $("#checkCode:checked").val()==undefined?'false':'true';
                 if($("#lokasiFile").val().trim()!=""){
                    const status = `dan merubahnya menjadi ${checked=="false"?"Decoded":"Encoded"}`;
                    let jwb = confirm(`Anda Yakin ingin menyimpan file ${checked!=decodeFile?status:""}?`);
                    if(jwb==1){
                      let values = {
                          'lokasi': lokasiFile,
                          'isDecode' :decodeFile,
                          'content': editor.getValue(),
                          'checked':checked
                      };
                      $.ajax({
                          url: "action.php?aksi=save",
                          type: 'POST',
                          data:values,
                          success: function (data) {
                            data = JSON.parse(data);
                            if(data.err.trim()!=''){
                              alert(data.err);
                            }else{
                              ambilData(lokasiFile);
                              // $("#btnClear").trigger('click');
                              alert(data.result);
                            }
                          }
                      });
                    }
                    //end if jwb
                 }else{
                  alert("Terjadi kesalahan lokasi file kosong.Pilih lagi file");
                 }
              });
              $.fn.bmdIframe = function( options ) {
                  let self = this;
                  let settings = $.extend({
                      classBtn: '.bmd-modalButton',
                      defaultW: 800,
                      defaultH: 540
                  }, options );
                  $(settings.classBtn).on('click', function(e) {
                    let allowFullscreen = $(this).attr('data-bmdVideoFullscreen') || false;
                    let dataVideo = {
                      'src': $(this).attr('data-bmdSrc'),
                      'height': $(this).attr('data-bmdHeight') || settings.defaultH,
                      'width': $(this).attr('data-bmdWidth') || settings.defaultW
                    };

                    if ( allowFullscreen ) dataVideo.allowfullscreen = "";
                    $(self).find("iframe").attr(dataVideo);
                    $('iframe').on('load', function() {
                      $('#frame-fm').contents().find('.data-link-full').each(function(index,element){
                        $(element).click(function(){
                          let link  = $(this).data('link');
                          console.log(link);
                          $("#myModal .close").click();
                          ambilData(link);
                        });
                        //end click element
                      });
                    });
                  });
                  //remove src from iframe when modal close
                  this.on('hidden.bs.modal', function(){
                    $(this).find('iframe').html("").attr("src", "");
                  });

                  return this;
              };

            jQuery("#myModal").bmdIframe();
          })(jQuery);

        </script>
    </body>
</html>
<?php } ?>