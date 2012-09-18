<?php
	sleep(0);
	header("Content-Type: text/javascript");
?>

function sleep(delay) {
	var DELAY = delay || 0,// in milliseconds
		now = new Date().getTime(),
		later = now + DELAY;
	while(now < later) {
		now = new Date().getTime();
	}
}
sleep(0);

function log() {
	console.log('Done');
}

alert('Ext JS');