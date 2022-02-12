<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class mailer
{
    private $mailer;
    private $log;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        Logger::configure($_SERVER["DOCUMENT_ROOT"] . '/helper/config.xml');
        $this->log = Logger::getLogger(__CLASS__);

        /*
         * Отправка почты через smtp yandex
         * Создан тестовый пользователь
         */
        $this->mailer = new PHPMailer(true);
        $this->log->info("Создан класс " . __CLASS__);
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.yandex.ru';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = getenv("SMTP_USERNAME");
        $this->mailer->Password =  getenv("SMTP_PASSWORD");
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port = 465;
        $this->mailer->setFrom('presentations@false.team', 'Online presentations verify');
        $this->log->info("Заданы параметры");
    }

    /*
     * Отправка сообщения по заданным параметрам
     */
    /**
     * @throws Exception
     */
    public function sendMessage($subject, $email, $username, $body)
    {
        $this->mailer->addAddress($email, $username);
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;
        $this->log->info("Отправлено готово к отправке ");
        $this->mailer->send();
        $this->log->info("Отправлено отправлено");
    }
}