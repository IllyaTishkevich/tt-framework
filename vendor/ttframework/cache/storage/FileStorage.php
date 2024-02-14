<?php


namespace tt\cache\storage;


class FileStorage extends \tt\AbstractObject implements \tt\cache\storage\CacheStorageInterface
{
    protected $manager;

    protected $dir;

    public function __construct()
    {
        $this->manager = new \tt\lib\storage\TextFileManager();
        $this->setData(null,getConfig('cache'));
    }

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
    public function set(string $filePath, $data, int $lifetime = null)
    {
        if($lifetime === null) {
            $endTime = time() + $lifetime;
        } else {
            $endTime = time() + $this->getDefaultCacheLifetime();
        }

        $dataString = serialize([
          'content' => $data,
          'end_time' => $endTime
        ]);
        $this->manager->createDir(BASE_DIR.$this->getDir(), mb_strrchr($filePath, '/', true));

        $filePath = BASE_DIR.$this->getDir().$filePath;
        return $this->manager->write($filePath, $dataString);
    }

    /**
     * Read cache.
     *
     * FileName
     * @param string $filePath
     *
     * @return mixed
     */
    public function get(string $filePath)
    {
        $filePath = BASE_DIR.$this->getDir().$filePath;
        $data = unserialize($this->manager->read($filePath));
        if($data) {
            if (time() <= $data['end_time']) {
                return $data['content'];
            } else {
                $this->manager->remove($filePath);
            }
        }

        return false;
    }

    /**
     * Delete all files in instance.
     *
     * @param string $instName
     *
     * @return mixed
     */
    public function cleanInst(string $instanceName)
    {
        $filePath = BASE_DIR.$this->getDir().$instanceName.'/';
        if($this->getInstances()[$instanceName] && is_dir($filePath)) {
            $this->manager->clean($filePath);

            return true;
        }

        return false;
    }

    /**
     * Clean all instance.
     *
     * @return mixed
     */
    public function clean()
    {
        $filePath = BASE_DIR.$this->getDir();
        foreach ($this->getInstances() as $instance => $value) {
            $this->manager->clean($filePath.'/'.$instance.'/');
        }

        return true;
    }
}