<?php

class getslides implements request
{
    private $request;

    public function __construct($request = null)
    {
        $this->request = $request['id'];
    }

    public function invoke()
    {
        /*
         * Получение слайдов по номеру презентации
         */
        $sql = new sql();
        print json_encode($sql->getSlides($this->request));
    }
}