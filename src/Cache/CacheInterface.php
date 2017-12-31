<?php
declare(strict_types = 1);

namespace BrowserDetector\Cache;

use Psr\SimpleCache\CacheInterface as PsrCacheInterface;

/**
 * a cache proxy to be able to use the cache adapters provided by the WurflCache package
 */
interface CacheInterface
{
    /**
     * Constructor class, checks for the existence of (and loads) the cache and
     * if needed updated the definitions
     *
     * @param \Psr\SimpleCache\CacheInterface $adapter
     */
    public function __construct(PsrCacheInterface $adapter);

    /**
     * Get an item.
     *
     * @param string $cacheId
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return mixed Data on success, null on failure
     */
    public function getItem(string $cacheId);

    /**
     * save the content into an php file
     *
     * @param string $cacheId     The cache id
     * @param mixed  $content     The content to store
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return bool whether the file was correctly written to the disk
     */
    public function setItem(string $cacheId, $content) : bool;

    /**
     * Test if an item exists.
     *
     * @param string $cacheId
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return bool
     */
    public function hasItem(string $cacheId) : bool;

    /**
     * Remove an item.
     *
     * @param string $cacheId
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return bool
     */
    public function removeItem(string $cacheId) : bool;

    /**
     * Flush the whole storage
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return bool
     */
    public function flush() : bool;
}
