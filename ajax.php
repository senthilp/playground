<?php
header("Content-Type: application/json; charset=utf-8");

$response = array();
$data = array();

$html = array();
$html[] = 'This is response from AJAX';
$html[] = '<br/>';
$html[] = 'A JS alert should be trigerred';
$html[] = '<script>alert("script in AJAX executed...");</script>';

$data['html'] = implode("\n", $html);
$response['data'] = $data; 

echo json_encode($response) 
?>