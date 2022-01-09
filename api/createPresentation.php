<?php

class createPresentation implements request
{
    private $sql;

    public function __construct($request = null)
    {
        /*
         * Валидация пользователя по сессии
         */
        session_start();
        $this->sql = new sql();

        if (!isset($_SESSION['id'])) throw new RuntimeException("403\nВы не авторизированы!");

        if (!$this->sql->validateId($_SESSION['id']))
            throw new RuntimeException("403\nАккаунт не найден");

        if ($this->sql->checkValidated($_SESSION['id']) != 1)
            throw new RuntimeException("403\nАккаунт не подтвержден");
    }

    public function invoke()
    {
        /*
         * Сереалиазация слайдов
         */

        $slides = (object)[];

        foreach ($_POST as $key => $value) {
            if (strpos($key, "title")) {
                $slidenumber = str_replace("title", "", $key);
                $slides->$slidenumber[] = (object)["title" => $value];
            }

            if (strpos($key, "content")) {
                $slidenumber = str_replace("content", "", $key);
                $slides->$slidenumber[] = (object)["content" => $value];
            }
        }

        /*
         * Добавление презентаций в базу данных
         */
        $presentationId = $this->sql->createPresentation($_SESSION['id']);

        $slidesIds = [];

        foreach ($slides as $key => $slide) {
            /*
             * Добавление слайдов в базу данных
             */
            $slideId = $this->sql->createSlide($presentationId, $slide[0]->title, $slide[1]->content);
            array_push($slidesIds, [$key => $slideId]);
        }

        /*
         * Сериализация файлов
         */
        foreach ($_FILES as $key => $file) {

            $uploader = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
            $uploadfile = $uploader . basename($file['name']);

            $fileNumber = substr($key, strpos($key, "file"));
            $slideNumber = substr($key, 0, strpos($key, $fileNumber));

            foreach ($slidesIds as $key2 => $id) {
                if ($id[$slideNumber] > 0 == $slideNumber) {
                    if (move_uploaded_file($_FILES[$key]['tmp_name'], $uploadfile)) {
                        /*
                         * Добавление файлов в базу данных
                         */
                        $this->sql->createFiles($id[$slideNumber], "/uploads/" . basename($file['name']));
                    } else {
                        throw new RuntimeException('Файл не загружен!');
                    }
                }
            }
        }
    }
}