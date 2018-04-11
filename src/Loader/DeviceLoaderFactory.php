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
namespace BrowserDetector\Loader;

use BrowserDetector\Cache\CacheInterface;
use BrowserDetector\Loader\Helper\CacheKey;
use BrowserDetector\Loader\Helper\InitData;
use BrowserDetector\Loader\Helper\InitRules;
use Psr\Log\LoggerInterface;
use Seld\JsonLint\JsonParser;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use UaDeviceType\TypeLoader;
use UaResult\Company\CompanyLoader;

class DeviceLoaderFactory
{
    private const CACHE_PREFIX = 'device';

    /**
     * @var \BrowserDetector\Cache\CacheInterface
     */
    private $cache;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \BrowserDetector\Cache\CacheInterface $cache
     * @param \Psr\Log\LoggerInterface              $logger
     */
    public function __construct(CacheInterface $cache, LoggerInterface $logger)
    {
        $this->cache  = $cache;
        $this->logger = $logger;
    }

    /**
     * @param string $company
     * @param string $mode
     *
     * @return Loader
     */
    public function __invoke(string $company, string $mode): Loader
    {
        static $loaders = [];

        $key = sprintf('%s::%s', $company, $mode);

        if (!array_key_exists($key, $loaders)) {
            $dataPath  = __DIR__ . '/../../data/devices/' . $company;
            $rulesPath = __DIR__ . '/../../data/factories/devices/' . $mode . '/' . $company . '.json';

            $finder = new Finder();
            $finder->files();
            $finder->name('*.json');
            $finder->ignoreDotFiles(true);
            $finder->ignoreVCS(true);
            $finder->ignoreUnreadableDirs();
            $finder->in($dataPath);

            $jsonParser = new JsonParser();
            $cacheKey   = new CacheKey(self::CACHE_PREFIX, $dataPath, $rulesPath);
            $file       = new SplFileInfo($rulesPath, '', '');
            $initRules  = new InitRules($this->cache, $jsonParser, $cacheKey, $file);
            $initData   = new InitData(
                $this->cache,
                $finder,
                $jsonParser,
                $cacheKey
            );

            $loaderFactory  = new PlatformLoaderFactory($this->cache, $this->logger);
            $platformLoader = $loaderFactory('unknown');

            $loader = new DeviceLoader(
                $this->cache,
                $this->logger,
                $cacheKey,
                CompanyLoader::getInstance(),
                new TypeLoader(),
                $platformLoader
            );

            $loaders[$key] = new Loader(
                $this->cache,
                $this->logger,
                $cacheKey,
                $initRules,
                $initData,
                $loader
            );
        }

        return $loaders[$key];
    }
}
