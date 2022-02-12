<?php
/*
 * Includes
 */
include_once $_SERVER["DOCUMENT_ROOT"] . "/WebError.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

Logger::configure($_SERVER["DOCUMENT_ROOT"] . '/helper/config.xml');
/*
 * Отключение вывода мусора PHP
 */
error_reporting(0);
/*
 * Разбиение адресной строки по / символу
 */
$explode = explode('/', $_SERVER['REDIRECT_URL']);
/*
 * Словарь редиректов
 */
$routes = array(
    'auth' => 'auth',
    'signup' => 'signup',
    '' => 'index',
    'account' => 'account',
    'exit' => 'exit',
    'create-presentation' => 'create-presentation',
    'init' => 'init',
    'showpresentations' => 'showpresentations',
    'presentation' => 'presentation',
    'editpresentation' => 'editpresentation',
    'search' => 'search'
);
/*
 * Главная функция в файле
 * Роутинг: в случае не заданности адресной строки редирект на index.html
 * В случае если элемент от $routes не найден редирект на страницу ошибок
 * В противном случае редирект на указанный файл от словаря роутингов
 */
try {
    if (count($explode) == 1)
        print file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/templates/index.html");
    elseif ($routes[$explode[1]] == null)
        throw new RuntimeException("Error 404");
    else
        include_once __DIR__ . "/" . $routes[$explode[1]] . ".php";
} catch (RuntimeException $exception) {
    /*
     * Вывод пользователю серверных ошибок
     */
    $webError = new WebError($exception->getMessage());
    print $webError->printError();
}