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

namespace BrowserDetector\Bits;

use Stringy\Stringy;

/**
 * Class to detect the Bit count for an Platform/Operating System
 *
 * @category  BrowserDetector
 *
 * @copyright 2012-2016 Thomas Mueller
 * @license   http://www.opensource.org/licenses/MIT MIT License
 */
class Os
{
    /**
     * @var string the user agent to handle
     */
    private $useragent = null;

    /**
     * @var int the bits of the detected browser
     */
    private $bits = null;

    /**
     * class constructor
     *
     * @param string $useragent
     */
    public function __construct($useragent)
    {
        if (!is_string($useragent) || null === $useragent) {
            throw new \UnexpectedValueException(
                'The useragent parameter is required in this function'
            );
        }

        $this->useragent = $useragent;
    }

    /**
     * @throws \UnexpectedValueException
     *
     * @return int
     */
    public function getBits()
    {
        if (null !== $this->bits) {
            return $this->bits;
        }

        $this->bits = $this->detectBits();

        return $this->bits;
    }

    /**
     * detects the bit count by this browser from the given user agent
     *
     * @return int
     */
    private function detectBits()
    {
        $s = new Stringy($this->useragent);

        if ($s->containsAny(
            ['x64', 'win64', 'wow64', 'x86_64', 'amd64', 'ppc64', 'i686 on x86_64', 'sparc64', 'osf1'],
            false
        )
        ) {
            return 64;
        }

        if ($s->containsAny(['win3.1', 'windows 3.1'], false)) {
            return 16;
        }

        // old deprecated 8 bit systems
        if ($s->containsAny(['cp/m', '8-bit'], false)) {
            return 8;
        }

        return 32;
    }
}