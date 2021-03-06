(function($){
// Get the messages div.
   var formMessages = $('#form-messages');

  $(document).ready(function (e){
    $("#ajax-request").on('submit',(function(e){
      e.preventDefault();
      $.ajax({
        url: "upload.php",
        type: "POST",
        data:  new FormData(this),
        contentType: false,
        cache: false,
        processData:false,
        success: function(data){
          //$("#form-messages").html(data);
        },
        error: function(){}
      })
      .done(function(data) {
        console.log(data);
        goAzure(data);
      });
    }));
  });

  function _(el) {
    return document.getElementById(el);
  }

  function uploadFile() {
    var file = _("url").files[0];
    // alert(file.name+" | "+file.size+" | "+file.type);
    var formdata = new FormData();
    formdata.append("file", file);
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.addEventListener("error", errorHandler, false);
    ajax.addEventListener("abort", abortHandler, false);
    ajax.addEventListener("loadend", goAzure, false);
    ajax.open("POST", "upload.php"); // http://www.developphp.com/video/JavaScript/File-Upload-Progress-Bar-Meter-Tutorial-Ajax-PHP
    //use file_upload_parser.php from above url
    ajax.send(formdata);
  }

  function goAzure(event){
    var urlText = event.target.responseText;
    var serialized = 'data=' + encodeURIComponent(urlText);
    console.log(serialized);
    $.ajax({
        type: 'POST',
        url: "ia.php",
        data: serialized
    })
    .done(function(response) {
        // Make sure that the formMessages div has the 'success' class.
        // $(formMessages).removeClass('error');
        // $(formMessages).addClass('success');

        // Set the message text.
        var toTalk = jQuery.parseJSON(response).description.captions[0].text;
        responsiveVoice.speak(toTalk, "Spanish Female");

        $(formMessages).text(toTalk);
        // Clear the form.
        // $('#name').val('');
        // $('#email').val('');
        // $('#message').val('');
    })
  };

  function progressHandler(event) {
    _("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
    var percent = (event.loaded / event.total) * 100;
    _("progressBar").value = Math.round(percent);
    _("status").innerHTML = Math.round(percent) + "% uploaded... please wait";
  }

  function completeHandler(event) {
    _("status").innerHTML = event.target.responseText;
    _("progressBar").value = 0; //wil clear progress bar after successful upload
  }

  function errorHandler(event) {
    _("status").innerHTML = "Upload Failed";
  }

  function abortHandler(event) {
    _("status").innerHTML = "Upload Aborted";
  }

  })(jQuery);
