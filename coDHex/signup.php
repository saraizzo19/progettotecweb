<?php
// 1. Include il file che stabilisce la connessione al database coDHex (variabile $pdo)
require 'db_connect.php';

// 2. Controlla se la pagina è stata chiamata inviando il form (metodo POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Recupera i dati dai campi del form HTML.
    // '??': servono a evitare errori se un campo non viene inviato -> mette una stringa vuota.
    $nome = $_POST['name'] ?? '';
    $cognome = $_POST['surname'] ?? '';
    $data_nascita = $_POST['date'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $conferma_pwd = $_POST['confirmPassword'] ?? '';


    // 4. Controlla che nessun campo obbligatorio sia vuoto
    if (empty($nome) || empty($cognome) || empty($data_nascita) || empty($email) || empty($password)) {
        die("Errore: Tutti i campi sono obbligatori.");
    }

    // 5. Controlla che la password e la conferma siano identiche
    if ($password !== $conferma_pwd) {
        die("Errore: Le password non coincidono.");
    }


    // 6. Cripta la password, usando l'algoritmo sicuro predefinito di PHP, attraverso la funzione password_hash: è fondamentale per la sicurezza (GDPR e buone norme) non salvare le password leggibili
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 7. Query per inserire l'utente nell'apposita tabella all'interno del database, con i segnaposto (es. :nome) per evitare SQL Injection
    $sql = "INSERT INTO utenti (nome, cognome, data_nascita, email, password)
            VALUES (:nome, :cognome, :data_nascita, :email, :pass)";

    try {
        // 8. Prepara la query per l'esecuzione
        $stmt = $pdo->prepare($sql);

        // 9. Esegue la query associando ai segnaposto i dati reali (inclusa la password criptata)
        $stmt->execute([
            'nome' => $nome,
            'cognome' => $cognome,
            'data_nascita' => $data_nascita,
            'email' => $email,
            'pass' => $password_hash
        ]);

        // PER LA SESSIONE

        // 10. Recupera l'ID univoco appena creato per questo nuovo utente
        $nuovo_id = $pdo->lastInsertId();

        // 11. Avvia la sessione per mantenere l'utente loggato
        session_start();

        // 12. Salva i dati dell'utente nella sessione (così è già loggato senza dover rifare il login)
        $_SESSION['user_id'] = $nuovo_id;
        $_SESSION['user_nome'] = $nome;
        $_SESSION['user_cognome'] = $cognome;


        echo "Registrazione riuscita!";

    } catch(PDOException $e) {
        // 13. Gestione specifica dell'errore SQL "23000" (Duplicate entry): significa che nel DB la colonna 'email' è UNIQUE e l'utente sta usando una mail già presente.
        if ($e->getCode() == 23000) {
            echo "Errore: L'indirizzo email <b>$email</b> è già registrato su coDHex.";
        } else {
            // Gestione di altri errori generici del database
            echo "Errore del sistema: " . $e->getMessage();
        }
    }
}
?>