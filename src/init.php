<?php
/*
 * Задание базы данных
 * для первой инициалиации
 */
$servername = getenv("DB_HOST") ?: "localhost";
$username = "root";
$password = getenv("DB_PASSWORD");

$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE onlinepresentations";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

mysqli_close($conn);

$sql = file_get_contents(__DIR__ . '/init.sql');

$mysqli = new mysqli($servername, $username, $password, "onlinepresentations");

$mysqli->multi_query($sql);