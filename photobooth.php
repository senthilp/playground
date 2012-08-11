
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet" type="text/css">
<link href="http://html5-demos.appspot.com/static/common.css" rel="stylesheet" type="text/css">
<title>Take a picture</title>
<style>
@-webkit-keyframes glowRed {
  from {
    box-shadow: rgba(255, 0, 0, 0) 0 0 0;
  }
  50% {
    box-shadow: rgba(255, 0, 0, 1) 0 0 15px 1px;
  }
  to {
    box-shadow: rgba(255, 0, 0, 0) 0 0 0;
  }
}
html, body {
  overflow: hidden;
  margin: 0;
  padding: 0;
}
body {
  display: -webkit-flexbox;
  -webkit-flex-item-align: center;
  -webkit-flex-pack: center;
  box-sizing: border-box;
}
article {
  -webkit-flex-item-align: center;
  text-align: center;
}
#monitor {
  /*-webkit-transform: scaleX(-1);*/
  height: 400px;
  -webkit-box-reflect: below 20px -webkit-linear-gradient(top, transparent, transparent 80%, rgba(255,255,255,0.2));
}
#live {
  position: absolute;
  z-index: 1;
  color: white;
  font-weight: 600;
  font-family: Arial;
  font-size: 16pt;
  right: 35px;
  top: 20px;
  text-shadow: 1px 1px red;
  letter-spacing: 1px;
}
#live:before {
  content: '';
  border-radius: 50%;
  width: 15px;
  height: 15px;
  background: red;
  position: absolute;
  left: -20px;
  margin-top: 5px;
}
#gallery img {
	float: left;
  position: absolute;
  z-index: -1;
  height: 250px;
}
.container {
  padding: 10px 25px 5px 25px;
  background: black;
  border-radius: 4px;
  display: inline-block;
  position: relative;
}
h1 {
  font-weight: 300;
}
.blur {
  -webkit-filter: blur(3px);
}
.brightness {
  -webkit-filter: brightness(5);
}
.contrast {
  -webkit-filter: contrast(8);
}
.hue-rotate {
  -webkit-filter: hue-rotate(90deg);
}
.hue-rotate2 {
  -webkit-filter: hue-rotate(180deg);
}
.hue-rotate3 {
  -webkit-filter: hue-rotate(270deg);
}
.saturate {
  -webkit-filter: saturate(10);
}
.grayscale {
  -webkit-filter: grayscale(1);
}
.sepia {
  -webkit-filter: sepia(1);
}
.invert {
  -webkit-filter: invert(1)
}
</style>
</head>
<body>
<article>
 <h1>Take a picture</h1>
 <section id="app" hidden>
  <div class="container"><span id="live">LIVE</span><video id="monitor" autoplay onclick="changeFilter(this)" title="Click me to see different filters"></video></div>
  <p>Click to take a snap</p>
 </section>
 <p><button onclick="init(this)">Camera</button></p>
 <div id="splash">
 <!--  <p id="errorMessage">&uarr;<br>Click to begin</p> -->
 </div>
 <div id="gallery"></div>
</article>
<canvas id="photo" style="display:none"></canvas>
<script>
navigator.getUserMedia = navigator.webkitGetUserMedia || navigator.getUserMedia;
window.URL = window.URL || window.webkitURL;

var app = document.getElementById('app');
var video = document.getElementById('monitor');
var canvas = document.getElementById('photo');
var effect = document.getElementById('effect');
var gallery = document.getElementById('gallery');
var ctx = canvas.getContext('2d');
var intervalId = null;
var idx = 0;
var filters = [
  'grayscale',
  'sepia',
  'blur',
  'brightness',
  'contrast',
  'hue-rotate', 'hue-rotate2', 'hue-rotate3',
  'saturate',
  'invert',
  ''
];

function changeFilter(el) {
  el.className = '';
  var effect = filters[idx++ % filters.length];
  if (effect) {
    el.classList.add(effect);
  }
}

function gotStream(stream) {
  if (window.URL) {
    video.src = window.URL.createObjectURL(stream);
  } else {
    video.src = stream; // Opera.
  }

  video.onerror = function(e) {
    stream.stop();
  };

  stream.onended = noStream;

  video.onloadedmetadata = function(e) { // Not firing in Chrome. See crbug.com/110938.
    document.getElementById('splash').hidden = true;
    document.getElementById('app').hidden = false;
  };

  // Since video.onloadedmetadata isn't firing for getUserMedia video, we have
  // to fake it.
  setTimeout(function() {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    document.getElementById('splash').hidden = true;
    document.getElementById('app').hidden = false;
  }, 50);
}

function noStream(e) {
  document.getElementById('errorMessage').textContent = 'No camera available.';
}

function capture() {
  if (intervalId) {
    clearInterval(intervalId);
    intervalId = null;
    return;
  }

    ctx.drawImage(video, 0, 0);
    var img = document.createElement('img');
    img.src = canvas.toDataURL('image/webp');

    var angle = Math.floor(Math.random() * 36);
    var sign = Math.floor(Math.random() * 2) ? 1 : -1;
    //img.style.webkitTransform = 'rotateZ(' + (sign * angle) + 'deg)';

    var maxLeft = document.body.clientWidth;
    var maxTop = document.body.clientHeight;

    //img.style.top = Math.floor(Math.random() * maxTop) + 'px';
    //img.style.left = Math.floor(Math.random() * maxLeft) + 'px';
    img.style.top = '5%';
    img.style.left = '5%';
    gallery.appendChild(img);
}

function init(el) {
  if (!navigator.getUserMedia) {
    document.getElementById('errorMessage').innerHTML = 'Sorry. <code>navigator.getUserMedia()</code> is not available.';
    return;
  }
  el.onclick = capture;
  el.textContent = 'Snapshot';
  navigator.getUserMedia('video', gotStream, noStream);
}

window.addEventListener('keydown', function(e) {
  if (e.keyCode == 27) { // ESC
    document.querySelector('details').open = false;
  }
}, false);
</script>
<script>
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-22014378-1']);
_gaq.push(['_trackPageview']);

</script>
<!--[if IE]>
<script src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
<script>CFInstall.check({mode: 'overlay'});</script>
<![endif]-->
</body>
</html>