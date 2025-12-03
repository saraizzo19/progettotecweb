<?php
// 1. Includiamo la connessione al database
require 'db_connect.php';

// 2. Controlliamo se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- RECUPERO DATI (Mapping HTML -> PHP) ---
    $nome = $_POST['name'] ?? '';
    $cognome = $_POST['surname'] ?? '';
    $data_nascita = $_POST['date'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $conferma_pwd = $_POST['confirmPassword'] ?? '';

    // --- VALIDAZIONE ---

    // Controllo campi vuoti
    if (empty($nome) || empty($cognome) || empty($data_nascita) || empty($email) || empty($password)) {
        die("Errore: Tutti i campi sono obbligatori.");
    }

    // Controllo password uguali
    if ($password !== $conferma_pwd) {
        die("Errore: Le password non coincidono.");
    }

    // --- SALVATAGGIO NEL DB ---

    // Criptiamo la password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Prepariamo la query
    $sql = "INSERT INTO utenti (nome, cognome, data_nascita, email, password)
            VALUES (:nome, :cognome, :data_nascita, :email, :pass)";

    try {
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'nome' => $nome,
            'cognome' => $cognome,
            'data_nascita' => $data_nascita,
            'email' => $email,
            'pass' => $password_hash
        ]);

        // ==========================================
        //  NUOVA PARTE: CREAZIONE SESSIONE (Auto-Login)
        // ==========================================

        // 1. Recuperiamo l'ID che il database ha appena assegnato a questo utente
        $nuovo_id = $pdo->lastInsertId();

        // 2. Avviamo la sessione PHP
        session_start();

        // 3. Inseriamo i dati nel "biglietto" della sessione
        $_SESSION['user_id'] = $nuovo_id;
        $_SESSION['user_nome'] = $nome;
        $_SESSION['user_cognome'] = $cognome;

        // ==========================================

        // Rispondiamo con un messaggio semplice.
        // Il tuo JavaScript vedrà che non c'è scritto "Errore" e farà il redirect.
        echo "Registrazione riuscita!";

    } catch(PDOException $e) {
        // Gestione errore Email Duplicata (codice 23000)
        if ($e->getCode() == 23000) {
            echo "Errore: L'indirizzo email <b>$email</b> è già registrato su coDHex.";
        } else {
            echo "Errore del sistema: " . $e->getMessage();
        }
    }
}
?>