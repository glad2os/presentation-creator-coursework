<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/helper/sql.php";
$file_get_contents = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/templates/showpresentations.html");
$sql = new sql();
$allPresentations = $sql->getAllPresentations();
$_str = "";
/*
 * Вывод всех презентаций
 */
foreach ($allPresentations as $presentation) {
    $_str .= "<a href='/presentation?id={$presentation['id']}'>Презентация#" . $presentation['id'] . " от пользователя " . $presentation['username'] . "</a><br>";
}
$file_get_contents = str_replace("{{PRESENTATION}}", $_str, $file_get_contents);
print $file_get_contents;
