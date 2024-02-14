<?php

//spl_autoload_register(
//    function ($class)
//    {
//        $filePath = BASE_DIR.$class.'.php';
//        $filePath = str_replace('\\','/', $filePath);
//        if(file_exists($filePath)) {
//            require $filePath;
//        }
//    }
//);

/**
 * Data output.
 * Is the wrapper around var_dump.
 *
 * @param $data
 * @throws Exception
 */
function debug($data)
{
    $setting = getConfig('settings/mode');
    if($setting === \tt\Core::DEPLOY_MODE_DEVELOPER) {
        echo '<pre><code>';
        var_dump($data);
        echo '</code></pre><br>';
    }
}

/**
 * Reads the configuration from the 'config' directory files.
 * The key may be composed of several parts.
 * Where the first part is the file name and the following parts are the keys of the array.
 * Use '/' to separate. e.g. 'page/source/js'
 *
 * @param string $key
 * @return mixed
 * @throws Exception
 */
function getConfig(string $key)
{
    $keys = explode('/',$key);
    $filePath = BASE_DIR.'config/'.$keys[0].'.php';
    if(file_exists($filePath)) {
        $result = require $filePath;
        if(count($keys) > 1) {
            for($i = 1; $i < count($keys); $i++) {
                if(isset($result[$keys[$i]])) {
                    $result = $result[$keys[$i]];
                } else {
                    throw new Exception('Config path"'.$key.'" not exists.');
                }
            }
        }
    } else {
        throw new Exception('File config "'.$key.'" not exists.');
    }

    return $result;
}
