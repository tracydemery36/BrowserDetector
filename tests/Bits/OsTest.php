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
namespace BrowserDetectorTest\Bits;

use BrowserDetector\Bits\Os;

/**
 * Test class for \BrowserDetector\Detector\Device\Mobile\GeneralMobile
 */
class OsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider providerGetBits
     *
     * @param string $useragent
     * @param int    $expected
     */
    public function testGetBits($useragent, $expected)
    {
        $object = new Os($useragent);
        self::assertSame($expected, $object->getBits());
        self::assertSame($expected, $object->getBits());
    }

    /**
     * @return array[]
     */
    public function providerGetBits()
    {
        return [
            [
                'ua'       => 'Mozilla/5.0 (X11; Linux i686 on x86_64; rv:38.0) Gecko/20100101 Firefox/38.0 Iceweasel/38.5.0',
                'expected' => 64,
            ],
            [
                'ua'       => 'Mozilla/5.0 (X11; U; Linux x86_64; C) AppleWebKit/533.3 (KHTML, like Gecko) Qt/4.7.1 Safari/533.3',
                'expected' => 64,
            ],
            [
                'ua'       => 'Mozilla/2.0 (Compatible; MSIE 3.02; AOL 4.0; Windows 3.1)',
                'expected' => 16,
            ],
            [
                'ua'       => 'ShitScript/1.0 (CP/M; 8-bit)',
                'expected' => 8,
            ],
            [
                'ua'       => 'Mozilla/5.0 Galeon/1.2.6 (X11; Linux i686; U;) Gecko/20020830',
                'expected' => 32,
            ],
        ];
    }

    public function testGetBitsFails()
    {
        $this->expectException('\UnexpectedValueException');
        $this->expectExceptionMessage('The useragent parameter is required in this function');

        new Os(123);
    }
}