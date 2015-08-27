<?php

use Aws\S3\S3Client;
use Aws\Common\InstanceMetadata\InstanceMetadataClient;
use Monolog\Logger;

/**
 * Class: S3Component.
 */
class S3Component extends Component
{
    /**
     * log.
     *
     * @var mixed
     */
    private $log;

    /**
     * __construct.
     */
    public function __construct(ComponentCollection $collection, $settings = array())
    {
        parent::__construct($collection, $settings);

        // create a log channel
        $this->log = new Logger('name');
        $this->log->pushHandler(new \Monolog\Handler\StreamHandler(LOGS . DS . 'batch.log', Logger::INFO));
        $this->log->pushHandler(new \Monolog\Handler\ErrorLogHandler());
    }

    /**
     * Create Amazon S3 Client instance.
     */
    public function getClient()
    {
        if (isset($_SERVER['OSS_AWS_ACCESS_ID']) && isset($_SERVER['OSS_AWS_SECRET_KEY'])) {
            $config = array(
                'signature' => 'v4',
                'region' => Configure::read('region'),
                'key' => $_SERVER['OSS_AWS_ACCESS_ID'],
                'secret' => $_SERVER['OSS_AWS_SECRET_KEY'],
            );
            $s3 = S3Client::factory($config);
        } else {
            $s3 = S3Client::factory(array(
                'signature' => 'v4',
                'region' => Configure::read('region'),
            ));
        }

        return $s3;
    }

    /**
     * Create Policy for S3 Upload.
     */
    public function createPolicy()
    {
        date_default_timezone_set('UTC');
        $base_time = time();

        // will be replaced from Env var or IAM Role
        if (isset($_SERVER['OSS_AWS_ACCESS_ID']) && isset($_SERVER['OSS_AWS_SECRET_KEY'])) {
            $access_id = $_SERVER['OSS_AWS_ACCESS_ID'];
            $secret_key = $_SERVER['OSS_AWS_SECRET_KEY'];
            $security_token = '';
        } else {
            $meta_client = InstanceMetadataClient::factory();
            $credentials = $meta_client->getInstanceProfileCredentials();
            $access_id = $credentials->getAccessKeyId();
            $secret_key = $credentials->getSecretKey();
            $security_token = $credentials->getSecurityToken();
        }
        $region = Configure::read('region');
        $bucket_name = Configure::read('bucket_name');

        return $this->populatePolicy($base_time, $access_id, $secret_key, $security_token, $region, $bucket_name);
    }

    ################## Private ################

    /**
     * Populate policy for S3 Upload.
     */
    private function populatePolicy($base_time, $access_id, $secret_key, $security_token, $region, $bucket_name)
    {
        $date_ymd = gmdate('Ymd', $base_time);
        $date_gm = gmdate("Ymd\THis\Z", $base_time);
        $acl = 'public-read';
        $expires = gmdate("Y-m-d\TH:i:s\Z", $base_time + 60 * 120);

        //---------------------------------------------
        // 1. Create a policy using UTF-8 encoding.
        // This includes custom meta data named "x-amz-meta-title" for example
        //---------------------------------------------
        $p_array = array(
          'expiration' => $expires,
          'conditions' => array(
            array('bucket' => $bucket_name),
            array('starts-with', '$key', ''),
            array('acl' => $acl),
            array('success_action_status' => '201'),
            array('starts-with', '$Content-Type', 'application/octetstream'),
            array('x-amz-meta-uuid' => '14365123651274'),
            array('starts-with', '$x-amz-meta-tag', ''),
            array('x-amz-credential' => $access_id . '/' . $date_ymd . '/' . $region . '/s3/aws4_request'),
            array('x-amz-algorithm' => 'AWS4-HMAC-SHA256'),
            array('x-amz-date' => $date_gm),
          ),
        );

        if ($security_token != '') {
            $p_array['conditions'][] = array('x-amz-security-token' => $security_token);
        }

        $policy = (json_encode($p_array, JSON_UNESCAPED_SLASHES));

        //---------------------------------------------
        // 2. Convert the UTF-8-encoded policy to Base64. The result is the string to sign.
        //---------------------------------------------
        $base64_policy = base64_encode($policy);
        $base64_policy = str_replace(array("\r\n", "\r", "\n", ' '), '', $base64_policy);

        //---------------------------------------------
        // 3. Create the signature as an HMAC-SHA256 hash of the string to sign. You will provide the signing key as key to the hash function.
        //---------------------------------------------
        // https://github.com/aws/aws-sdk-php/blob/00c4d18d666d2da44814daca48deb33e20cc4d3c/src/Aws/Common/Signature/SignatureV4.php
        $signinkey = $this->getSigningKey($date_ymd, $region, 's3', $secret_key);
        $signature = hash_hmac('sha256', $base64_policy, $signinkey, false);

        $result = array(
            'access_id' => $access_id,
            'base64_policy' => $base64_policy,
            'date_ymd' => $date_ymd,
            'date_gm' => $date_gm,
            'acl' => $acl,
            'security_token' => $security_token,
            'signature' => $signature,
            'success_action_status' => '201',
        );

        return $result;
    }

    /**
     * get several key for AWS API.
     *
     * @param string $shortDate
     * @param string $region
     * @param string $service
     * @param string $secretKey
     *
     * @return tring
     */
    private function getSigningKey($shortDate, $region, $service, $secretKey)
    {
        $dateKey = hash_hmac('sha256', $shortDate, 'AWS4' . $secretKey, true);
        $regionKey = hash_hmac('sha256', $region, $dateKey, true);
        $serviceKey = hash_hmac('sha256', $service, $regionKey, true);
        $signinKey = hash_hmac('sha256', 'aws4_request', $serviceKey, true);

        return $signinKey;
    }
}
