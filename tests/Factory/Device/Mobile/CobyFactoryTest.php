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
namespace BrowserDetectorTest\Factory\Device\Mobile;

use BrowserDetector\Factory\Device\Mobile\CobyFactory;
use BrowserDetector\Loader\DeviceLoader;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Stringy\Stringy;

/**
 * Test class for \BrowserDetector\Detector\Device\Mobile\GeneralMobile
 */
class CobyFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \BrowserDetector\Factory\Device\Mobile\CobyFactory
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
        $this->object = new CobyFactory($loader);
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
                'general Coby Device',
                'general Coby Device',
                'Coby',
                'Coby',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.3; de-; MID9742 Build/IML74K) AppleWebKit/534.24 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.24 T5/2.0 bdbrowser_i18n/4.1.0.3',
                'MID9742',
                'Kyros MID9742',
                'Coby',
                'Coby',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 2.3.3; de-de; MID8128 Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
                'MID8128',
                'Kyros MID8128',
                'Coby',
                'Coby',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 2.3.3; de-de; MID8127 Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
                'MID8127',
                'Kyros MID8127',
                'Coby',
                'Coby',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 2.3; de-at; MID8024 Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
                'MID8024',
                'Kyros MID8024',
                'Coby',
                'Coby',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 2.3.3; en-us; MID7022 Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
                'MID7022',
                'Kyros MID7022',
                'Coby',
                'Coby',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 2.1-update1; fr-fr; MID7015 Build/ECLAIR) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17',
                'MID7015',
                'Kyros MID7015',
                'Coby',
                'Coby',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.3; de-de; MID1126 Build/CaribbeanSunset_v1.1) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30',
                'MID1126',
                'Kyros MID1126',
                'Coby',
                'Coby',
                'Tablet',
                true,
                'touchscreen',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.1.1; MID1125 Build/GreenDemon_v1.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.74 Safari/537.36 OPR/28.0.1764.89981',
                'MID1125',
                'Kyros MID1125',
                'Coby',
                'Coby',
                'Tablet',
                true,
                'touchscreen',
            ],
        ];
    }
}