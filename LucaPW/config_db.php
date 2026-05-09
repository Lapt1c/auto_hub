<?php
// config_db.php

$host = '127.0.0.1';
$db_user = 'root'; // Userul standard din XAMPP
$db_pass = '';     // Parola standard din XAMPP
$db_name = 'autohub_db';


$conn_mysqli = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn_mysqli->connect_error) {
    die("Eroare conexiune MySQLi: " . $conn_mysqli->connect_error);
}

try {
    $dsn_mysql = "mysql:host=$host;dbname=$db_name;charset=utf8mb4";
    $conn_pdo = new PDO($dsn_mysql, $db_user, $db_pass);
    // Aruncă excepții în caz de eroare (foarte util la depanare)
    $conn_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Eroare conexiune PDO MySQL: " . $e->getMessage());
}


try {

    $sqlite_path = __DIR__ . '/baza_auxiliara.sqlite';
    $conn_sqlite = new PDO("sqlite:" . $sqlite_path);
    $conn_sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $query_sqlite = "CREATE TABLE IF NOT EXISTS loguri_sistem (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        actiune TEXT NOT NULL,
                        data_timp DATETIME DEFAULT CURRENT_TIMESTAMP
                    )";
    $conn_sqlite->exec($query_sqlite);

} catch (PDOException $e) {
    die("Eroare conexiune SQLite: " . $e->getMessage());
}
?>