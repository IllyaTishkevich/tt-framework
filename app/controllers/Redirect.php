<?php


namespace app\controllers;


class Redirect extends \tt\controllers\AbstractController
{

    function execute()
    {
        return $this->redirect('homepage', [
            'color' => 'white',
            'size' => 'l'
        ]);
    }
}