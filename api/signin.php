<?php

class signin implements request
{
    private $request;
    private $log;

    public function invoke()
    {
        Logger::configure($_SERVER["DOCUMENT_ROOT"] . '/helper/config.xml');
        $this->log = Logger::getLogger(__CLASS__);

        include_once $_SERVER["DOCUMENT_ROOT"] . "/helper/sql.php";
        include_once $_SERVER["DOCUMENT_ROOT"] . "/entities/User.php";

        $sql = new sql();
        /*
         * Сериализация пользователя
         */
        $user = new User(null, null, $this->request['password'], null, $this->request['email'], null);
        if ($sql->validateUser($user)) {
            /* Задание сессии*/
            session_start();
            $_SESSION['id'] = $sql->getUserId($user);
            http_response_code(200);
        } else {
            throw new RuntimeException("Не валидный пароль");
        }
    }

    public function __construct($request = null)
    {
        $this->request = $request;
    }
}
