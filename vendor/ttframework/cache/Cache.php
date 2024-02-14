<?php


namespace tt\cache;


class Cache extends \tt\AbstractObject
{
    /**
     * Content storage object;
     *
     * @var tt\cache\storage\CacheStorageInterface
     */
    private $storage;

    public function __construct()
    {
        $this->setData(null, getConfig('cache'));
        $namespace = $this->getStorage();
        if(class_exists($namespace)) {
            if(in_array('tt\cache\storage\CacheStorageInterface', class_implements($namespace))) {
                $this->storage = new $namespace;
            } else {
                throw new \Exception('Class '.$namespace.' not implement StorageInterface.');
            }
        } else {
            throw new \Exception('Class '.$namespace.' not exists.');
        }
    }

    /**
     * Get cache.
     * You can use a unique value.
     * Otherwise the class name and the current URL will be used.
     *
     * @param string $key
     * @param string $unique
     * @return mixed
     */
    public function get(string $key, string $unique = '')
    {
        if($unique != '') {
            $trace = debug_backtrace();
            $caller = $trace[1]['class'];
            $uri = $_SERVER['REQUEST_URI'];
            $file = hash('md5', preg_replace('/[^ a-zA-Z\d]/ui', '', $caller . $uri . $key ));
        } else {
            $file = hash('md5', preg_replace('/[^ a-zA-Z\d]/ui', '', $key.$unique ));
        }
        $filePath = $key.'/'.$file;

        return $this->storage->get($filePath);
    }

    /**
     * Save cache. You can use a unique value.
     * Otherwise the class name and the current URL will be used.
     * You can also set the cache lifetime.
     *
     * @param string $key
     * @param $data
     * @param string $unique
     * @param int|null $lifetime
     * @return mixed
     */
    public function set(string $key, $data, string $unique = '', int $lifetime = null)
    {
        if($unique != '') {
            $trace = debug_backtrace();
            $caller = $trace[1]['class'];
            $uri = $_SERVER['REQUEST_URI'];
            $file = hash('md5', preg_replace('/[^ a-zA-Z\d]/ui', '', $caller . $uri . $key));
        } else {
            $file = hash('md5', preg_replace('/[^ a-zA-Z\d]/ui', '', $key.$unique ));
        }
        $filePath = $key.'/'.$file;

        return $this->storage->set($filePath, $data, $lifetime);
    }

    /**
     * Delete all cache file in instance.
     *
     * @param $instName
     * @return mixed
     */
    public function cleanInstance($instName)
    {
        return $this->storage->cleanInst($instName);
    }

    /**
     * Clean all instances.
     */
    public function clearAll()
    {
        $this->storage->clean();
    }
}