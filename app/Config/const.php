<?php
$config = array();

$config['region'] = $_SERVER['REGION'];
$config['bucket_name'] = $_SERVER['BUCKET_NAME'];
$config['image_bucket_name'] = $_SERVER['IMAGE_BUCKET_NAME'];
$config['sqs_url'] = $_SERVER['SQS_URL'];

// Convert status
if(!defined('ERROR_CONVERT_PPT_TO_PDF')) define('ERROR_CONVERT_PPT_TO_PDF', -10);
if(!defined('ERROR_CONVERT_PDF_TO_PPM')) define('ERROR_CONVERT_PDF_TO_PPM', -20);
if(!defined('ERROR_CONVERT_PPM_TO_JPG')) define('ERROR_CONVERT_PPM_TO_JPG', -30);
if(!defined('ERROR_NO_CONVERT_SOURCE'))  define('ERROR_NO_CONVERT_SOURCE' , -40);
if(!defined('SUCCESS_CONVERT_COMPLETED'))  define('SUCCESS_CONVERT_COMPLETED' , 100);

