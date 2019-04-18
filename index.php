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
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <style>.CodeMirror {border-top: 1px solid black; border-bottom: 1px solid black;}
/*.bmd-modalButton {
  display: block;
  margin: 15px auto;
  padding: 5px 15px;
}*/

.close-button {
  overflow: hidden;
}

.bmd-modalContent {
  box-shadow: none;
  background-color: transparent;
  border: 0;
}

.bmd-modalContent .close {
  font-size: 30px;
  line-height: 30px;
  padding: 7px 4px 7px 13px;
  text-shadow: none;
  opacity: .7;
  color:#fff;
}

.bmd-modalContent .close span {
  display: block;
}

.bmd-modalContent .close:hover,
.bmd-modalContent .close:focus {
  opacity: 1;
  outline: none;
}

.bmd-modalContent iframe {
  display: block;
  margin: 0 auto;
}
    </style>
    </head>
    <body>
      <div class="container-fluid" style="margin-top: 10px;">
          <input type="hidden" value="" id="lokasiFile">
          <input type="hidden" value="" id="isDecodeFile">
          <div class="row">
            <div class="col-md-8">
             <button type="button" class="bmd-modalButton btn btn-default" data-toggle="modal" data-bmdSrc="http://web/seditor/fm.php" data-bmdWidth="640" data-bmdHeight="480" data-target="#myModal"  data-bmdVideoFullscreen="true">Browse File</button>
             <button type="button" class="btn btn-default"  id="btnClear">Clear</button>
             <button type="button" class="btn btn-warning " id="btnSave" disabled="">Save</button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <p>
                <span id="lokasi"></span>
                <br>
                <span id="isDecode"></span>
              </p>
            </div>
          </div>
      </div>

         <div class="modal fade" id="myModal">
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
        <?php
            // include("function.php");
            // $base = new base64();
            // if(!empty($_FILES)){
            //     if($_FILES['openFile']['error']!=4){
            //         $data = file_get_contents($_FILES["openFile"]["tmp_name"]);
            //         // file_put_contents("baru.php",$data);
            //         echo "<textarea id='code' name='code'>";
            //         echo $data;
            //         echo "</textarea>";
            //         $encode= $base->decode($data);
            //         echo "<textarea id='codehasil' name='codehasil'>";
            //         echo $encode;
            //         echo "</textarea>";
            //     }
            // }
        ?>
        <!-- <form  enctype="multipart/form-data" method="post" id="fm">
            <input type="file" name="openFile" id="openFile">
            <button type="submit" id="decode">Decode</button>
        </form> -->
        <h3>Code</h3>
        <textarea id="code" name="code"></textarea>
        <h3>Result</h3>
        <textarea id="codehasil" name="codehasil"></textarea>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script>
            (function($) {
              $("#btnClear").click(function(){

                 $("#btnSave").attr('disabled','');
                $("#isDecode").html("");
                $("#lokasi").html("");
                $("#lokasiFile").val("");
                 editor.getDoc().setValue("");
                 editor2.getDoc().setValue("");
                 $("#isDecodeFile").val("");
                 editor2.setOption("readOnly", false)
              });
              $("#btnSave").click(function(){
                 var lokasiFile = $("#lokasiFile").val();
                 var decodeFile =$("#isDecodeFile").val();
                 if($("#lokasiFile").val().trim()!=""){
                    var jwb = confirm('Anda Yakin ingin menyimpan file ?');
                    if(jwb==1){
                      var values = {
                          'lokasi': lokasiFile,
                          'isDecode' :decodeFile,
                          'content': editor.getValue(),
                          'content2':editor2.getValue()
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
                              $("#btnClear").trigger('click');
                              alert(data.result);
                            }
                              // console.log(data.path);
                              // editor.getDoc().setValue(data.encode);
                              // editor2.getDoc().setValue(data.decode);
                          }
                      });
                    }
                    //end if jwb

                 }else{
                  alert("Terjadi kesalahan lokasi file kosong.Pilih lagi file");
                 }
              });
              $.fn.bmdIframe = function( options ) {
                  var self = this;
                  var settings = $.extend({
                      classBtn: '.bmd-modalButton',
                      defaultW: 640,
                      defaultH: 360
                  }, options );

                  $(settings.classBtn).on('click', function(e) {
                    var allowFullscreen = $(this).attr('data-bmdVideoFullscreen') || false;

                    var dataVideo = {
                      'src': $(this).attr('data-bmdSrc'),
                      'height': $(this).attr('data-bmdHeight') || settings.defaultH,
                      'width': $(this).attr('data-bmdWidth') || settings.defaultW
                    };

                    if ( allowFullscreen ) dataVideo.allowfullscreen = "";

                    // stampiamo i nostri dati nell'iframe
                    $(self).find("iframe").attr(dataVideo);
                    $('iframe').on('load', function() {
                      $('#frame-fm').contents().find('.data-link-full').each(function(index,element){
                        $(element).click(function(){
                          var link  = $(this).data('link');
                          $("#myModal .close").click();
                            var values = {
                                'nilai': link
                            };
                          $.ajax({
                              url: "action.php?aksi=decodev2",
                              type: 'POST',
                              data:values,
                              success: function (data) {
                                  var data = JSON.parse(data);
                                  if(data.err==''){
                                    editor.getDoc().setValue(data.result);
                                    editor2.getDoc().setValue(data.result2);
                                    $("#isDecode").html("isDecode : <b>"+data.isDecode +"</b>" );
                                    $("#lokasi").html("lokasi : <b>"+link+"</b>");
                                    $("#isDecodeFile").val(data.isDecode);
                                    if(data.isDecode==false){
                                      editor2.setOption("readOnly", true)
                                    }else{
                                      editor2.setOption("readOnly", false)
                                    }
                                    $("#lokasiFile").val(link);
                                    $("#btnSave").removeAttr('disabled');
                                  }else{
                                    alert(data.err);
                                  }
                                  return true;
                                  // console.log(data.path);
                                  // editor.getDoc().setValue(data.encode);
                                  // editor2.getDoc().setValue(data.decode);
                              }
                          });
                        });
                        //end click element
                      });
                    });
                    //  $('#frame-fm').contents().find('#data-link-full').click(function(){
                    //   console.log("masuk");
                    //   // var link = $(this).data('link');
                    //   // console.log(link);
                    //   // $("#myModal").modal('hide');
                    // });

                    // console.log(dataLink);
                  });

                  // se si chiude la modale resettiamo i dati dell'iframe per impedire ad un video di continuare a riprodursi anche quando la modale è chiusa
                  this.on('hidden.bs.modal', function(){
                    $(this).find('iframe').html("").attr("src", "");
                  });

                  return this;
              };

          })(jQuery);




          jQuery(document).ready(function(){
            jQuery("#myModal").bmdIframe();
          });
          var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            lineNumbers: true,
            matchBrackets: true,
            lineWrapping: true,
            mode: "application/x-httpd-php",
            indentUnit: 4,
            indentWithTabs: true,
            readOnly: true
          });
          var editor2 = CodeMirror.fromTextArea(document.getElementById("codehasil"), {
            lineNumbers: true,
            matchBrackets: true,
            lineWrapping: true,
            mode: "application/x-httpd-php",
            indentUnit: 4,
            indentWithTabs: true
          });
          $("#openFile").change(function() {
            var path =$("#openFile").val()
            console.log(path);
        });
          $("form#fm").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "action.php?aksi=decode",
                type: 'POST',
                data: formData,
                success: function (data) {
                    var data = JSON.parse(data);
                    console.log(data.path);
                    editor.getDoc().setValue(data.encode);
                    editor2.getDoc().setValue(data.decode);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

        </script>
    </body>
</html>

