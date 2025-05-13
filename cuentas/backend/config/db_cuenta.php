<?php
function getCuentaConnection() {
    $host = "localhost";
    $db_name = "Cuenta";
    $username = "admin";
    $password = "admin";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $conn->exec("set names utf8");
        return $conn;
    } catch(PDOException $exception) {
        die("Error de conexión a Cuenta: " . $exception->getMessage());
    }
}
?>