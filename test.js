navigator.getUserMedia('video', gotStream, noStream);

var noStream = function(e) {
	$('#errorMessage').text('No camera available.');
};

var gotStream = function(stream) {
	$('video').src = window.URL.createObjectURL(stream);
	$('canvas').width = $('video').videoWidth;
	$('canvas').height = $('video').videoHeight;
};

function capture() {
	$('canvas').getContext('2d').drawImage(video, 0, 0);
    $('img').src = $('canvas').toDataURL('image/webp');
}