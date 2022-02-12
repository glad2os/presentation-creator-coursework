<?php
session_start();
/*
 * проверка наличии сессии
 */
if (isset($_SESSION['id'])) {
/*
 * Валидация сохраненных в сессии данных
 */
    include_once $_SERVER["DOCUMENT_ROOT"] . "/helper/sql.php";
    $sql = new sql();

    if ($sql->validateId($_SESSION['id'])) {
        header("Location: /account");
    } else {
        include_once $_SERVER["DOCUMENT_ROOT"] . '/exit.php';
    }
} else {
    /*
     * В случае не заданности сессии редирект на страницу авторизации
     */
    $file_get_contents = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/templates/auth.html");
    print $file_get_contents;
}