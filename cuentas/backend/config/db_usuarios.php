<?php
function getUsuarioConnection() {
    $host = "localhost";
    $db_name = "usuarios";
    $username = "amdin";
    $password = "admin";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $conn->exec("set names utf8");
        return $conn;
    } catch(PDOException $exception) {
        die("Error de conexión a Usuario: " . $exception->getMessage());
    }
}
?>