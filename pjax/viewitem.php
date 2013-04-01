<?php 
	require_once 'util.php';

	error_log('View Item ENTERED\n');
	
	// Title
	$title = '<title>View Item</title>';
	
	// Content
	$content = array();
	$content[] = '<h1>View Item</h1>';
	$content[] = '<a href="http://feedback.ebay.com/ws/eBayISAPI.dll?ViewFeedback2&userid=wirelesshut2010&&_trksid=p2047675.l2560&rt=nc&iid=281082876442&sspagename=VIP:feedback&ftab=FeedbackAsSeller" class="button">Go to Feedback</a>.';
	
	// Flushing the output
	echo getSkeleton($title, implode("\n", $content));
?>