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

class MicromaxFactory implements Factory\FactoryInterface
{
    /**
     * @var array
     */
    private $devices = [
        'q413'   => 'micromax q413',
        'q392'   => 'micromax q392',
        'q391'   => 'micromax q391',
        'q380'   => 'micromax q380',
        'q372'   => 'micromax q372',
        'q345'   => 'micromax q345',
        'q332'   => 'micromax q332',
        'q327'   => 'micromax q327',
        'e481'   => 'micromax e481',
        'e455'   => 'micromax e455',
        'e352'   => 'micromax e352',
        'e313'   => 'micromax e313',
        'd321'   => 'micromax d321',
        'aq5001' => 'micromax aq5001',
        'aq4501' => 'micromax aq4501',
        'a300'   => 'micromax a300',
        'a177'   => 'micromax a177',
        'a120'   => 'micromax a120',
        'a116i'  => 'micromax a116i',
        'a116'   => 'micromax a116',
        'a114'   => 'micromax a114',
        'a110'   => 'micromax a110',
        'a107'   => 'micromax a107',
        'a106'   => 'micromax a106',
        'a101'   => 'micromax a101',
        'a093'   => 'micromax a093',
        'a065'   => 'micromax a065',
        'a99'    => 'micromax a99',
        'a96'    => 'micromax a96',
        'a87'    => 'micromax a87',
        'a59'    => 'micromax a59',
        'a50'    => 'micromax a50',
        'a47'    => 'micromax a47',
        'a40'    => 'micromax a40',
        'a35'    => 'micromax a35',
        'a27'    => 'micromax a27',
        'x650'   => 'micromax x650',
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

        return $this->loader->load('general micromax device', $useragent);
    }
}
