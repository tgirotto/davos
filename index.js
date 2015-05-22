Webcam.attach( '#my_camera' );

function take_snapshot() {
    console.log('taking picture');
    Webcam.snap( function(data_uri) {
        Webcam.upload( data_uri, 'uploadImage.php', function(code, text) {
            console.log('text: ', JSON.parse(text));
            //document.getElementById('imageDetectionContainer').innerHTML = text;
            wait();
        } );
    } );
}

function wait() {
  setTimeout(take_snapshot, 1000);
};

function uploadAudio( blob ) {
    var reader = new FileReader();
    reader.onload = function(event){
      var fd = {};
      fd["fname"] = "test.wav";
      fd["data"] = event.target.result;
      $.ajax({
        type: 'POST',
        url: 'uploadAudio.php',
        data: fd,
        dataType: 'text'
      }).done(function(data) {
          appendTranslation(data);
      });
    };
    reader.readAsDataURL(blob);
}

function appendTranslation(data) {
    document.getElementById('translationContainer').innerHTML = data;
}