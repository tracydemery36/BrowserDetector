<?php
namespace Browscap\Detector\Os;

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
 * @category  Browscap
 * @package   Browscap
 * @copyright Thomas Mueller <t_mueller_stolzenhain@yahoo.de>
 * @license   http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version   SVN: $Id$
 */

use \Browscap\Detector\OsHandler;
use \Browscap\Helper\Utils;
use \Browscap\Helper\MobileDevice;
use \Browscap\Helper\Windows as WindowsHelper;
use \Browscap\Detector\MatcherInterface;
use \Browscap\Detector\MatcherInterface\OsInterface;
use \Browscap\Detector\BrowserHandler;
use \Browscap\Detector\EngineHandler;
use \Browscap\Detector\DeviceHandler;
use \Browscap\Detector\Version;

/**
 * MSIEAgentHandler
 *
 *
 * @category  Browscap
 * @package   Browscap
 * @copyright Thomas Mueller <t_mueller_stolzenhain@yahoo.de>
 * @license   http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version   SVN: $Id$
 */
class WindowsPhoneOs
    extends OsHandler
    implements MatcherInterface, OsInterface
{
    /**
     * the detected browser properties
     *
     * @var array
     */
    protected $_properties = array(
        'wurflKey' => null, // not in wurfl
        
        // os
        'device_os'              => 'Windows Phone OS',
        'device_os_version'      => '',
        'device_os_bits'         => '', // not in wurfl
        'device_os_manufacturer' => 'Microsoft', // not in wurfl
    );
    
    /**
     * Returns true if this handler can handle the given $useragent
     *
     * @return bool
     */
    public function canHandle()
    {
        $mobileDeviceHelper = new MobileDevice();
        $mobileDeviceHelper->setUserAgent($this->_useragent);
        
        $windowsHelper = new WindowsHelper();
        $windowsHelper->setUserAgent($this->_useragent);
        
        if (!$windowsHelper->isMobileWindows() 
            && !($windowsHelper->isWindows() && $mobileDeviceHelper->isMobileBrowser())
        ) {
            return false;
        }
        
        if (!$this->_utils->checkIfContains(array('Windows Phone OS', 'XBLWP7', 'ZuneWP7', 'Windows Phone'))) {
            return false;
        }
        
        $doMatch = preg_match('/Windows Phone ([\d\.]+)/', $this->_useragent, $matches);
        if ($doMatch && $matches[1] < 7) {
            return false;
        }
        
        return true;
    }
    
    /**
     * detects the browser name from the given user agent
     *
     * @param string $this->_useragent
     *
     * @return string
     */
    protected function _detectVersion()
    {
        $detector = new \Browscap\Detector\Version();
        $detector->setUserAgent($this->_useragent);
        
        if ($this->_utils->checkIfContains(array('XBLWP7', 'ZuneWP7'))) {
            $this->setCapability('device_os_version', $detector->setVersion('7.5'));
            
            return;
        }
        
        if ($this->_utils->checkIfContains(array('WPDesktop'))) {
            $this->setCapability('device_os_version', $detector->setVersion('8.0'));
            
            return;
        }
        
        $searches = array('Windows Phone OS', 'Windows Phone');
        
        $this->setCapability(
            'device_os_version', 
            $detector->detectVersion($searches)
        );
    }
    
    /**
     * gets the weight of the handler, which is used for sorting
     *
     * @return integer
     */
    public function getWeight()
    {
        return 2;
    }
    
    /**
     * returns null, if the device does not have a specific Browser
     * returns the Browser Handler otherwise
     *
     * @return null|\Browscap\Os\Handler
     */
    public function detectBrowser()
    {
        $browsers = array(
            new \Browscap\Detector\Browser\Mobile\MicrosoftInternetExplorer(),
            new \Browscap\Detector\Browser\Mobile\MicrosoftMobileExplorer(),
            new \Browscap\Detector\Browser\Mobile\OperaMobile(),
            new \Browscap\Detector\Browser\Mobile\Opera()
        );
        
        $chain = new \Browscap\Detector\Chain();
        $chain->setUserAgent($this->_useragent);
        $chain->setHandlers($browsers);
        $chain->setDefaultHandler(new \Browscap\Detector\Browser\Unknown());
        
        return $chain->detect();
    }
    
    /**
     * detects properties who are depending on the browser, the rendering engine
     * or the operating system
     *
     * @return DeviceHandler
     */
    public function detectDependProperties(
        BrowserHandler $browser, EngineHandler $engine, DeviceHandler $device)
    {
        parent::detectDependProperties($browser, $engine, $device);
        
        if ($this->_utils->checkIfContains(array('XBLWP7', 'ZuneWP7'))) {
            $browser->setCapability('mobile_browser_modus', 'Desktop Mode');
        }
        
        $browserVersion = (float)$browser->getCapability('mobile_browser_version')->getVersion(
            Version::MAJORMINOR
        );
        
        if ($browserVersion < 10.0) {
            $engine->setCapability('is_sencha_touch_ok', false);
        }
        
        return $this;
    }
}