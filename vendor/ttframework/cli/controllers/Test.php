<?php


namespace tt\cli\controllers;


class Test extends \tt\cli\AbstractCommand
{
    public function execute()
    {
        $loader = ['/','-','\\','|'];
        $dot = ['.','..','...',''];
        $i = 0;

        $this->output->initBar('[reindex catalog]');
        while($i < 100) {
            $this->output->updateBar($i);
            usleep(100000);
            $i++;
        }
        $this->output->closeBar();
    }

    public function getHelp()
    {
        // TODO: Implement getHelp() method.
    }

}