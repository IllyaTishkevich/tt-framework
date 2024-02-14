<?php


namespace tt\cache\storage;


interface CacheStorageInterface
{
    /**
     * Save cache.
     *
     * FileName
     * @param string $filePath
     *
     * Data for caching.
     * @param $data
     *
     * Сaching time. Not necessary.
     * @param int|null $lifetime
     *
     * @return mixed
     */
    public function set(string $filePath, $data, int $lifetime = null);

    /**
     * Read cache.
     *
     * FileName
     * @param string $filePath
     *
     * @return mixed
     */
    public function get(string $filePath);

    /**
     * Delete all files in instance.
     *
     * @param string $instName
     *
     * @return mixed
     */
    public function cleanInst(string $instName);

    /**
     * Clean all instance.
     *
     * @return mixed
     */
    public function clean();

}