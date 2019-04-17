<?php


namespace Trafiklab\Resrobot\Internal;


use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

/**
 * This caching class provides an abstraction layer on top of the PSR-6 cache implementations loaded via composer.
 * This abstraction layer allows to automatically determine the best available caching method, which allows the code to
 * work optimal on a wider variety of systems.
 *
 * @package Trafiklab\Resrobot\Internal
 */
class ResRobotCache implements Cache
{

    private const cache_prefix = "TL_resrobot_sdk_";
    private const cache_TTL = 15;

    /**
     * @var $cache Cache\Adapter\Common\AbstractCachePool cache pool which will be used.
     */
    private $cache;

    /**
     * Check if an item is present in the cache.
     *
     * @param String $key The key to search for.
     *
     * @return bool Whether or not the key is present in the cache.
     */
    public function contains(string $key): bool
    {
        $key = $this->getPrefixedAndHashedKey($key);
        $this->createCachePool();
        return $this->cache->hasItem($key);
    }

    /**
     * Get an item from the cache.
     *
     * @param String $key The key to search for.
     *
     * @return bool|object The cached object if found. If not found, false.
     */
    public function get(string $key)
    {
        $key = $this->getPrefixedAndHashedKey($key);
        $this->createCachePool();
        if ($this->cache->hasItem($key)) {
            return $this->cache->getItem($key)->get();
        } else {
            return false;
        }
    }

    /**
     * Store an item in the cache.
     *
     * @param String              $key   The key to store the object under.
     * @param object|array|string $value The object to store.
     * @param int                 $ttl   The number of seconds to keep this in cache.
     */
    public function put(string $key, $value, $ttl = self::cache_TTL): void
    {
        $key = $this->getPrefixedAndHashedKey($key);
        $this->createCachePool();

        $item = $this->cache->getItem($key);
        $item->set($value);
        if ($ttl > 0) {
            $item->expiresAfter($ttl);
        }
        $this->cache->save($item);
    }

    /**
     * @return \Cache\Adapter\Common\AbstractCachePool the cachePool for this application
     */
    private function createCachePool()
    {
        if ($this->cache == null) {
            // Try to use APC when available
            if (extension_loaded('apc')) {
                $this->cache = new \Cache\Adapter\Apcu\ApcuCachePool();
            } else {
                // Fall back to file cache
                $filesystemAdapter = new Local('./.cache/');
                $filesystem = new Filesystem($filesystemAdapter);

                $this->cache = new FilesystemCachePool($filesystem);
            }
        }
        return $this->cache;
    }

    private function getPrefixedAndHashedKey($key): string
    {
        return self::cache_prefix . md5($key);
    }


}