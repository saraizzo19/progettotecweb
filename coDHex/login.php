<?php

// 1. Avvia una nuova sessione o ne riprende una esistente. Serve a memorizzare i dati dell'utente (come l'ID) sul server per riconoscerlo nelle pagine successive (es. area riservata).
session_start();

// 2. Include il file di connessione al database (che contiene la variabile $pdo)
require 'db_connect.php';

// 3. Controlla se il server ha ricevuto una richiesta di tipo POST (cioè se il form è stato inviato)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 4. Recupera i dati inviati dal form.
    $email = $_POST['email'] ?? '';  // ?? '': servono a evitare errori se il campo è vuoto (assegna una stringa vuota nel caso)
    $password = $_POST['password'] ?? '';

    // 5. Verifica base: se uno dei due campi è vuoto, ferma tutto e dai errore
    if (empty($email) || empty($password)) {
        die("Errore: Inserisci sia l'email che la password.");
    }

    // 6. Scrittura della query SQL -> usiamo :email come segnaposto per sicurezza (invece di inserire la variabile direttamente nella stringa SQL, evitiamo che gli hacker facciano SQL Injection)
    $sql = "SELECT * FROM utenti WHERE email = :email";

    try {
        // 7. Preparazione ed esecuzione della query tramite $pdo
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        // 8. Recupera la riga trovata nel database (se esiste)
        $user = $stmt->fetch(); // Prende i dati dell'utente (se esiste)

        /* 9. VERIFICA LOGIN
              Controlla due cose:
              - $user: abbiamo trovato un utente con quella email?
              - password_verify: la password scritta dall'utente coincide con l'hash criptato nel database? (Non controlla la password in chiaro (==) proprio perché la password è salvata cifrata all'interno del database)
        */
        if ($user && password_verify($password, $user['password'])) {

            // 10. LOGIN RIUSCITO: Salviamo i dati importanti nella SESSIONE, così il sito sa chi siamo nelle altre pagine
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_cognome'] = $user['cognome'];

            // Messaggio che verrà letto dal Javascript per il redirect
            echo "Login riuscito!";

        } else {
            // 11. LOGIN FALLITO: Email non trovata o password sbagliata
            echo "Errore: Credenziali non valide (Email o Password errati).";
        }

    } catch(PDOException $e) {
        // 12. Gestione errori del database (es. connessione persa)
        echo "Errore del sistema: " . $e->getMessage();
    }
}
?>