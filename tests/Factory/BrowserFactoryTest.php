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
namespace BrowserDetectorTest\Factory;

use BrowserDetector\Factory\BrowserFactory;
use BrowserDetector\Factory\NormalizerFactory;
use BrowserDetector\Factory\PlatformFactory;
use BrowserDetector\Loader\BrowserLoader;
use BrowserDetector\Loader\PlatformLoader;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use UaResult\Browser\Browser;
use UaResult\Company\Company;

/**
 * Test class for \BrowserDetector\Detector\Device\Mobile\GeneralMobile
 */
class BrowserFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \BrowserDetector\Factory\BrowserFactory
     */
    private $object = null;

    /**
     * @var \BrowserDetector\Factory\PlatformFactory
     */
    private $platformFactory = null;

    /**
     * @var \Psr\Cache\CacheItemPoolInterface|null
     */
    private $cache = null;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $adapter      = new Local(__DIR__ . '/../../cache/');
        $this->cache  = new FilesystemCachePool(new Filesystem($adapter));
        $loader       = new BrowserLoader($this->cache);
        $this->object = new BrowserFactory($loader);

        $platformLoader        = new PlatformLoader($this->cache);
        $this->platformFactory = new PlatformFactory($platformLoader);
    }

    /**
     * @dataProvider providerDetect
     *
     * @param string $userAgent
     * @param string $browser
     * @param string $version
     * @param string $manufacturer
     */
    public function testDetect($userAgent, $browser, $version, $manufacturer)
    {
        $normalizer = (new NormalizerFactory())->build();

        $normalizedUa = $normalizer->normalize($userAgent);
        $platform     = $this->platformFactory->detect($normalizedUa);

        /** @var \UaResult\Browser\BrowserInterface $result */
        list($result) = $this->object->detect($normalizedUa, $platform);

        self::assertInstanceOf('\UaResult\Browser\BrowserInterface', $result);
        self::assertSame(
            $browser,
            $result->getName(),
            'Expected browser name to be "' . $browser . '" (was "' . $result->getName() . '")'
        );

        self::assertInstanceOf('\BrowserDetector\Version\Version', $result->getVersion());
        self::assertSame(
            $version,
            $result->getVersion()->getVersion(),
            'Expected version to be "' . $version . '" (was "' . $result->getVersion()->getVersion() . '")'
        );

        self::assertSame(
            $manufacturer,
            $result->getManufacturer()->getName(),
            'Expected manufacturer name to be "' . $manufacturer . '" (was "' . $result->getManufacturer()->getName() . '")'
        );
    }

    /**
     * @return array[]
     */
    public function providerDetect()
    {
        return [
            [
                'Mozilla/5.0 (iPad; CPU OS 8_1_2 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12B440 Safari/600.1.4',
                'Safari',
                '8.0.0',
                'Apple Inc',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C; .NET4.0E)',
                'Internet Explorer',
                '8.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.3; de-de; GT-I9300 Build/JSS15J) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'Android Webkit',
                '4.0.0',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko)',
                'Apple Mail',
                '0.0.0',
                'Apple Inc',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko',
                'Internet Explorer',
                '11.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.35+ (KHTML, like Gecko) Version/10.2.2.1609 Mobile Safari/537.35+',
                'BlackBerry',
                '10.2.2.1609',
                'Research In Motion Limited',
                null,
            ],
            [
                'Mozilla/5.0 (Nintendo 3DS; U; ; de) Version/1.7567.EU',
                'NetFront NX',
                '0.0.0',
                'Access',
                null,
            ],
            [
                'Mozilla/5.0 (Mobile; Windows Phone 8.1; Android 4.0; ARM; Trident/7.0; Touch; rv:11.0; IEMobile/11.0; NOKIA; Lumia 930) like iPhone OS 7_0_3 Mac OS X AppleWebKit/537 (KHTML, like Gecko) Mobile Safari/537',
                'IEMobile',
                '11.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36 Edge/12.0',
                'Edge',
                '12.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; ru) Opera 8.01',
                'Opera',
                '8.01.0',
                'Opera Software ASA',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 6.0; PPC Mac OS X 10.6.8; Tasman 1.0)',
                'Internet Explorer',
                '6.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:26.0.0b2) Goanna/20150828 Gecko/20100101 AppleWebKit/601.1.37 (KHTML, like Gecko) Version/9.0 Safari/601.1.37 PaleMoon/26.0.0b2',
                'PaleMoon',
                '26.0.0-beta+2',
                'Moonchild Productions',
                null,
            ],
            [
                'NokiaN90-1/3.0545.5.1 Series60/2.8 Profile/MIDP-2.0 Configuration/CLDC-1.1 (en-US; rv:9.3.3) Clecko/20141026 Classilla/CFM',
                'Classilla',
                '0.0.0',
                null,
                null,
            ],
            [
                'UCWEB/2.0(Linux; U; Opera Mini/7.1.32052/30.3697; en-us; GT-S5670 Build/GINGERBREAD) U2/1.0.0 UCBrowser/9.4.1.362 Mobile',
                'UC Browser',
                '9.4.1.362',
                'UCWeb Inc.',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.4; pt-BR; H5000 Build/IMM76D) AppleWebKit/534.31 (KHTML, like Gecko) UCBrowser/9.3.0.321 U3/0.8.0 Mobile Safari/534.31',
                'UC Browser',
                '9.3.0.321',
                'UCWeb Inc.',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.4.2; ru-; TAB917QC-8GB Build/KVT49L) AppleWebKit/534.24 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.24 T5/2.0 bdbrowser_i18n/4.6.0.7',
                'Baidu Browser',
                '4.6.0.7',
                'Baidu',
                null,
            ],
            [
                'Mozilla/5.0 (compatible; Konqueror/3.5; Linux) KHTML/3.5.8 (like Gecko) (Debian)',
                'Konqueror',
                '3.5.0',
                'KDE e.V.',
                null,
            ],
            [
                'Mozilla/5.0 (compatible; Konqueror/2.2.2; Linux 2.4.14-xfs; X11; i686)',
                'Konqueror',
                '2.2.2',
                'KDE e.V.',
                null,
            ],
            [
                'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.8.0.11) Firefox/1.5.0.11;',
                'Firefox',
                '1.5.0.11',
                'Mozilla Foundation',
                null,
            ],
            [
                'Mozilla/6.0 (Windows NT 6.2; WOW64; rv:16.0.1) Gecko/20121011 Firefox/16.0.1',
                'Firefox',
                '16.0.1',
                'Mozilla Foundation',
                null,
            ],
            [
                'Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.1033 Mobile Safari/534.11+',
                'BlackBerry',
                '7.1.0.1033',
                'Research In Motion Limited',
                null,
            ],
            [
                'BlackBerry9000/5.0.0.1079 Profile/MIDP-2.1 Configuration/CLDC-1.1 VendorID/114',
                'BlackBerry',
                '5.0.0.1079',
                'Research In Motion Limited',
                null,
            ],
            [
                'LG-GD350/V100 Obigo/WAP2.0 Profile/MIDP-2.1 Configuration/CLDC-1.1',
                'Teleca-Obigo',
                '0.0.0',
                'Obigo',
                null,
            ],
            [
                'Mozilla/5.0 (compatible; archive-de.com/1.1; +http://archive-de.com/bot)',
                'archive-de.com bot',
                '1.1.0',
                null,
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Android 6.0; U7 Plus Build/MRA58K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36',
                'Android WebView',
                '4.0.0',
                'Google Inc',
                null,
            ],
            [
                'QuickTime\\\\xaa.7.0.4 (qtver=7.0.4;cpu=PPC;os=Mac 10.3.9)',
                'Quicktime',
                '7.0.4',
                'Apple Inc',
                null,
            ],
            [
                'Zend\Http\Client',
                'Zend_Http_Client',
                '0.0.0',
                'Zend Technologies Ltd.',
                null,
            ],
            [
                'Mozilla/5.0+(compatible; RevIP.info site analyzer v4.00; http://poweredby.revip.info)',
                'Reverse IP Lookup',
                '4.00.0',
                'binarymonkey.com',
                null,
            ],
            [
                'Mozilla / reddit pic scraper v0.8 (bklug@tyx.net)',
                'reddit pic scraper',
                '0.8.0',
                'Reddit Inc.',
                null,
            ],
            [
                'Mozilla crawl/5.0 (compatible; fairshare.cc +http://fairshare.cc)',
                'Mozilla Crawler',
                '5.0.0',
                'fairshare.cc',
                null,
            ],
            [
                'UCBrowserHD/2.4.0.367 CFNetwork/672.1.15 Darwin/14.0.0',
                'UC Browser HD',
                '2.4.0.367',
                'UCWeb Inc.',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.4; pt-BR; H5000 Build/IMM76D) AppleWebKit/534.31 (KHTML, like Gecko) UCBrowser/9.3.0.321 U3/0.8.0 Mobile Safari/534.31',
                'UC Browser',
                '9.3.0.321',
                'UCWeb Inc.',
                null,
            ],
            [
                'SAMSUNG-GT-E3309T Opera/9.80 (J2ME/MIDP; Opera Mini/4.4.34189/34.1016; U; en) Presto/2.8.119 Version/11.10',
                'Opera Mini',
                '4.4.34189',
                'Opera Software ASA',
                null,
            ],
            [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_1 like Mac OS X) AppleWebKit/537.51.2 (KHTML, like Gecko) OPiOS/8.0.1.80062 Mobile/11D201 Safari/9537.53',
                'Opera Mini',
                '8.0.1.80062',
                'Opera Software ASA',
                null,
            ],
            [
                'Opera/9.80 (Android 4.0.4; Linux; Opera Mobi/ADR-1210091050) Presto/2.11.355 Version/12.10',
                'Opera Mobile',
                '12.10.0',
                'Opera Software ASA',
                null,
            ],
            [
                'IC OpenGraph Crawler 4.5 (proprietary)',
                'IBM Connections',
                '4.5.0',
                'IBM',
                null,
            ],
            [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_1 like Mac OS X) AppleWebKit/537.51.2 (KHTML, like Gecko) Coast/3.0.0.74604 Mobile/11D201 Safari/7534.48.3',
                'Coast',
                '3.0.0.74604',
                'Opera Software ASA',
                null,
            ],
            [
                'Huawei/1.0/HUAWEI-G7300 Browser/Opera MMS/1.0 Profile/MIDP-2.0 Configuration/CLDC-1.1 Opera/9.80 (MTK; Nucleus; Opera Mobi/4000; U; it-IT) Presto/2.5.28 Version/10.10',
                'Opera Mobile',
                '10.10.0',
                'Opera Software ASA',
                null,
            ],
            [
                'MicromaxX650 ASTRO36_TD/v3 MAUI/10A1032MP_ASTRO_W1052 Release/31.12.2010 Browser/Opera Sync/SyncClient1.1 Profile/MIDP-2.0 Configuration/CLDC-1.1 Opera/9.80 (MTK; U; en-US) Presto/2.5.28 Version/10.10',
                'Opera Mobile',
                '10.10.0',
                'Opera Software ASA',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.57 Safari/537.36 OPR/16.0.1196.62',
                'Opera',
                '16.0.1196.62',
                'Opera Software ASA',
                null,
            ],
            [
                'iCabMobile/1.0.6 CFNetwork/711.1.16 Darwin/14.0.0',
                'iCab Mobile',
                '1.0.6',
                'Alexander Clauss',
                null,
            ],
            [
                'iCab/5.5 (Macintosh; U; Intel Mac OS X)',
                'iCab',
                '5.5.0',
                'Alexander Clauss',
                null,
            ],
            [
                'Mozilla/5.0 (Macintosh; U; PPC Mac OS; en) iCab 3',
                'iCab',
                '3.0.0',
                'Alexander Clauss',
                null,
            ],
            [
                'HggH PhantomJS Screenshoter',
                'HggH Screenshot System with PhantomJS',
                '0.0.0',
                'Jonas Genannt (HggH)',
                null,
            ],
            [
                'Mozilla/5.0 (bl.uk_lddc_bot; Linux x86_64) PhantomJS/1.9.7 (+http://www.bl.uk/aboutus/legaldeposit/websites/websites/faqswebmaster/index.html)',
                'bl.uk_lddc_bot',
                '0.0.0',
                'The British Legal Deposit Libraries',
                null,
            ],
            [
                'phantomas/1.8.0 (PhantomJS/1.9.8; linux x64)',
                'phantomas',
                '1.8.0',
                'Maciej Brencz',
                null,
            ],
            [
                'Mozilla/5.0 (compatible; Seznam screenshot-generator 2.0; +http://fulltext.sblog.cz/screenshot/)',
                'Seznam Screenshot Generator',
                '2.0.0',
                'Seznam.cz, a.s.',
                null,
            ],
            [
                'Mozilla/5.0 (Unknown; Linux x86_64) AppleWebKit/534.34 (KHTML, like Gecko) CasperJS/1.1.0-beta3+PhantomJS/1.9.7 Safari/534.34',
                'PhantomJS',
                '1.9.7',
                'phantomjs.org',
                null,
            ],
            [
                'Mozilla/5.0 (Unknown; BSD Four) AppleWebKit/534.34 (KHTML, like Gecko) PhantomJS/1.9.2 Safari/534.34',
                'PhantomJS',
                '1.9.2',
                'phantomjs.org',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.102 YaBrowser/14.2.1700.12599 Safari/537.36',
                'Yandex Browser',
                '14.2.1700.12599',
                'Yandex LLC',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.4; SCL24 Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/33.0.0.0 Mobile Safari/537.36 Kamelio Android',
                'Kamelio App',
                '0.0.0',
                'Kamelio',
                null,
            ],
            [
                'Mozilla/5.0 (iPod touch; U; CPU iPhone OS 4_2_1 like Mac OS X; es_ES) AppleWebKit (KHTML, like Gecko) Mobile [FBAN/FBForIPhone;FBAV/4.1.1;FBBV/4110.0;FBDV/iPod2,1;FBMD/iPod touch;FBSN/iPhone OS;FBSV/4.2.1;FBSS/1; FBCR/;FBID/phone;FBLC/es_ES;FBSF/1.0]',
                'Facebook App',
                '4.1.1',
                'Facebook',
                null,
            ],
            [
                '[FBAN/FB4A;FBAV/10.0.0.28.27;FBBV/2802760;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_CA;FBCR/VIRGIN;FBPN/com.facebook.katana;FBDV/Nexus 5;FBSV/4.4.3;FBOP/1;FBCA/armeabi-v7a:armeabi;]',
                'Facebook App',
                '10.0.0.28.27',
                'Facebook',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; MTC SMART Run Build/ARK) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36 ACHEETAHI/2100501044',
                'CM Browser',
                '0.0.0',
                'Cheetah Mobile',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 2.3.4; en-us; GT-I9100G Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) FlyFlow/2.5 Version/4.0 Mobile Safari/533.1 baidubrowser/042_6.3.5.2_diordna_008_084/gnusmas_01_4.3.2_G0019I-TG/7400001l/AFCD145CE647EC590CFE42154CB19B89%7C274573340474753/1',
                'FlyFlow',
                '2.5.0',
                'Baidu',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; gxt_dongle_3188 Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Safari/537.36 bdbrowserhd_i18n/1.8.0.1',
                'Baidu Browser HD',
                '1.8.0.1',
                'Baidu',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.1.2; ru-; s4502 Build/JZO54K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 bdbrowser_mini/1.0.0.0',
                'Baidu Browser Mini',
                '1.0.0.0',
                'Baidu',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 3.2.1; en-us) AppleWebKit/534.35 (KHTML, like Gecko) Chrome/11.0.696.65 Safari/534.35 Puffin/2.10990AT Mobile',
                'Puffin',
                '2.10990.0',
                'CloudMosa Inc.',
                null,
            ],
            [
                'stagefright/1.2 (Linux;Android 4.2.1)',
                'stagefright',
                '1.2.0',
                null,
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.0; SAMSUNG SM-G900F Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/2.1 Chrome/34.0.1847.76 Mobile Safari/537.36',
                'Samsung Browser',
                '2.1.0',
                'Samsung',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; en-us; KFTT Build/IML74K) AppleWebKit/535.19 (KHTML, like Gecko) Silk/3.11 Safari/535.19 Silk-Accelerated=true',
                'Silk',
                '3.11.0',
                'Amazon.com, Inc.',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/49.0 Chrome/43.0.2357.126_coc_coc Safari/537.36',
                'Coc Coc Browser',
                '49.0.0',
                'Coc Coc Company Limited',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 2.3.6; ja-jp; SC-02C Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1 NaverMatome-Android/4.0.7',
                'Matome',
                '4.0.7',
                'NHN Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:28.0) Gecko/20100101 Firefox/28.0 (FlipboardProxy/1.1; +http://flipboard.com/browserproxy)',
                'FlipboardProxy',
                '1.1.0',
                'Flipboard, Inc.',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.4; GT-I9300I Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/33.0.0.0 Mobile Safari/537.36 Flipboard/3.1.5/2581,3.1.5.2581,2015-02-24 17:19, +0800, ru',
                'Flipboard App',
                '3.1.5',
                'Flipboard, Inc.',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36 Seznam.cz/1.2.1',
                'Seznam Browser',
                '1.2.1',
                'Seznam.cz, a.s.',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.36 (KHTML, like Gecko) WhiteHat Aviator/33.0.1750.117 Chrome/33.0.1750.117 Safari/537.36',
                'Aviator',
                '33.0.1750.117',
                'WhiteHat Security',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android; es_mx; WT19a Build/) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 NetFrontLifeBrowser/2.3 Mobile (Dragonfruit)',
                'NetFrontLifeBrowser',
                '2.3.0',
                'Access',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; rv:26.0) Gecko/20100101 Firefox/26.0 IceDragon/26.0.0.2',
                'IceDragon',
                '26.0.0.2',
                'Comodo Group Inc',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Dragon/43.3.3.185 Chrome/43.0.2357.81 Safari/537.36',
                'Dragon',
                '43.3.3.185',
                'Comodo Group Inc',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Beamrise/27.3.0.5964 Chrome/27.0.1453.116 Safari/537.36',
                'Beamrise',
                '27.3.0.5964',
                'Beamrise Team',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Diglo/28.0.1479.334 Chrome/28.0.1479.0 Safari/537.36',
                'Diglo',
                '28.0.1479.334',
                'Diglo Inc',
                null,
            ],
            [
                'Mozilla/5.0 (Windows; U; Windows NT 5.2; en) Chrome/37.0.2049.0 (KHTML, like Gecko) Version/4.0 APUSBrowser/1.1.315  Safari/',
                'APUSBrowser',
                '1.1.315',
                'APUS-Group',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chedot/43.0.2357.402 Safari/537.36',
                'Chedot',
                '43.0.2357.402',
                'Chedot.com',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Qword/35.0.1905.5 Safari/537.36',
                'Qword Browser',
                '35.0.1905.5',
                'Qword Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Iridium/44.0 Safari/537.36 Chrome/43.0.2357.132',
                'Iridium Browser',
                '44.0.0',
                'Iridium Browser Team',
                null,
            ],
            [
                'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0; Avant Browser)',
                'Avant',
                '0.0.0',
                'Avant Force',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) MxNitro/1.0.1.3000 Chrome/35.0.1849.0 Safari/537.36',
                'Maxthon Nitro',
                '1.0.1.3000',
                'Maxthon International Limited',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 2.3.6; fa-fa; GT-S5830i Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1 MxBrowser/4.3.0.2000',
                'Maxthon',
                '4.3.0.2000',
                'Maxthon International Limited',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 2.3.4; de-de; GT-I9100 Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1 Maxthon/4.0.3.3000',
                'Maxthon',
                '4.0.3.3000',
                'Maxthon International Limited',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) SuperBird/24.0',
                'SuperBird',
                '24.0.0',
                'superbird-browser.com',
                null,
            ],
            [
                'TinyBrowser/2.0 (TinyBrowser Comment; rv:1.9.1a2pre) Gecko/20201231',
                'TinyBrowser',
                '2.0.0',
                null,
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.4.2; zh-cn; HM NOTE 1LTETD Build/KVT49L) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/5.4 TBS/025410 Mobile Safari/533.1 MicroMessenger/6.1.0.66_r1062275.542 NetType/cmnet',
                'WeChat App',
                '6.1.0.66',
                'Tencent Ltd.',
                null,
            ],
            [
                'MQQBrowser/Mini2.4 (SAMSUNG-GT-E2252)',
                'QQbrowser Mini',
                '2.4.0',
                'Tencent Ltd.',
                null,
            ],
            [
                'MQQBrowser/3.0/Mozilla/5.0 (Linux; U; Android 4.0.3; de-de; GT-I9100 Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'QQ Browser',
                '3.0.0',
                'Tencent Ltd.',
                null,
            ],
            [
                'Pinterest/0.1 +http://pinterest.com/',
                'Pinterest App',
                '0.1.0',
                'Ericsson Research',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Android 5.0; SAMSUNG-SM-N900A Build/LRX21V; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/42.0.2311.129 Mobile Safari/537.36 [Pinterest/Android]',
                'Pinterest App',
                '0.0.0',
                'Ericsson Research',
                null,
            ],
            [
                'Mozilla/5.0 (iPad; CPU OS 8_3 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Mobile/12F69 [Pinterest/iOS]',
                'Pinterest App',
                '0.0.0',
                'Ericsson Research',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.4.2; zh-cn; HUAWEI MT7-TL10 Build/HuaweiMT7-TL10) AppleWebKit/534.24 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.24 T5/2.0 baiduboxapp/6.3.1 (Baidu; P1 4.4.2)',
                'Baidu Box App',
                '6.3.1',
                'Baidu',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.2; HTC 802t 16GB Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36 wkbrowser 3.2.56 696',
                'WKBrowser',
                '3.2.56',
                'Keanu Lee',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.4; Coolpad 8675-FHD Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/33.0.0.0 Mobile Safari/537.36 Mb2345Browser/7.5.1',
                '2345 Browser',
                '7.5.1',
                null,
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.4; Coolpad 8675-FHD Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/33.0.0.0 Mobile Safari/537.36',
                'Android WebView',
                '4.0.0',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Tizen 2.3; SAMSUNG SM-Z130H) AppleWebKit/537.3 (KHTML, like Gecko) Version/2.3 Mobile Safari/537.3',
                'Samsung WebView',
                '2.3.0',
                'Samsung',
                null,
            ],
            [
                'CybEye.com/2.0 (compatible; MSIE 9.0; Windows NT 5.1; Trident/4.0; GTB6.4)',
                'CybEye',
                '2.0.0',
                'CybEye.com',
                null,
            ],
            [
                'RebelMouse/0.1 Mozilla/5.0 (compatible; http://rebelmouse.com) Gecko/20100101 Firefox/7.0.1',
                'RebelMouse',
                '0.1.0',
                'RebelMouse',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; rv:34.0) Gecko/20100101 Firefox/34.0 SeaMonkey/2.31',
                'SeaMonkey',
                '2.31.0',
                'Mozilla Foundation',
                null,
            ],
            [
                'Mozilla/5.0 (X11; U; Linux Core i7-4980HQ; de; rv:32.0; compatible; Jobboerse.com; http://www.xn--jobbrse-d1a.com) Gecko/20100401 Firefox/24.0',
                'JobBoerse Bot',
                '0.0.0',
                'Jobboerse.com',
                null,
            ],
            [
                'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.8.1.8pre) Gecko/20070928 Firefox/2.0.0.7 Navigator/9.0RC1',
                'Navigator',
                '9.0.0-RC+1',
                'Netscape',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 5.1; rv:) Gecko/20100101 Firefox/ anonymized by Abelssoft 1433017337',
                'Firefox',
                '0.0.0',
                'Mozilla Foundation',
                null,
            ],
            [
                'Mozilla/5.0 (Windows 95; Anonymisiert durch AlMiSoft Browser-Anonymisierer 69351893; Trident/7.0; rv:11.0) like Gecko',
                'Internet Explorer',
                '11.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Windows-RSS-Platform/2.0 (IE 11.0; Windows NT 6.3)',
                'Windows-RSS-Platform',
                '2.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT; MarketwireBot +http://www.marketwire.com)',
                'MarketwireBot',
                '0.0.0',
                'Marketwire L.P.',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; GoogleToolbar 7.5.5111.1712; Windows XP 5.1; MSIE 8.0.6001.18702)',
                'Google Toolbar',
                '7.5.5111.1712',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1) Netscape/8.0.4',
                'Netscape',
                '8.0.4',
                'Netscape',
                null,
            ],
            [
                'LSSRocketCrawler/1.0 LightspeedSystems',
                'Lightspeed Systems RocketCrawler',
                '1.0.0',
                'Lightspeed Systems',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 7.0;Windows NT 5.1;.NET CLR 1.1.4322;.NET CLR 2.0.50727;.NET CLR 3.0.04506.30) Lightspeedsystems',
                'Lightspeed Systems Crawler',
                '0.0.0',
                'Lightspeed Systems',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 6.0 ; .NET CLR 2.0.50215; SL Commerce Client v1.0; Tablet PC 2.0',
                'Second Life Commerce Client',
                '1.0.0',
                'Linden Labs',
                null,
            ],
            [
                'Mozilla/5.0 (Windows Phone 8.1; ARM; Trident/7.0; Touch; rv:11.0; IEMobile/11.0; NOKIA; Lumia 635) like Gecko',
                'IEMobile',
                '11.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0;  WOW64;  Trident/5.0;  BingPreview/1.0b)',
                'Bing Preview',
                '1.0.0-beta',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; )  Firefox/1.5.0.11; 360Spider',
                '360Spider',
                '0.0.0',
                'Qihoo 360 Technology Co. Ltd.',
                null,
            ],
            [
                'Outlook-Express/7.0 (MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; TmstmpExt)',
                'Windows Live Mail',
                '7.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Microsoft Office/14.0 (Windows NT 6.1; Microsoft Outlook 14.0.4760; Pro; ms-office; MSOffice 14)',
                'Outlook',
                '2010.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Microsoft Office Mobile/15.0',
                'Office',
                '2013.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; OfficeLiveConnector.1.5; OfficeLivePatch.1.3; .NET4.0E; MSOffice 12)',
                'Office',
                '2007.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Microsoft Office Protocol Discovery',
                'MS OPD',
                '0.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Microsoft Office Excel 2013 (15.0.4693) Windows NT 6.2',
                'Excel',
                '2013.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (Macintosh; Intel Mac OS X) PowerPoint/14.43.0',
                'PowerPoint',
                '2010.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'WordPress 4.0-alpha Feed Client Mozilla/5.0 Compatible',
                'WordPress',
                '4.0.0-alpha',
                'wordpress.org',
                null,
            ],
            [
                'Microsoft Office Word 2013 (15.0.4693) Windows NT 6.2',
                'Word',
                '2013.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Microsoft Office OneNote 2013 (15.0.4693) Windows NT 6.2',
                'OneNote',
                '2013.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Microsoft Office Visio 2013 (15.0.4693) Windows NT 6.2',
                'Visio',
                '2013.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Microsoft Office/15.0 (Windows NT 6.2; Microsoft Access 15.0.4693; Pro)',
                'Access',
                '2013.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Microsoft Office/15.0 (Windows NT 6.2; Microsoft Lync 15.0.4675; Pro)',
                'Lync',
                '2013.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Microsoft Office SyncProc 2013 (15.0.4693) Windows NT 6.2',
                'Office SyncProc',
                '2013.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Microsoft Office Upload Center 2013 (15.0.4693) Windows NT 6.2',
                'Office Upload Center',
                '2013.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'MSFrontPage/15.0',
                'FrontPage',
                '2013.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/2.0 (compatible; MS FrontPage 5.0)',
                'FrontPage',
                '0.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'non-browser; Microsoft Office/15.0 (Windows NT 6.2; 15.0.4691; Pro)',
                'Office',
                '2013.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Crazy Browser 1.0.5)',
                'Crazy Browser',
                '1.0.5',
                'CrazyBrowser.com',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Deepnet Explorer 1.5.0; .NET CLR 1.0.3705)',
                'Deepnet Explorer',
                '1.5.0',
                'Deepnet Security',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; KKman2.0)',
                'KKMAN',
                '2.0.0',
                'KKBOX Taiwan Co., Ltd.',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; rv:11.0; Lunascape 6.9.2.27391) like Gecko',
                'Lunascape',
                '6.9.2.27391',
                'Lunascape Corporation',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30618; .NET4.0C; .NET4.0E; Sleipnir/2.9.9)',
                'Sleipnir',
                '2.9.9',
                'Fenrir Inc',
                null,
            ],
            [
                'Smartsite HTTPClient - Mozilla/4.0 (compatible; MSIE 6.0)',
                'Smartsite HTTPClient',
                '0.0.0',
                null,
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:13.0; GomezAgent 3.0) Gecko/20100101 Firefox/13.0.1',
                'Gomez Site Monitor',
                '3.0.0',
                'Compuware Corporation',
                null,
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.3; WOW64; Trident/7.0; Touch; .NET4.0E; .NET4.0C; Tablet PC 2.0)',
                'Internet Explorer',
                '11.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/5.0)',
                'Internet Explorer',
                '9.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko',
                'Internet Explorer',
                '11.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (X11; Linux i686) AppleWebKit/536.11 (KHTML, like Gecko) Ubuntu/12.04 Chromium/20.0.1132.47 Chrome/20.0.1132.47 Safari/536.11',
                'Chromium',
                '20.0.1132.47',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Iron/30.0.1650.0 Chrome/30.0.1650.0 Safari/537.36',
                'Iron',
                '30.0.1650.0',
                'SRWare',
                null,
            ],
            [
                'Mozilla/5.0 (X11; Linux) AppleWebKit/531.2+ Midori/0.3',
                'Midori',
                '0.3.0',
                'Christian Dywan',
                null,
            ],
            [
                'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko; Google Page Speed Insights) Chrome/27.0.1453 Safari/537.36',
                'Google PageSpeed Insights',
                '0.0.0',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0 Google (+https://developers.google.com/+/web/snippet/)',
                'Google Web Snippet',
                '0.0.0',
                'Google Inc',
                null,
            ],
            [
                'SAMSUNG-SGH-E250/1.0 Profile/MIDP-2.0 Configuration/CLDC-1.1 UP.Browser/6.2.3.3.c.1.101 (GUI) MMP/2.0 (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)',
                'Google Bot Mobile',
                '2.1.0',
                'Google Inc',
                null,
            ],
            [
                'DoCoMo/2.0 N905i(c100;TB;W24H16) (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)',
                'Google Bot Mobile',
                '2.1.0',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (en-us) AppleWebKit/534.14 (KHTML, like Gecko; Google Wireless Transcoder) Chrome/9.0.597 Safari/534.14',
                'Google Wireless Transcoder',
                '0.0.0',
                'Google Inc',
                null,
            ],
            [
                'Locubot (compatible; Googlebot; msnbot)',
                'Locubot',
                '0.0.0',
                null,
                null,
            ],
            [
                'Mozilla/5.0 (iPhone5,2; iPhone; U; CPU OS 7_0_4 like Mac OS X; it_IT) com.google.GooglePlus/29676 (KHTML, like Gecko) Mobile/N42AP (gzip)',
                'Google+ App',
                '0.0.0',
                'Google Inc',
                null,
            ],
            [
                'Google-HTTP-Java-Client/1.17.0-rc (gzip)',
                'Google HTTP Client Library for Java',
                '1.17.0-RC',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (compatible;acapbot/0.1.;treat like Googlebot)',
                'acapbot',
                '0.1.0',
                null,
                null,
            ],
            [
                'Googlebot-Image/1.0',
                'Google Image Search',
                '1.0.0',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
                'Google Bot',
                '2.1.0',
                'Google Inc',
                null,
            ],
            [
                'GOOG',
                'Google Bot',
                '0.0.0',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (X11; FreeBSD; U; Viera; pt-BR) AppleWebKit/535.1 (KHTML, like Gecko) Viera/1.5.1 Chrome/14.0.835.202 Safari/535.1',
                'Viera Browser',
                '1.5.1',
                'Panasonic',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.117 Safari/537.36 Nichrome/self/33',
                'Nichrome',
                '33.0.0',
                'rambler.ru',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36 Kinza/1.2.0',
                'Kinza',
                '1.2.0',
                'kinza.jp',
                null,
            ],
            [
                'Mozilla/5.0 (X11; U; Linux x86_64; en-US) AppleWebKit/534.16 (KHTML, like Gecko, Google Keyword Suggestion) Chrome/10.0.648.127 Safari/534.16',
                'Google Keyword Suggestion',
                '0.0.0',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko; Google Web Preview) Chrome/27.0.1453 Safari/537.36',
                'Google Web Preview',
                '0.0.0',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (en-us) AppleWebKit/537.36(KHTML, like Gecko; Google-Adwords-DisplayAds-WebRender;) Chrome/27.0.1453Safari/537.36',
                'Google Adwords DisplayAds WebRender',
                '0.0.0',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36 HubSpot Webcrawler',
                'HubSpot Webcrawler',
                '0.0.0',
                'HubSpot, Inc.',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.7 (KHTML, like Gecko) RockMelt/0.16.91.483 Chrome/16.0.912.77 Safari/535.7',
                'RockMelt',
                '0.16.91.483',
                'Yahoo! Inc.',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36 SE 2.X MetaSr 1.0',
                'Sogou Explorer',
                '2.0.0',
                'Sogou Inc',
                null,
            ],
            [
                'ArchiveTeam ArchiveBot/20141009.02 (wpull 0.1002a1) and not Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.101 Safari/537.36',
                'ArchiveBot',
                '0.0.0',
                'ArchiveTeam',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.4; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.143 Safari/537.36 Edge/12.0',
                'Edge',
                '12.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (Windows Phone 10.0; Android 4.2.1; NOKIA; Lumia 930) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Mobile Safari/537.36 Edge/12.0',
                'Edge Mobile',
                '12.0.0',
                'Microsoft Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.15 (KHTML, like Gecko; +http://www.diffbot.com) Chrome/24.0.1295.0 Safari/537.15',
                'Diffbot',
                '0.0.0',
                null,
                null,
            ],
            [
                'Diffbot/0.1',
                'Diffbot',
                '0.0.0',
                null,
                null,
            ],
            [
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.89 Vivaldi/1.0.83.38 Safari/537.36',
                'Vivaldi',
                '1.0.83.38',
                'Vivaldi Technologies',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.4; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.137 Safari/537.36 LBBROWSER',
                'liebao',
                '0.0.0',
                'Kingsoft',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1709.117 Amigo/32.0.1709.117 MRCHROME SOC Safari/537.36',
                'Amigo',
                '32.0.1709.117',
                null,
                null,
            ],
            [
                'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/532.2 (KHTML, like Gecko) ChromePlus/4.0.222.3 Chrome/4.0.222.3 Safari/532.2',
                'CoolNovo Chrome Plus',
                '4.0.222.3',
                null,
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.110 Safari/537.36 CoolNovo/2.0.9.20',
                'CoolNovo',
                '2.0.9.20',
                null,
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36 Kenshoo/3049',
                'Kenshoo',
                '0.0.0',
                'Kenshoo, Ltd.',
                null,
            ],
            [
                'Mozilla/5.0 (iOS; like Mac OS X) AppleWebKit/536.36 (KHTML, like Gecko) not Chrome/27.0.1500.95 Mobile/10B141 Safari/537.36 Bowser/0.2.1',
                'Bowser',
                '0.2.1',
                'Ericsson Research',
                null,
            ],
            [
                'Mozilla/5.0 (compatible; MSIE 11.0; Windows NT 6.3; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; 360SE)',
                '360 Secure Browser',
                '0.0.0',
                'Qihoo 360 Technology Co. Ltd.',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36 QIHU 360SE',
                '360 Secure Browser',
                '0.0.0',
                'Qihoo 360 Technology Co. Ltd.',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36 QIHU 360EE',
                '360 Speed Browser',
                '0.0.0',
                'Qihoo 360 Technology Co. Ltd.',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36 ASW/1.46.1990.55',
                'Avast SafeZone',
                '1.46.1990.55',
                'AVAST Software s.r.o.',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.4.3; en-us; KFASWI Build/KTU84M) AppleWebKit/537.36 (KHTML, like Gecko) Silk/3.67 like Chrome/39.0.2171.93 Safari/537.36',
                'Silk',
                '3.67.0',
                'Amazon.com, Inc.',
                null,
            ],
            [
                'Mozilla/5.0 (iPod; CPU iPhone OS 7_1_2 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Mobile Schoolwires',
                'Schoolwires App',
                '0.0.0',
                'Schoolwires, Inc.',
                null,
            ],
            [
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/537.36 (KHTML, like Gecko) Wire/2.3.2553 Chrome/45.0.2454.85 Electron/0.35.2 Safari/537.36',
                'Wire App',
                '2.3.2553',
                'Wire Swiss GmbH',
                null,
            ],
            [
                'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.1.0.0 Safari/537.36',
                'Dragon',
                '31.1.0.0',
                'Comodo Group Inc',
                null,
            ],
            [
                'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.7 (KHTML, like Gecko) Flock/3.5.2.4599 Chrome/7.0.517.442 Safari/534.7',
                'Flock',
                '3.5.2.4599',
                null,
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.4.4; GT-I9195I Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Crosswalk/14.43.343.17 Mobile Safari/537.36',
                'Crosswalk App',
                '14.43.343.17',
                'Intel Corporation',
                null,
            ],
            [
                'Mozilla/5.0 (Windows; chromeframe/2.4.8.5746) AppleWebKit/1.0 (KHTML, like Gecko) Bromium Safari/1.0',
                'vSentry',
                '1.0.0',
                null,
                null,
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.2.2; Nexus 4 Build/JDQ39E) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.90 Mobile Safari/537.36',
                'Chrome',
                '27.0.1453.90',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.3; de-de; GT-I9100 Build/IML74K) AppleWebKit/535.7 (KHTML, like Gecko) CrMo/16.0.912.77 Mobile Safari/535.7',
                'Chrome',
                '16.0.912.77',
                'Google Inc',
                null,
            ],
            [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0_2 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) CriOS/30.0.1599.16 Mobile/11A501 Safari/8536.25',
                'Chrome',
                '30.0.1599.16',
                'Google Inc',
                null,
            ],
            [
                'Dolphin http client/10.3.0(280) (Android)',
                'Dolphin smalltalk http client',
                '10.3.0',
                'Steve Waring',
                null,
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.4; de-de; GT-I9100 Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Dolphin/INT-1.0 Mobile Safari/534.30',
                'Dolfin',
                '1.0.0',
                'MopoTab Inc',
                null,
            ],
            [
                'SAMSUNG-SGH-T528g/T528UDKE4[TF355314045027030009640018153425713] Dolfin/1.5 SMM-MMS/1.2.0 profile/MIDP-2.1 configuration/CLDC-1.1',
                'Dolfin',
                '1.5.0',
                'MopoTab Inc',
                null,
            ],
        ];
    }
}
