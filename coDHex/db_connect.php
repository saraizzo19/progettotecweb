<?php
$host = 'localhost';
$db   = 'codhex';    // Il nome del tuo database
$user = 'root';      // Utente standard XAMPP
$pass = '';          // Password standard XAMPP (vuota)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Se la connessione fallisce, ferma tutto e mostra l'errore
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>