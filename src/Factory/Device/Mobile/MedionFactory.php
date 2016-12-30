<?php
/**
 * Copyright (c) 2012-2016, Thomas Mueller <mimmi20@live.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category  BrowserDetector
 *
 * @author    Thomas Mueller <mimmi20@live.de>
 * @copyright 2012-2016 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mimmi20/BrowserDetector
 */

namespace BrowserDetector\Factory\Device\Mobile;

use BrowserDetector\Factory;
use BrowserDetector\Loader\LoaderInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @category  BrowserDetector
 *
 * @copyright 2012-2016 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 */
class MedionFactory implements Factory\FactoryInterface
{
    /**
     * @var \Psr\Cache\CacheItemPoolInterface|null
     */
    private $cache = null;

    /**
     * @var \BrowserDetector\Loader\LoaderInterface|null
     */
    private $loader = null;

    /**
     * @param \Psr\Cache\CacheItemPoolInterface       $cache
     * @param \BrowserDetector\Loader\LoaderInterface $loader
     */
    public function __construct(CacheItemPoolInterface $cache, LoaderInterface $loader)
    {
        $this->cache  = $cache;
        $this->loader = $loader;
    }

    /**
     * detects the device name from the given user agent
     *
     * @param string $useragent
     *
     * @return \UaResult\Device\DeviceInterface
     */
    public function detect($useragent)
    {
        $deviceCode = 'general medion device';

        if (preg_match('/medion e5001/i', $useragent)) {
            $deviceCode = 'life e5001';
        } elseif (preg_match('/medion e4502/i', $useragent)) {
            $deviceCode = 'life e4502';
        } elseif (preg_match('/medion e4504/i', $useragent)) {
            $deviceCode = 'life e4504';
        } elseif (preg_match('/medion e4503/i', $useragent)) {
            $deviceCode = 'life e4503';
        } elseif (preg_match('/medion e4506/i', $useragent)) {
            $deviceCode = 'life e4506';
        } elseif (preg_match('/medion e4005/i', $useragent)) {
            $deviceCode = 'life e4005';
        } elseif (preg_match('/x5020/i', $useragent)) {
            $deviceCode = 'life x5020';
        } elseif (preg_match('/x5004/i', $useragent)) {
            $deviceCode = 'x5004';
        } elseif (preg_match('/x4701/i', $useragent)) {
            $deviceCode = 'x4701';
        } elseif (preg_match('/p5001/i', $useragent)) {
            $deviceCode = 'life p5001';
        } elseif (preg_match('/p5004/i', $useragent)) {
            $deviceCode = 'life p5004';
        } elseif (preg_match('/p5005/i', $useragent)) {
            $deviceCode = 'life p5005';
        } elseif (preg_match('/s5004/i', $useragent)) {
            $deviceCode = 'life s5004';
        } elseif (preg_match('/LIFETAB_P1034X/i', $useragent)) {
            $deviceCode = 'lifetab p1034x';
        } elseif (preg_match('/LIFETAB_P733X/i', $useragent)) {
            $deviceCode = 'lifetab p733x';
        } elseif (preg_match('/LIFETAB_S9714/i', $useragent)) {
            $deviceCode = 'lifetab s9714';
        } elseif (preg_match('/LIFETAB_S9512/i', $useragent)) {
            $deviceCode = 'lifetab s9512';
        } elseif (preg_match('/LIFETAB_S1036X/i', $useragent)) {
            $deviceCode = 'lifetab s1036x';
        } elseif (preg_match('/LIFETAB_S1034X/i', $useragent)) {
            $deviceCode = 'lifetab s1034x';
        } elseif (preg_match('/LIFETAB_S1033X/i', $useragent)) {
            $deviceCode = 'lifetab s1033x';
        } elseif (preg_match('/LIFETAB_S831X/i', $useragent)) {
            $deviceCode = 'lifetab s831x';
        } elseif (preg_match('/LIFETAB_S785X/i', $useragent)) {
            $deviceCode = 'lifetab s785x';
        } elseif (preg_match('/LIFETAB_S732X/i', $useragent)) {
            $deviceCode = 'lifetab s732x';
        } elseif (preg_match('/LIFETAB_P9516/i', $useragent)) {
            $deviceCode = 'lifetab p9516';
        } elseif (preg_match('/LIFETAB_P9514/i', $useragent)) {
            $deviceCode = 'lifetab p9514';
        } elseif (preg_match('/LIFETAB_P891X/i', $useragent)) {
            $deviceCode = 'lifetab p891x';
        } elseif (preg_match('/LIFETAB_P831X\.2/i', $useragent)) {
            $deviceCode = 'lifetab p831x.2';
        } elseif (preg_match('/LIFETAB_P831X/i', $useragent)) {
            $deviceCode = 'lifetab p831x';
        } elseif (preg_match('/LIFETAB_E10320/i', $useragent)) {
            $deviceCode = 'lifetab e10320';
        } elseif (preg_match('/LIFETAB_E10316/i', $useragent)) {
            $deviceCode = 'lifetab e10316';
        } elseif (preg_match('/LIFETAB_E10312/i', $useragent)) {
            $deviceCode = 'lifetab e10312';
        } elseif (preg_match('/LIFETAB_E10310/i', $useragent)) {
            $deviceCode = 'lifetab e10310';
        } elseif (preg_match('/LIFETAB_E7316/i', $useragent)) {
            $deviceCode = 'lifetab e7316';
        } elseif (preg_match('/LIFETAB_E7313/i', $useragent)) {
            $deviceCode = 'lifetab e7313';
        } elseif (preg_match('/LIFETAB_E7312/i', $useragent)) {
            $deviceCode = 'lifetab e7312';
        } elseif (preg_match('/LIFETAB_E733X/i', $useragent)) {
            $deviceCode = 'lifetab e733x';
        } elseif (preg_match('/LIFETAB_E723X/i', $useragent)) {
            $deviceCode = 'lifetab e723x';
        } elseif (preg_match('/p4501/i', $useragent)) {
            $deviceCode = 'md 98428';
        } elseif (preg_match('/p4502/i', $useragent)) {
            $deviceCode = 'life p4502';
        } elseif (preg_match('/LIFE P4310/i', $useragent)) {
            $deviceCode = 'life p4310';
        } elseif (preg_match('/p4013/i', $useragent)) {
            $deviceCode = 'life p4013';
        } elseif (preg_match('/LIFE P4012/i', $useragent)) {
            $deviceCode = 'lifetab p4012';
        } elseif (preg_match('/LIFE E3501/i', $useragent)) {
            $deviceCode = 'life e3501';
        }

        return $this->loader->load($deviceCode, $useragent);
    }
}