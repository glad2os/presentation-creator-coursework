<?php
/*
 * Для успешной сериализации JSON,  тк
 * ошибки PHP идут поверх RuntimeException
 */
error_reporting(0);
include_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";
Logger::configure($_SERVER["DOCUMENT_ROOT"] . '/helper/config.xml');
$log = Logger::getLogger(__CLASS__);
try {
    /*
     * Маршрутизация
     */
    $explode = explode('/', $_SERVER['REDIRECT_URL']);

    $routes = [
        'signin' => 'signin',
        'signup' => 'signup',
        'validate' => 'validate',
        'getslides' => 'getslides',
        'getfiles' => 'getfiles',
        'deletepresentation' => 'deletepresentation',
        'createPresentation' => 'createPresentation',
        'editslide' => 'editslide',
        'search' => 'search'
    ];

    header('Content-Type: application/json');
    /*
     * Получение ассоциативного массива
     * тела запроса
     */
    $request = json_decode(file_get_contents("php://input"), true);

    include_once $_SERVER["DOCUMENT_ROOT"] . "/helper/sql.php";
    include_once $_SERVER["DOCUMENT_ROOT"] . "/entities/User.php";

    $sql = new sql();
    /*
     * Тк почти все запросы к апи будут так или иначе взаимодействовать с пользователем
     * сереализуем полученные данные заранее
     */
    $user = new User(isset($request['name']) ? null : $request['name'],
        isset($request['username']) ? null : $request['username'],
        isset($request['password']) ? null : $request['password'],
        isset($request['description']) ? null : $request['description'],
        isset($request['email']) ? null : $request['email'],
        isset($request['validated']) ? null : $request['validated'],
    );
    /*
     * В случае ошибного вызова функции
     */
    if ($routes[$explode[2]] == null) {
        http_response_code(400);
        print json_encode(array('error' => "no selected action"));
    } else {
        /*
         * В случае найденного вызова
         * вызывается класс
         */
        include_once $_SERVER["DOCUMENT_ROOT"] . "/api/request.php";

        include_once __DIR__ . "/" . $routes[$explode[2]] . ".php";
        $api = new $routes[$explode[2]]($request);
        $api->invoke();
    }
    /*
     * В случае ошибок или RuntimeException
     */
} catch (RuntimeException $exception) {
    $log->error($exception);
    http_response_code(400);
    print json_encode(['error' => $exception->getMessage()]);
} catch (Error $exception) {
    $log->error($exception);
    http_response_code(400);
    print json_encode(['error' => $exception->getMessage(), 'file' => $exception->getFile(), 'line' => $exception->getLine()]);
}