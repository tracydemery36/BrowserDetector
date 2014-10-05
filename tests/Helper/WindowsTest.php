<?php
namespace BrowserDetectorTest\Helper;

use BrowserDetector\Helper\Windows;

/**
 * Test class for KreditCore_Class_BrowserDetector.
 * Generated by PHPUnit on 2010-09-05 at 22:13:26.
 */
class WindowsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \BrowserDetector\Helper\Windows
     */
    private $object = null;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->object = new Windows();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->object);

        parent::tearDown();
    }

    /**
     * @dataProvider providerIsWindowsPositive
     * @param string $agent
     */
    public function testIsWindowsPositive($agent)
    {
        $this->object->setUserAgent($agent);

        self::assertTrue($this->object->isWindows());
    }

    public function providerIsWindowsPositive()
    {
        return array(
            array('Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)'),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0'),
            array('Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)'),
            array('Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4'),
        );
    }

    /**
     * @dataProvider providerIsWindowsNegative
     * @param string $agent
     */
    public function testIsWindowsNegative($agent)
    {
        $this->object->setUserAgent($agent);

        self::assertFalse($this->object->isWindows());
    }

    public function providerIsWindowsNegative()
    {
        return array(
            array('Mozilla/5.0 (iPad; CPU OS 5_1_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9B206 Safari/7534.48.3'),
        );
    }
}