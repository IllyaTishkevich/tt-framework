<?php
/**
 * 'storage' - The property contains the namespace of the class used for caching.
 * The class must implement tt\cache\storage\CacheStorageInterface
 *
 * 'dir' - Path to cache files.
 *
 * 'default_cache_lifetime' - Default caching time in seconds.
 *
 * 'instances' - Array of cache containers for cache.
 * 0 - disable caching in this container. 1 - enable.
 */

return [
    'instances' => [
        'page' => 1,
        'data' => 1,
    ],
    'storage' => 'tt\cache\storage\FileStorage',
    'dir' => 'var/cache/',
    'default_cache_lifetime' => 3600
];