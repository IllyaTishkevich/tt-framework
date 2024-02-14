<?php


namespace tt\lib;


class Request extends \tt\AbstractObject
{
    public function __construct()
    {
        $this->setData(null, $this->cleanInput($_REQUEST));
    }

    /**
     * Injection protection.
     *
     * @param $data
     * @return array|string
     */
    protected function cleanInput($data)
    {
        if (is_array($data)) {
            $cleaned = [];
            foreach ($data as $key => $value) {
                $cleaned[$key] = $this->cleanInput($value);
            }
            return $cleaned;
        }

        return trim(htmlspecialchars($data, ENT_QUOTES));

    }
}