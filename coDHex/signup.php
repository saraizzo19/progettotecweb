<?php
// Connessione al db coDHex
require 'db_connect.php';

// Se form è inviato correttamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $nome = $_POST['name'] ?? '';
    $cognome = $_POST['surname'] ?? '';
    $data_nascita = $_POST['date'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $conferma_pwd = $_POST['confirmPassword'] ?? '';


    // Controllo campi vuoti
    if (empty($nome) || empty($cognome) || empty($data_nascita) || empty($email) || empty($password)) {
        die("Errore: Tutti i campi sono obbligatori.");
    }

    // Controllo password uguali
    if ($password !== $conferma_pwd) {
        die("Errore: Le password non coincidono.");
    }



    // Funzione password_hash per criptare la password e non renderla leggibile al db
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Query per inserire nel db l'utente nell'apposita tabella
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

        //Per la sessione

        // Recupero dell'ultimo id inserito
        $nuovo_id = $pdo->lastInsertId();

        // Avvio della sessione con session_start
        session_start();


        $_SESSION['user_id'] = $nuovo_id;
        $_SESSION['user_nome'] = $nome;
        $_SESSION['user_cognome'] = $cognome;


        echo "Registrazione riuscita!";

    } catch(PDOException $e) {
        // Gestione errore per una mail duplicata
        if ($e->getCode() == 23000) {
            echo "Errore: L'indirizzo email <b>$email</b> è già registrato su coDHex.";
        } else {
            echo "Errore del sistema: " . $e->getMessage();
        }
    }
}
?>