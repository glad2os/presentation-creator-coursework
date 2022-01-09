<?php

session_start();

include_once $_SERVER["DOCUMENT_ROOT"] . "/helper/sql.php";
$sql = new sql();
/*
 * Валидация данных
 */

if (!isset($_SESSION['id'])) throw new RuntimeException("403\nВы не авторизированы!");

if ($sql->validateId($_SESSION['id'])) {
    if (!$sql->checkValidated($_SESSION['id']))
        throw new RuntimeException("403\nАккаунт не подтвержден");
    /*
     * view компонент
     */
    print file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/templates/createpresentation.html");
} else {
    throw new RuntimeException("403\nОшибка авторизации");
}
