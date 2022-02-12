<?php
session_start();

include_once $_SERVER["DOCUMENT_ROOT"] . "/helper/sql.php";
$sql = new sql();

/*
 * Валидация
 */
if ($sql->validateId($_SESSION['id'])) {
    $file_get_contents = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/templates/account.html");
    $user = $sql->getUserById($_SESSION['id']);

    if (!$user->validated)
        throw new RuntimeException("403\nАккаунт не подтвержден");

    /*
     * Шаблонизатор данных
     */
    $file_get_contents = str_replace("{{LOGIN}}", $user->username, $file_get_contents);
    $file_get_contents = str_replace("{{ID}}", $user->id, $file_get_contents);
    $file_get_contents = str_replace("{{EMAIL}}", $user->email, $file_get_contents);
    $file_get_contents = str_replace("{{VALIDATED}}", ($user->id) ? 'подтвержден' : "Не подтвержден", $file_get_contents);
    $file_get_contents = str_replace("{{NAME}}", $user->username, $file_get_contents);
    $file_get_contents = str_replace("{{USERNAME}}", $user->name, $file_get_contents);
    $file_get_contents = str_replace("{{DESCRIPTION}}", $user->description, $file_get_contents);
    $usersPresentations = $sql->getUsersPresentations($_SESSION['id']);
    $presentations = "";
    foreach ($usersPresentations as $presentation) {
        $presentations .= "<a href='/presentation?id={$presentation['id']}'>Презентация#" . $presentation['id'] . "</a>
<a href='/editpresentation?id={$presentation['id']}'>[Изменить]</a> <a href='#' onclick='deletepresentation({$presentation['id']})'>[Удалить]</a><br>";
    }

    $file_get_contents = str_replace("{{PRESENTATIONS}}", $presentations, $file_get_contents);

    /*
     * Презентации
     */
    print $file_get_contents;
} else {
    throw new RuntimeException("403\nОшибка авторизации");
}
