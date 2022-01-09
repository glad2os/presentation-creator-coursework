<?php

class getfiles implements request
{
    private $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    public function invoke()
    {
        /*
         * Получение файлов из базы данных по номеру слайда и презентации
         */
        $sql = new sql();
        print json_encode($sql->getFiles($this->request['slide_id'], ($this->request['presentation_id'])));
    }
}