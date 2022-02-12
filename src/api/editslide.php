<?php

class editslide implements request
{
    private $request;

    public function __construct($request = null)
    {
        $this->request = $request;
        if (!is_numeric($request['presentation_id']))
            throw new RuntimeException("Ошибка номера презентации!");

        if (!is_numeric($request['id']))
            throw new RuntimeException("Ошибка номера презентации!");
    }

    public function invoke()
    {
        /*
         * Вызов метода update
         */
        $sql = new sql();
        $sql->updateSlide($this->request['content'], $this->request['title'], $this->request['presentation_id'], $this->request['id']);
    }
}