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
             <button type="button" class="bmd-modalButton btn btn-default" data-toggle="modal" data-bmdSrc="http://web/seditor/fm.php" data-bmdWidth="640" data-bmdHeight="480" data-target="#myModal"  data-bmdVideoFullscreen="true">Browse File</button>

             <button type="button" class="btn btn-default"  id="btnClear">Clear</button>
             <span id="spanCheck"><input type="checkbox"  id="checkCode">Encoded</span>
             <button type="button" class="btn btn-warning " id="btnSave" disabled="">Save</button>
            </div>
          </div>
          <p>
            <span id="lokasi"></span>
          </p>
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
        <h3>Code</h3>
        <textarea id="code" name="code"></textarea>
        <!-- <h3>Result</h3> -->
        <!-- <textarea id="codehasil" name="codehasil"></textarea> -->
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
          // let editor2 = CodeMirror.fromTextArea(document.getElementById("codehasil"), {
          //   lineNumbers: true,
          //   matchBrackets: true,
          //   lineWrapping: true,
          //   mode: "application/x-httpd-php",
          //   indentUnit: 4,
          //   indentWithTabs: true
          // });
          (function($) {
              //clear button
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
                              $("#btnClear").trigger('click');
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
                      defaultW: 640,
                      defaultH: 360
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
                          $("#myModal .close").click();
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
                                    $("#lokasi").html(`Lokasi : <b>${link}</b>(<span class='text-danger'>${data.isDecode==false?"Not Encoded":"Encoded"})</span>`);
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

