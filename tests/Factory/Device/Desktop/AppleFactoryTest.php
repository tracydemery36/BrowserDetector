<?php
/**
 * This file is part of the browser-detector package.
 *
 * Copyright (c) 2012-2017, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace BrowserDetectorTest\Factory\Device\Desktop;

use BrowserDetector\Factory\Device\Desktop\AppleFactory;
use BrowserDetector\Loader\DeviceLoader;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Stringy\Stringy;

/**
 * Test class for \BrowserDetector\Factory\Device\Desktop\AppleFactory
 */
class AppleFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \BrowserDetector\Factory\Device\Desktop\AppleFactory
     */
    private $object = null;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $adapter      = new Local(__DIR__ . '/../../../../cache/');
        $cache        = new FilesystemCachePool(new Filesystem($adapter));
        $loader       = new DeviceLoader($cache);
        $this->object = new AppleFactory($loader);
    }

    /**
     * @dataProvider providerDetect
     *
     * @param string $agent
     * @param string $deviceName
     * @param string $marketingName
     * @param string $manufacturer
     * @param string $brand
     * @param string $deviceType
     * @param bool   $dualOrientation
     * @param string $pointingMethod
     */
    public function testDetect($agent, $deviceName, $marketingName, $manufacturer, $brand, $deviceType, $dualOrientation, $pointingMethod)
    {
        $s = new Stringy($agent);

        /** @var \UaResult\Device\DeviceInterface $result */
        list($result) = $this->object->detect($agent, $s);

        self::assertInstanceOf('\UaResult\Device\DeviceInterface', $result);

        self::assertSame(
            $deviceName,
            $result->getDeviceName(),
            'Expected device name to be "' . $deviceName . '" (was "' . $result->getDeviceName() . '")'
        );
        self::assertSame(
            $marketingName,
            $result->getMarketingName(),
            'Expected marketing name to be "' . $marketingName . '" (was "' . $result->getMarketingName() . '")'
        );
        self::assertSame(
            $manufacturer,
            $result->getManufacturer()->getName(),
            'Expected manufacturer name to be "' . $manufacturer . '" (was "' . $result->getManufacturer()->getName() . '")'
        );
        self::assertSame(
            $brand,
            $result->getBrand()->getBrandName(),
            'Expected brand name to be "' . $brand . '" (was "' . $result->getBrand()->getBrandName() . '")'
        );
        self::assertSame(
            $deviceType,
            $result->getType()->getName(),
            'Expected device type to be "' . $deviceType . '" (was "' . $result->getType()->getName() . '")'
        );
        self::assertSame(
            $dualOrientation,
            $result->getDualOrientation(),
            'Expected dual orientation to be "' . $dualOrientation . '" (was "' . $result->getDualOrientation() . '")'
        );
        self::assertSame(
            $pointingMethod,
            $result->getPointingMethod(),
            'Expected pointing method to be "' . $pointingMethod . '" (was "' . $result->getPointingMethod() . '")'
        );
    }

    /**
     * @return array[]
     */
    public function providerDetect()
    {
        return [
            [
                'this is a fake ua to trigger the fallback',
                'Macintosh',
                'Macintosh',
                'Apple Inc',
                'Apple',
                'Desktop',
                false,
                'mouse',
            ],
            [
                'Safari/9537.85.10.17.1 CFNetwork/673.5 Darwin/13.4.0 (x86_64) (iMac11%2C3)',
                'iMac',
                'iMac',
                'Apple Inc',
                'Apple',
                'Desktop',
                false,
                'mouse',
            ],
            [
                'Safari/9537.85.11.5 CFNetwork/673.5 Darwin/13.4.0 (x86_64) (MacBookPro5%2C1)',
                'MacBook Pro',
                'MacBook Pro',
                'Apple Inc',
                'Apple',
                'Desktop',
                false,
                'mouse',
            ],
            [
                'Safari/7534.53.10 CFNetwork/520.3.2 Darwin/11.3.0 (x86_64) (MacBookAir4%2C1)',
                'MacBook Air',
                'MacBook Air',
                'Apple Inc',
                'Apple',
                'Desktop',
                false,
                'mouse',
            ],
            [
                'Safari/6534.59.10 CFNetwork/454.12.4 Darwin/10.8.0 (i386) (MacBook4%2C1)',
                'MacBook',
                'MacBook',
                'Apple Inc',
                'Apple',
                'Desktop',
                false,
                'mouse',
            ],
            [
                'Unibox/283 CFNetwork/673.5 Darwin/13.4.0 (x86_64) (Macmini5%2C2)',
                'Mac Mini',
                'Mac Mini',
                'Apple Inc',
                'Apple',
                'Desktop',
                false,
                'mouse',
            ],
            [
                'Safari/6534.59.10 CFNetwork/454.12.4 Darwin/10.8.0 (i386) (MacPro4%2C1)',
                'MacPro',
                'MacPro',
                'Apple Inc',
                'Apple',
                'Desktop',
                false,
                'mouse',
            ],
            [
                'Safari5533.22.3 CFNetwork/438.16 Darwin/9.8.0 (Power%20Macintosh) (PowerMac12%2C1)',
                'PowerMac',
                'PowerMac',
                'Apple Inc',
                'Apple',
                'Desktop',
                false,
                'mouse',
            ],
        ];
    }
}