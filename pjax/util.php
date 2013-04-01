<?php
	function getSkeleton($title, $content) {
		$html = array();
		$html[] = '<!doctype html>';
		$html[] = '<html>';
		$html[] = '<head>';
		$html[] = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
		$html[] = $title;
		$html[] = '<link type="text/css" href="page.css" rel="stylesheet"/>';
		$html[] = '</head>';
		$html[] = '<body>';
		$html[] = '<header>HEADER</header>';
		$html[] = '<div class="container" id="pjax-container">';
		$html[] = $content;
		$html[] = '</div>';
		$html[] = '<footer>FOOTER</footer>';
		$html[] = '<script src="http://code.jquery.com/jquery-1.9.1.js"></script>';
		$html[] = '<script src="jquery.pjax.js"></script>';
		$html[] = '<script>';
		$html[] = '$(document).pjax("a", "#pjax-container", {"fragment":"#pjax-container"});';
		$html[] = '$(function() {';
		$html[] = 'sessionStorage.setItem("header", document.getElementsByTagName("header")[0].outerHTML);';
		$html[] = 'sessionStorage.setItem("footer", document.getElementsByTagName("footer")[0].outerHTML);';
		$html[] = '});';
		$html[] = '</script>';
		$html[] = '</body>';
		$html[] = '</html>';
		
		return implode("\n", $html);
	}
?>