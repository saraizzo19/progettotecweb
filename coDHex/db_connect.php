<?php
// Codice per la creazione della connessione con il db, configurando parametri
$host = 'localhost';
$db   = 'codhex';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

//Costruzione del DSN (data source name), specifica mysql, host e nome del db con il set di caratteri
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
//Attiva segnalazione di errori, importa fetch come array associativo, disabilita emulazione degli statement preparati, usando i nativi
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
// apre la connessione creando un'istanza. In caso di errore, cattura l'eccezione e la rilancia
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {

    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
