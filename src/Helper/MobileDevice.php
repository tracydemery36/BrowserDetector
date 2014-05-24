<?php
namespace BrowserDetector\Helper;

    /**
     * PHP version 5.3
     *
     * LICENSE:
     *
     * Copyright (c) 2013, Thomas Mueller <t_mueller_stolzenhain@yahoo.de>
     *
     * All rights reserved.
     *
     * Redistribution and use in source and binary forms, with or without
     * modification, are permitted provided that the following conditions are met:
     *
     * * Redistributions of source code must retain the above copyright notice,
     *   this list of conditions and the following disclaimer.
     * * Redistributions in binary form must reproduce the above copyright notice,
     *   this list of conditions and the following disclaimer in the documentation
     *   and/or other materials provided with the distribution.
     * * Neither the name of the authors nor the names of its contributors may be
     *   used to endorse or promote products derived from this software without
     *   specific prior written permission.
     *
     * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
     * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
     * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
     * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
     * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
     * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
     * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
     * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
     * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
     * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
     * POSSIBILITY OF SUCH DAMAGE.
     *
     * @category  BrowserDetector
     * @package   BrowserDetector
     * @copyright 2012-2013 Thomas Mueller
     * @license   http://opensource.org/licenses/BSD-3-Clause New BSD License
     */
/**
 * helper to get information if the device is a mobile
 *
 * @package   BrowserDetector
 */
class MobileDevice
{
    /**
     * @var string the user agent to handle
     */
    private $_useragent = '';

    /**
     * @var \BrowserDetector\Helper\Utils the helper class
     */
    private $utils = null;

    /**
     * Class Constructor
     *
     * @return \BrowserDetector\Helper\MobileDevice
     */
    public function __construct()
    {
        $this->utils = new Utils();
    }

    /**
     * sets the user agent to be handled
     *
     * @param string $userAgent
     *
     * @return \BrowserDetector\Helper\MobileDevice
     */
    public function setUserAgent($userAgent)
    {
        $this->_useragent = $userAgent;
        $this->utils->setUserAgent($userAgent);

        return $this;
    }

    /**
     * Returns true if the give $userAgent is from a mobile device
     *
     * @param string $userAgent
     *
     * @return bool
     */
    public function isMobileBrowser()
    {
        /**
         * @var array Collection of mobile browser keywords
         */
        $mobileBrowsers = array(
            'android',
            'arm; touch',
            'aspen simulator',
            'bada',
            'bb10',
            'blackberry',
            'blazer',
            'bolt',
            'brew',
            'cldc',
            'dalvik',
            'danger hiptop',
            'embider',
            'fennec',
            'firefox or ie',
            'foma',
            'folio100',
            'gingerbread',
            'hd_mini_t',
            'hp-tablet',
            'hpwOS',
            'htc',
            'ipad',
            'iphone',
            'iphoneosx',
            'iphone os',
            'ipod',
            'iris',
            'iuc(u;ios',
            'j2me',
            'juc(linux;u;',
            'juc (linux; u;',
            'kindle',
            'lenovo',
            'like mac os x',
            'linux armv',
            'look-alike',
            'maemo',
            'meego',
            'midp',
            'mobile version',
            'mqqbrowser',
            'netfront',
            'nintendo',
            'nitro',
            'nokia',
            'obigo',
            'openwave',
            'opera mini',
            'opera mobi',
            'palm',
            'phone',
            'playstation',
            'pocket pc',
            'pocketpc',
            'rim tablet',
            'samsung',
            'series40',
            'series 60',
            'silk',
            'symbian',
            'symbianos',
            'symbos',
            'toshiba_ac_and_az',
            'touchpad',
            'transformer tf',
            'up.browser',
            'up.link',
            'xblwp7',
            'wap2',
            'webos',
            'wetab-browser',
            'windows ce',
            'windows mobile',
            'windows phone os',
            'wireless',
            'xda_diamond_2',
            'zunewp7'
        );
        if ($this->utils->checkIfContains($mobileBrowsers, true)) {
            $noMobiles = array(
                'xbox', 'badab', 'badap', 'simbar', 'google-tr', 'googlet',
                'google wireless transcoder', 'eeepc', 'i9988_custom',
                'i9999_custom', 'wuid='
            );

            if ($this->utils->checkIfContains($noMobiles, true)) {
                return false;
            }

            return true;
        }

        if ($this->utils->checkIfContains('tablet', true)
            && !$this->utils->checkIfContains('tablet pc', true)
        ) {
            return true;
        }

        if ($this->utils->checkIfContains('mobile', true)
            && !$this->utils->checkIfContains('automobile', true)
        ) {
            return true;
        }

        if ($this->utils->checkIfContains('sony', true)
            && !$this->utils->checkIfContains('sonydtv', true)
        ) {
            return true;
        }

        if ($this->utils->checkIfContains('Windows NT 6.2; ARM;')) {
            return true;
        }

        $doMatch = preg_match('/\d+\*\d+/', $this->_useragent);
        if ($doMatch) {
            return true;
        }
        
        $helper = new \BrowserDetector\Helper\FirefoxOs();
        $helper->setUserAgent($this->_useragent);

        if ($helper->isFirefoxOs()) {
            return true;
        }

        return false;
    }
}