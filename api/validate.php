<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

class validate implements request
{
    private $log;

    public function __construct()
    {
        Logger::configure($_SERVER["DOCUMENT_ROOT"] . '/helper/config.xml');
        $this->log = Logger::getLogger(__CLASS__);
        /*
         * Валидация пользователя
         */
        if (!isset($_GET['user']) || !isset($_GET['token']))
            throw new RuntimeException('No credentials');
    }

    public function invoke()
    {
        $sql = new sql();
        /*
         * Получение пользователя для дальнейшей валидации
         */
        $user = $sql->getUserById($_GET['user']);
        if ($sql->checkValidated($_GET['user'])) throw new RuntimeException("Аккаунт уже подтвержден!");
        /*
         * Сравнение хеш суммы и полченного токена
         */
        if (md5($user->username . $user->password) == $_GET['token']) {
            /*
             * Замена значения из БД и редирект
             */
            $sql->setValidated($user->id, 1);
            $this->log->info("Validated user: " . $user->id);
            header("Location: /auth");
        } else throw new RuntimeException("Срок действия прямой ссылки истек!");
    }
}