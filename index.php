<!DOCTYPE html>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Live input record and playback</title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="recorder.js"></script>
  <style type='text/css'>
    ul { list-style: none; }
    li { margin: 1em; }
    audio { display: block; }
  </style>
</head>
<body>

  <h1>Demo</h1>

  <div style="display:none;">
    <label>monitorGain</label>
    <input id="monitorGain" type="number" value="0" />
  </div>

  <div style="display:none;">
    <label>bitDepth</label>
    <select id="bitDepth">
      <option value="8">8</option> 
      <option value="16" selected>16</option>
      <option value="24">24</option>
      <option value="32">32</option>
    </select>
  </div>

  <div style="display:none;">
    <label>numberOfChannels</label>
    <input id="numberOfChannels" type="number" value="1" />
  </div>

  <div style="display:none;">
    <label>recordOpus</label>
    <input type="checkbox" id="recordOpus" />
  </div>

  <div style="display:none;"> <!--style="display:none;"-->
    <label>sampleRate</label>
    <input id="sampleRate" type="number" value="16000" />
  </div>

  <div style="display:none;">
    <label>bitRate</label>
    <input id="bitRate" type="number" value="64000" />
  </div>

  <h2>Commands</h2>
  <button id="init">init</button>
  <button id="start" disabled>start</button>
  <button id="pause" disabled>pause</button>
  <button id="resume" disabled>resume</button>
  <button id="stopButton" disabled>stop</button>
  
  <h2>Recordings</h2>
  <ul id="recordingslist"></ul>

  <div id="urlContainer"></div>
  <div id="formContainer"></div>

  <h2>Log</h2>
  <pre id="log"></pre>

  <script>
    var recorder;
    start.addEventListener( "click", function(){ recorder.start(); });
    pause.addEventListener( "click", function(){ recorder.pause(); });
    resume.addEventListener( "click", function(){ recorder.resume(); });
    stopButton.addEventListener( "click", function(){ recorder.stop(); });
    init.addEventListener( "click", function(){
      recorder = new Recorder({
        monitorGain: monitorGain.value,
        numberOfChannels: numberOfChannels.value,
        bitDepth: bitDepth.options[ bitDepth.selectedIndex ].value,
        recordOpus: recordOpus.checked ? {bitRate: bitRate.value} : false,
        sampleRate: sampleRate.value
      });
      recorder.addEventListener( "start", function(e){
        screenLogger('Recorder is started');
        init.disabled = start.disabled = resume.disabled = true;
        pause.disabled = stopButton.disabled = false;
      });
      recorder.addEventListener( "stop", function(e){
        screenLogger('Recorder is stopped');
        init.disabled = start.disabled = false;
        pause.disabled = resume.disabled = stopButton.disabled = true;
      });
      recorder.addEventListener( "pause", function(e){
        screenLogger('Recorder is paused');
        init.disabled = pause.disabled = start.disabled = true;
        resume.disabled = stopButton.disabled = false;
      });
      recorder.addEventListener( "resume", function(e){
        screenLogger('Recorder is resuming');
        init.disabled = start.disabled = resume.disabled = true;
        pause.disabled = stopButton.disabled = false;
      });
      recorder.addEventListener( "duration", function(e){
        screenLogger('Recorded ' + e.detail + ' seconds');
      });
      recorder.addEventListener( "streamError", function(e){
        screenLogger('Error encountered: ' + e.error.name );
      });
      recorder.addEventListener( "streamReady", function(e){
        screenLogger('Audio stream is ready. Recording can begin.');
        init.disabled = pause.disabled = resume.disabled = stopButton.disabled = true;
        start.disabled = false;
      });
      recorder.addEventListener("dataAvailable", function(e){
        var fileName = new Date().toISOString() + "." + e.detail.type.split("/")[1];
        var url = URL.createObjectURL( e.detail );
        var audio = document.createElement('audio');
        audio.controls = true;
        audio.src = url;
        audio.id = 'audio';

        var link = document.createElement('a');
        link.href = url;
        link.download = fileName;
        link.innerHTML = link.download;

        var li = document.createElement('li');

        li.appendChild(link);
        li.appendChild(audio);
        recordingslist.appendChild(li);

        console.log(audio.src);

        uploadAudio(dataURItoBlob(audio.src));

      });
    });

    function screenLogger(text, data) {
      log.innerHTML += "\n" + text + " " + (data || '');
    }
  </script>

  <script type="text/javascript">

    function uploadAudio( blob ) {
      var reader = new FileReader();
      reader.onload = function(event){
        var fd = {};
        fd["fname"] = "test.wav";
        fd["data"] = event.target.result;
        $.ajax({
          type: 'POST',
          url: 'upload.php',
          data: fd,
          dataType: 'text'
        }).done(function(data) {
            console.log(data);
        });
      };
      reader.readAsDataURL(blob);
    }

    function dataURItoBlob(dataURL) {
      var BASE64_MARKER = ';base64,';
      if (dataURL.indexOf(BASE64_MARKER) == -1) {
        var parts = dataURL.split(',');
        var contentType = parts[0].split(':')[1];
        var raw = decodeURIComponent(parts[1]);

        return new Blob([raw], {type: contentType});
      }

      var parts = dataURL.split(BASE64_MARKER);
      var contentType = parts[0].split(':')[1];
      var raw = window.atob(parts[1]);
      var rawLength = raw.length;

      var uInt8Array = new Uint8Array(rawLength);

      for (var i = 0; i < rawLength; ++i) {
        uInt8Array[i] = raw.charCodeAt(i);
      }

      return new Blob([uInt8Array], {type: contentType});
    }

  </script>

  </body>
</html>