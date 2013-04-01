<?php 
	require_once 'util.php';
	
	error_log('SRP ENTERED\n');
	
	// Title
	$title = '<title>Search Results Page</title>';
	
	// Content
	$content = array();
	$content[] = '<h1>SRP</h1>';
	$content[] = '<a href="viewitem.php" class="button">Go to View Item</a>';
	
	// Flushing the output
	echo getSkeleton($title, implode("\n", $content));
?>