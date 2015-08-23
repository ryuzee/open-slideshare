<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('S3Component', 'Controller/Component');

/**
 * S3Component Test Case
 */
class S3ComponentTest extends CakeTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $Collection = new ComponentCollection();
        $this->S3 = new S3Component($Collection);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->S3);

        parent::tearDown();
    }

    /**
     * testGetSigningKey method
     *
     * @return void
     */
    public function testGetSigningKey()
    {
        $shortDate = '20150823';
        $region = 'ap-northeast-1';
        $service = 's3';
        $secretKey = 'sushikuitai';
        $hexed_expected_signingKey = 'ee17477456a62cd8cd6423347c93fa9010e42a852650df9b0a036cb3d97a8e45';

        $method = new ReflectionMethod($this->S3, 'getSigningKey');
        $method->setAccessible(true);
        $result = $method->invoke($this->S3, $shortDate, $region, $service, $secretKey);
        $this->assertEqual(bin2hex($result), $hexed_expected_signingKey);
    }

    public function testPopulatePolicy()
    {
        $method = new ReflectionMethod($this->S3, 'populatePolicy');
        $method->setAccessible(true);
        $base_time = 1440369374;
        $access_id = 'AK123456789';
        $secret_key = 'magurotorouniikuratai';
        $security_token = '';
        $region = 'ap-northeast-1';
        $bucket_name = 'sushi';
        $result = $method->invoke($this->S3, $base_time, $access_id, $secret_key, $security_token, $region, $bucket_name);

        $this->assertEqual($access_id, $result['access_id']);
        $base64_policy = "eyJleHBpcmF0aW9uIjoiMjAxNS0wOC0yNFQwMDozNjoxNFoiLCJjb25kaXRpb25zIjpbeyJidWNrZXQiOiJzdXNoaSJ9LFsic3RhcnRzLXdpdGgiLCIka2V5IiwiIl0seyJhY2wiOiJwdWJsaWMtcmVhZCJ9LHsic3VjY2Vzc19hY3Rpb25fc3RhdHVzIjoiMjAxIn0sWyJzdGFydHMtd2l0aCIsIiRDb250ZW50LVR5cGUiLCJhcHBsaWNhdGlvbi9vY3RldHN0cmVhbSJdLHsieC1hbXotbWV0YS11dWlkIjoiMTQzNjUxMjM2NTEyNzQifSxbInN0YXJ0cy13aXRoIiwiJHgtYW16LW1ldGEtdGFnIiwiIl0seyJ4LWFtei1jcmVkZW50aWFsIjoiQUsxMjM0NTY3ODkvMjAxNTA4MjMvYXAtbm9ydGhlYXN0LTEvczMvYXdzNF9yZXF1ZXN0In0seyJ4LWFtei1hbGdvcml0aG0iOiJBV1M0LUhNQUMtU0hBMjU2In0seyJ4LWFtei1kYXRlIjoiMjAxNTA4MjNUMjIzNjE0WiJ9XX0=";
        $this->assertEqual($base64_policy, $result['base64_policy']);
        $this->assertEqual('20150823', $result['date_ymd']);
        $this->assertEqual('20150823T223614Z', $result['date_gm']);
        $this->assertEqual('public-read', $result['acl']);
        $signature = "9a97d4b900d4372e45becd4fbdb0757bded801b0c80492c1a7cc55d63c6ef05f";
        $this->assertEqual($signature, $result['signature']);
        $this->assertEqual(201, $result['success_action_status']);
    }
}
