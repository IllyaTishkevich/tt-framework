<?php
/**
 * Data storage implementation class.
 */

namespace tt;


abstract class AbstractObject
{
    protected $data = [];

    /**
     * Implementation of magic methods for setting and getting values from the array $ this->data
     * set/get/has
     *
     * @param $name
     * @param $arguments
     * @return array|bool|mixed|void|null
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if(strpos($name,'get') === 0) {
            if(count($arguments) === 0) {
                $param = substr($name, 3);
                $param = $this->getParamName($param);
                return $this->getData($param);
            } else {
                throw new \Exception('The number of arguments in the \''.$name.'\' method is incorrect.');
            }
        }

        if(strpos($name, 'set') === 0) {
            if(count($arguments) === 1) {
                $param = substr($name, 3);
                $param = $this->getParamName($param);
                $this->setData($param, $arguments[0]);
                return ;
            } else {
                throw new \Exception('The number of arguments in the \''.$name.'\' method is incorrect.');
            }
        }

        if(strpos($name, 'has') === 0) {
            if(count($arguments) === 0) {
                $param = substr($name, 3);
                $param = $this->getParamName($param);
                return $this->hasData($param);
            } else {
                throw new \Exception('The number of arguments in the \''.$name.'\' method is incorrect.');
            }
        }

        throw new \Exception('Underfined method \''.$name.'\'.');
    }

    /**
     * If the key is specified, returns a specific value from the $ this->data[$key] array.
     * If the key is not specified then returns the weight of $ this->data.
     *
     * @param string|null $key
     * @return array|mixed|null
     */
    public function getData(string $key = null)
    {
        if($key) {
            if(isset($this->data[$key])) {
                return $this->data[$key];
            } else {
                return null;
            }
        }

        return $this->data;
    }

    /**
     * Puts the data into the $ this-> data array under the given key.
     * if the key is not specified, and the data is an array, then the masses are combined.
     *
     * @param string|null $key
     * @param $data
     */
    public function setData(string $key = null, $data)
    {
        if($key === null && is_array($data)) {
            $this->data = array_merge($this->data, $data);
        } else {
            $this->data[$key] = $data;
        }
    }

    /**
     * Сhecks if data exists in the $ this->data array under the given key.
     *
     * @param string $key
     * @return bool
     */
    public function hasData(string $key)
    {
        if(isset($this->data[$key])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method for internal use. Converts 'TestString' to 'test_string'
     *
     * @param string $string
     * @return string
     */
    private function getParamName(string $string)
    {
        $string = strtolower(preg_replace('/([a-z])([A-Z])/','$1_$2', $string));
        return $string;
    }
}