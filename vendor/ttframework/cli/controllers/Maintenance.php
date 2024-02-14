<?php


namespace tt\cli\controllers;


class Maintenance extends \tt\cli\AbstractCommand
{
    public function getHelp()
    {
        return [
            'maintenance:enable',
            'maintenance:disable'
        ];
    }

    public function execute()
    {
        $this->writeHelper();
    }

    /**
     * enable maintenance mode.
     */
    public function enable()
    {
        file_put_contents(BASE_DIR.'maintenance', '1');
    }

    /**
     * disable maintenance mode.
     */
    public function disable()
    {
        if(file_exists(BASE_DIR.'maintenance')) {
            unlink(BASE_DIR.'maintenance');
        }
    }
}