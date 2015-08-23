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
}
