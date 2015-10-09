<?php
/**
 * This is OpenSlideshare environmental configuration file.
 *
 * Usually you do not need to edit this file.
 * You have to set environment variables through nginx or apache configuration to keep the app portability.
 *
 */

$config = array();

$config['region'] = isset($_SERVER['OSS_REGION']) ? $_SERVER['OSS_REGION'] : "";
$config['bucket_name'] = isset($_SERVER['OSS_BUCKET_NAME']) ? $_SERVER['OSS_BUCKET_NAME'] : "";
$config['image_bucket_name'] = isset($_SERVER['OSS_IMAGE_BUCKET_NAME']) ? $_SERVER['OSS_IMAGE_BUCKET_NAME'] : "";
$config['use_s3_static_hosting'] = isset($_SERVER['OSS_USE_S3_STATIC_HOSTING']) ? $_SERVER['OSS_USE_S3_STATIC_HOSTING'] : 0;
$config['cdn_base_url'] = isset($_SERVER['OSS_CDN_BASE_URL']) ? $_SERVER['OSS_CDN_BASE_URL'] : "";
$config['sqs_url'] = isset($_SERVER['OSS_SQS_URL']) ? $_SERVER['OSS_SQS_URL'] : "";

// Convert status
if (!defined('ERROR_CONVERT_PPT_TO_PDF')) {
    define('ERROR_CONVERT_PPT_TO_PDF', -10);
}
if (!defined('ERROR_CONVERT_PDF_TO_PPM')) {
    define('ERROR_CONVERT_PDF_TO_PPM', -20);
}
if (!defined('ERROR_CONVERT_PPM_TO_JPG')) {
    define('ERROR_CONVERT_PPM_TO_JPG', -30);
}
if (!defined('ERROR_NO_CONVERT_SOURCE')) {
    define('ERROR_NO_CONVERT_SOURCE', -40);
}
if (!defined('SUCCESS_CONVERT_COMPLETED')) {
    define('SUCCESS_CONVERT_COMPLETED', 100);
}

if (!defined('OSS_VERSION')) {
    define('OSS_VERSION', '0.2.0');
}
