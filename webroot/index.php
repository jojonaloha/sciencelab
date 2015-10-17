<?php
/**
 * @file
 * Science Lab Login
 */

if (isset($_POST['name'])) {
  $name = $_POST['name'];
  `say -v Zarvox -o "audio/$name.mp4" "Welcome $name. Access granted."`;
}

?>
<html>
<head>
  <title>Enter the Science Lab</title>
  <style>
    * {
      box-sizing: border-box;
    }
    .overlay {
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: rgba(0,0,0, 0.5);
      display: none;
    }
    .loader {
      position: absolute;
      top: 10%;
      bottom: 10%;
      left: 10%;
      right: 10%;
      background-color: white;
      text-align: center;
      border: solid 1px black;
    }
    .spinner {
      background-image: url('loading.gif');
      background-repeat: no-repeat;
      height: 15px;
      width: 128px;
      margin: auto;
    }
    body {
      background-color: #e0e0e0;
      background-repeat: no-repeat;
      background-position: 90% 5%, 5% 95%;
      background-image: url('scientist-flamingo.png'), url('beaker.png');
    }
    .wrapper {
      margin: 10% auto;
      width: 650px;
      border: solid 1px black;
      padding: 5%;
      background-color: white;
      position: relative;
      min-height: 400px;
    }
    label {
      font-size: 1.2em;
      font-weight: bold;
    }
    input[type="text"] {
      width: 100%;
      font-size: 24pt;
      padding: 10px 25px;
    }
    .action {
      text-align: center;
    }
    input[type="submit"] {
      font-size: 18pt;
      border: solid 1px black;
      padding: 10px;
      background-color: greenyellow;
      margin: 10px auto;
    }

    #audioContainer {
      /*width: 500px;
      height: 70px;
      background: #000;
      padding: 5px;*/
      position: absolute;
      bottom: 0;
      left: 0;
      height: 250px;
      width: 100%;
      /*margin: 50px auto;*/
    }

    /*#audioContainer audio {
      width: 500px;
      background: #000;
      float: left;
    }*/

    #audioContainer canvas {
      /*width: 500px;*/
      /*height: 30px;*/
      width: 100%;
      height: 250px;
      background: #FFF;
    }
  </style>
  <script src="https://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>
  <script>
    jQuery(document).ready(function() {
      jQuery('.action input').click(function() {
        jQuery('.overlay').show();
      });
    });
    <?php if (!empty($name)) { ?>
      // Create an audio object
      var audio = new Audio();
      audio.src = 'audio/<?php echo $name; ?>.mp4';
      audio.controls = false;
      audio.loop = false;
      audio.autoplay = false;
      audio.onended = function() {
        // Go back to login after the audio finishes.
        window.location.href = window.location.pathname + window.location.search;
      }

      // Define variables for visualizer
      var canvas, ctx, source, context, analyser, fbc_array, bars, bar_x, bar_width, bar_height;
      window.addEventListener('load', initMp3Player, false);
      // Initialize MP3
      function initMp3Player() {
        //document.getElementById('audioContainer').appendChild(audio);
        context = new AudioContext();
        analyser = context.createAnalyser();
        canvas = document.getElementById('audioVisualizer');
        ctx = canvas.getContext('2d');

        source = context.createMediaElementSource(audio);
        source.connect(analyser);
        analyser.connect(context.destination);
        frameLooper();
        audio.play();
      }

      // Animate graphics in visualizer.
      function frameLooper() {
        window.requestAnimationFrame(frameLooper);
        fbc_array = new Uint8Array(analyser.frequencyBinCount);
        analyser.getByteFrequencyData(fbc_array);
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = 'greenyellow';
        bars = 50;
        for (var i = 0; i < bars; i++) {
          bar_x = i * 6;
          bar_width = 5;
          bar_height = -(fbc_array[i] / 1.5);
          ctx.fillRect(bar_x, canvas.height, bar_width, bar_height);
        }
      }
    <?php } ?>
  </script>
</head>

<body>
  <div class="wrapper">
    <h1>Welcome to Hannah's Science Lab</h1>
  <?php if (!empty($name)) { ?>
    <div id="audioContainer">
      <div id="audioPlayer"></div>
      <canvas id="audioVisualizer"></canvas>
    </div>
  <?php }
  else { ?>
    <div class="overlay">
      <div class="loader">
        <h2>Checking access...</h2>
        <div class="spinner"></div>
      </div>
    </div>
    <div class="main">
      <form action="index.php" method="POST">
        <div class="label"><label for="name">Enter your name to access the lab</label></div>
        <div class="input"><input type="text" name="name" value="" /></div>
        <div class="action"><input type="submit" value="Login" /></div>
      </form>
    </div>
  <?php } ?>
  </div>
</body>
</html>