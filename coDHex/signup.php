<?php
// Colleghiamo il database
require 'db_connect.php';

// Verifichiamo che la pagina sia stata chiamata dal pulsante di invio
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- 1. RECUPERO DATI DAL FORM ---
    // $_POST['...'] deve contenere il "name" che hai messo nell'HTML
    $nome = $_POST['name'] ?? '';
    $cognome = $_POST['surname'] ?? '';
    $data_nascita = $_POST['date'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $conferma_pwd = $_POST['confirmPassword'] ?? '';

    // --- 2. CONTROLLI DI SICUREZZA ---

    // Controlliamo se i campi essenziali sono vuoti
    if (empty($nome) || empty($cognome) || empty($email) || empty($password)) {
        die("Errore: Compila tutti i campi obbligatori.");
    }

    // Controlliamo se le password coincidono
    if ($password !== $conferma_pwd) {
        die("Errore: Le due password inserite non sono uguali.");
    }

    // --- 3. SALVATAGGIO NEL DATABASE ---

    // Criptiamo la password per sicurezza
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Prepariamo la query SQL
    // IMPORTANTE: I nomi dentro "INSERT INTO utenti (...)" devono essere le colonne del DB
    $sql = "INSERT INTO utenti (nome, cognome, data_nascita, email, password)
            VALUES (:nome, :cognome, :data_nascita, :email, :pass)";

    try {
        $stmt = $pdo->prepare($sql);

        // Eseguiamo l'inserimento
        $stmt->execute([
            'nome' => $nome,
            'cognome' => $cognome,
            'data_nascita' => $data_nascita,
            'email' => $email,
            'pass' => $password_hash
        ]);

        echo "Registrazione completata con successo! Benvenuto " . htmlspecialchars($nome);

    } catch(PDOException $e) {
        // Codice 23000 significa "Violazione vincolo unique" (email già esistente)
        if ($e->getCode() == 23000) {
            echo "Errore: L'indirizzo email $email è già registrato.";
        } else {
            echo "Errore generico del database: " . $e->getMessage();
        }
    }
}
?>