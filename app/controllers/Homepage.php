<?php

namespace app\controllers;

class Homepage extends \tt\controllers\AbstractController
{
    public function execute()
    {
        $this->setTitle('Homepage');
        $request = new \tt\lib\Request();
        $test = new \app\models\test\TestModel();
        $test->loadById(1);
        $test->setName('ffdgdffffffffffffffffffff');
        $test->save();

        echo '-------------';
        debug($test);
        return $this->render();
    }
}