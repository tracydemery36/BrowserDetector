<?php
/**
 * This file is part of the browser-detector package.
 *
 * Copyright (c) 2012-2018, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace BrowserDetector\Factory\Device\Mobile;

use BrowserDetector\Factory;
use BrowserDetector\Loader\ExtendedLoaderInterface;
use Stringy\Stringy;

class QmobileFactory implements Factory\FactoryInterface
{
    /**
     * @var array
     */
    private $devices = [
        't250'  => 'qmobile t250',
        ' s2 '  => 'qmobile s2',
        ' s1 '  => 'qmobile s1',
        ' i7 '  => 'qmobile i7',
        'a300'  => 'qmobile a300',
        'a290'  => 'qmobile a290',
        ' a30'  => 'qmobile a30',
        '_a30'  => 'qmobile a30',
        ' a10 ' => 'qmobile a10',
        ' x60 ' => 'qmobile x60',
    ];

    /**
     * @var \BrowserDetector\Loader\ExtendedLoaderInterface
     */
    private $loader;

    /**
     * @param \BrowserDetector\Loader\ExtendedLoaderInterface $loader
     */
    public function __construct(ExtendedLoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * detects the device name from the given user agent
     *
     * @param string           $useragent
     * @param \Stringy\Stringy $s
     *
     * @return array
     */
    public function detect(string $useragent, Stringy $s): array
    {
        foreach ($this->devices as $search => $key) {
            if ($s->contains($search, false)) {
                return $this->loader->load($key, $useragent);
            }
        }

        return $this->loader->load('general qmobile device', $useragent);
    }
}
