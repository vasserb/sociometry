<?php
$host = "";      // адрес сервера
$db   = "";     // имя базы данных
$user = "";           // имя пользователя
$pass = "";               // пароль

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>
