<?php


namespace tt\lib\storage;


class TextFileManager
{
    public function write(string $filePath, string $data)
    {
        return file_put_contents($filePath, $data);
    }

    public function read(string $filePath)
    {
        if(file_exists($filePath)) {
            return file_get_contents($filePath);
        } else {
            return false;
        }
    }

    public function add(string $filePath, string $data)
    {
        return file_put_contents($filePath, $data, FILE_APPEND);
    }

    public function remove(string $filePath)
    {
        if(file_exists($filePath)) {
            unlink($filePath);
        };

        return true;
    }

    public function clean(string $filePath)
    {
        if (file_exists($filePath)) {
            foreach (glob($filePath.'*') as $file) {
                unlink($file);
            }
        }

        return true;
    }

    public function createDir(string $filePath, string $dirName)
    {
        $dirArr = explode('/',$dirName);
        foreach ($dirArr as $dir)
        {
            $filePath .= $dir;
            if(!is_dir($filePath)) {
                mkdir($filePath);
            }
        }
        return ;
    }
}