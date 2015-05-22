<?php
	require_once 'facepp_sdk.php';
	require 'Cloudinary.php';
	require 'Uploader.php';
	require 'Api.php';

	$facepp = new Facepp();
	$facepp->api_key       = '8da2edc020cf48e3f1316bfbe491ad7b';
	$facepp->api_secret    = 'mq8ny2DuBMWbhDD58abcGy6zeAcwwQGG';

	\Cloudinary::config(array( 
	  "cloud_name" => "tommaso", 
	  "api_key" => "333177319649396", 
	  "api_secret" => "25EyD9sasW7FqSXpMPRXbsrJODg" 
	));

	$array = \Cloudinary\Uploader::upload($_FILES["webcam"]["tmp_name"]);
	
	#detect image by url
	$params['url']          = $array['url'];
	$params['attribute']    = 'gender,age,race,smiling,glass,pose';
	$response               = $facepp->execute('/detection/detect',$params);
	

	if($response['http_code'] == 200) {
	    $data = json_decode($response['body'], 1);

	   	header('Content-Type: application/json');
		echo json_encode($data);
	}
?>