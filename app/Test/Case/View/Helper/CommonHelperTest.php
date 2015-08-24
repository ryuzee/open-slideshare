<?php

App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('CommonHelper', 'View/Helper');

/**
 * CommonHelper Test Case.
 */
class CommonHelperTest extends CakeTestCase
{
    /**
     * setUp method.
     */
    public function setUp()
    {
        parent::setUp();
        $View = new View();
        $this->Common = new CommonHelper($View);
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        unset($this->Common);

        parent::tearDown();
    }

    /**
     * testDisplay method.
     */
    public function testDisplay()
    {
        $str = 'test';
        $this->assertEqual($this->Common->display($str), $str);
        $str = "test\ntest";
        $this->assertEqual($this->Common->display($str), "test<br />\ntest");
        $str = "<h1>test</h1>\ntest";
        $this->assertEqual($this->Common->display($str), "&lt;h1&gt;test&lt;/h1&gt;<br />\ntest");
    }

    /**
     * testEndpointS3 method.
     */
    public function testEndpointS3()
    {
        // store current settings
        $config = $this->store_config();

        // When static hosting is enabled
        Configure::write('use_s3_static_hosting', 1);
        $bucket_name = 'www.example.com';
        $this->assertEqual($this->Common->endpoint_s3($bucket_name), 'http://www.example.com');

        // Public hostig is disabled
        Configure::write('use_s3_static_hosting', 0);
        Configure::write('region', 'ap-northeast-1');
        $bucket_name = 'example';
        $this->assertEqual($this->Common->endpoint_s3($bucket_name), 'https://example.s3-ap-northeast-1.amazonaws.com');

        Configure::write('region', 'us-east-1');
        $bucket_name = 'example';
        $this->assertEqual($this->Common->endpoint_s3($bucket_name), 'https://example.s3.amazonaws.com');

        // restore
        $this->restore_config($config);
    }

    /**
     * testUploadEndpoint method.
     */
    public function testUploadEndpoint()
    {
        // store current settings
        $config = $this->store_config();

        Configure::write('region', 'ap-northeast-1');
        Configure::write('bucket_name', 'example');
        $this->assertEqual($this->Common->upload_endpoint(), 'https://example.s3-ap-northeast-1.amazonaws.com');

        Configure::write('region', 'us-east-1');
        Configure::write('bucket_name', 'example');
        $this->assertEqual($this->Common->upload_endpoint(), 'https://example.s3.amazonaws.com');

        // restore
        $this->restore_config($config);
    }

    /**
     * testThumbnailUrl method.
     */
    public function testThumbnailUrl()
    {
        $config = $this->store_config();

        $key = 'hoge';

        // case 1
        Configure::write('cdn_base_url', '');
        Configure::write('image_bucket_name', 'www.example.com');
        Configure::write('use_s3_static_hosting', 1);
        $this->assertEqual($this->Common->thumbnail_url($key), 'http://www.example.com/hoge/thumbnail.jpg');

        // case 2
        Configure::write('cdn_base_url', 'http://example.cloudfront.net');
        $this->assertEqual($this->Common->thumbnail_url($key), 'http://example.cloudfront.net/hoge/thumbnail.jpg');

        $this->restore_config($config);
    }

    /**
     * testSlidePageUrl method.
     */
    public function testSlidePageUrl()
    {
        $config = $this->store_config();

        $object_key = 'sushi/toro.html';

        // case 1
        Configure::write('cdn_base_url', '');
        Configure::write('image_bucket_name', 'www.example.com');
        Configure::write('use_s3_static_hosting', 1);
        $this->assertEqual($this->Common->slide_page_url($object_key), 'http://www.example.com/sushi/toro.html');

        // case 2
        Configure::write('image_bucket_name', 'example');
        Configure::write('region', 'ap-northeast-1');
        Configure::write('use_s3_static_hosting', 0);
        $this->assertEqual($this->Common->slide_page_url($object_key), 'https://example.s3-ap-northeast-1.amazonaws.com/sushi/toro.html');

        // case 3
        Configure::write('cdn_base_url', 'http://example.cloudfront.net');
        $this->assertEqual($this->Common->slide_page_url($object_key), 'http://example.cloudfront.net/sushi/toro.html');

        $this->restore_config($config);
    }

    /**
     * testJsonUrl method.
     */
    public function testJsonUrl()
    {
        $config = $this->store_config();

        $key = 'hoge';

        // case 1
        Configure::write('cdn_base_url', '');
        Configure::write('image_bucket_name', 'www.example.com');
        Configure::write('use_s3_static_hosting', 1);
        $this->assertEqual($this->Common->json_url($key), 'http://www.example.com/hoge/list.json');

        // case 2
        Configure::write('cdn_base_url', 'http://example.cloudfront.net');
        $this->assertEqual($this->Common->json_url($key), 'http://example.cloudfront.net/hoge/list.json');

        $this->restore_config($config);
    }

    /**
     * truncateTestDataProvider
     *
     */
    public function truncateTestDataProvider()
    {
        return array(
            array('test', 'test'),
            array('testtest', 'testtest'),
            array('testtesttest', 'testt...'),
            array('鯛鮪鰻鯖', '鯛鮪鰻鯖'),
            array('鯛鮪鰻鯖鮎', '鯛...'),
        );
    }

    /**
     * testTruncate
     *
     * @dataProvider truncateTestDataProvider
     *
     * @param mixed $a
     * @param mixed $expected
     */
    public function testTruncate($a, $expected)
    {
        $this->assertEqual($this->Common->truncate($a, 8), $expected);
    }

    /**
     * store_config.
     */
    private function store_config()
    {
        $keys = array(
            'bucket_name',
            'image_bucket_name',
            'region',
            'use_s3_static_hosting',
            'cdn_base_url',
        );
        $result = array();
        foreach ($keys as $key) {
            $result[$key] = Configure::read($key);
        }

        return $result;
    }

    /**
     * restore_config.
     *
     * @param mixed $config_array
     */
    private function restore_config($config_array)
    {
        foreach ($config_array as $key => $val) {
            Configure::write($key, $val);
        }
    }
}
