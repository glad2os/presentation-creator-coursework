<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

class signup implements request
{
    private $request;
    private $log;

    public function __construct($request = null)
    {
        Logger::configure($_SERVER["DOCUMENT_ROOT"] . '/helper/config.xml');
        $this->log = Logger::getLogger(__CLASS__);

        /*
         * Валидация данных
         */
        if (empty($request['email']) || strlen($request['email']) < 3 ||
            empty($request['password']) || strlen($request['password']) < 3 ||
            empty($request['name']) || strlen($request['name']) < 3 ||
            empty($request['username']) || strlen($request['username']) < 3 ||
            empty($request['description']) || strlen($request['description']) < 3) {
            throw new RuntimeException("Поля пустые!");
        }
        $this->request = $request;

    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function invoke()
    {
        $sql = new sql();
        /*
         * Сериализация пользователя
         */
        $user = new User($this->request['name'],
            $this->request['username'],
            $this->request['password'],
            $this->request['description'],
            $this->request['email'],
            0);
        /*
         * Регистрация пользователя по сущности
         */
        $registrationUser = $sql->registrationUser($user);
        $this->log->info("New User: " . $registrationUser);

        /*
         * Отправка почты
         */
        include_once $_SERVER["DOCUMENT_ROOT"] . "/helper/mailer.php";
        $this->log->info("Подключен файл " . $_SERVER["DOCUMENT_ROOT"] . "/helper/mailer.php");
        $mailer = new mailer();

        /*
         * В качестве токена выступает хеш сумма логина и пароля
         */

        $mailer->sendMessage("Validate account", $this->request['email'], "Online presentations",
            stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://'
                . $_SERVER['HTTP_HOST'] . "/api/validate?user=" . $registrationUser .
                "&token=" . md5($this->request['username'] . $this->request['password']));

    }
}