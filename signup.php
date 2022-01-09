<?php
session_start();
if (isset($_SESSION['id'])) {
    /*
     * Валидация сохраненных в сессии данных
     */
    include_once $_SERVER["DOCUMENT_ROOT"] . "/helper/sql.php";
    $sql = new sql();

    /*
     * Редирект в случае успеха
     */
    if ($sql->validateId($_SESSION['id'])) {
        header("Location: /account");
    } else {
        include_once $_SERVER["DOCUMENT_ROOT"] . '/exit.php';
    }
} else {
    $file_get_contents = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/templates/signup.html");
    print $file_get_contents;
}
