<?php
/*
 * Разрушение созданной сессии
 */
session_start();
$_SESSION['id'] = null;
session_destroy();
/*
 * Редирект на страницу авторизации
 */
header("Location: /auth");