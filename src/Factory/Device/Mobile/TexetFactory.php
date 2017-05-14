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
namespace BrowserDetector\Factory\Device\Mobile;

use BrowserDetector\Factory;
use BrowserDetector\Loader\LoaderInterface;
use Stringy\Stringy;

/**
 * @category  BrowserDetector
 *
 * @copyright 2012-2017 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 */
class TexetFactory implements Factory\FactoryInterface
{
    /**
     * @var array
     */
    private $devices = [
        'x-pad ix 7 3g'      => 'tm-7068',
        'x-pad lite 7.1'     => 'tm-7066',
        'tm-7058hd'          => 'tm-7058hd',
        'tm-7058'            => 'tm-7058',
        'x-pad style 7.1 3g' => 'tm-7058',
        'x-navi'             => 'tm-4672',
        'tm-3204r'           => 'tm-3204r',
        'tm-7055hd'          => 'tm-7055hd',
        'tm-5204'            => 'tm-5204',
    ];

    /**
     * @var \BrowserDetector\Loader\LoaderInterface|null
     */
    private $loader = null;

    /**
     * @param \BrowserDetector\Loader\LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
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
    public function detect($useragent, Stringy $s = null)
    {
        foreach ($this->devices as $search => $key) {
            if ($s->contains($search, false)) {
                return $this->loader->load($key, $useragent);
            }
        }

        return $this->loader->load('general texet device', $useragent);
    }
}
