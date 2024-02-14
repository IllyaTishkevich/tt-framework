<?php


namespace tt\controllers;


class NotFound extends \tt\controllers\AbstractController
{
    protected  $data = [
        'template' => 'vendor/ttframework/views/404'
    ];


    public function execute()
    {
        return $this->render();
    }
}