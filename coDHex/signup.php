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

    // Controllo campi vuoti (sicurezza extra oltre al 'required' HTML)
    if (empty($nome) || empty($cognome) || empty($data_nascita) || empty($email) || empty($password)) {
        die("Errore: Tutti i campi sono obbligatori.");
    }

    // Controllo password uguali
    if ($password !== $conferma_pwd) {
        die("Errore: Le password non coincidono. <a href='javascript:history.back()'>Torna indietro</a>");
    }

    // --- SALVATAGGIO NEL DB ---

    // Criptiamo la password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Prepariamo la query.
    // NOTA BENE:
    // A sinistra (INSERT INTO...) ci sono i nomi delle colonne della tabella MySQL (italiano).
    // A destra (VALUES :...) ci sono i segnaposto che riempiremo con i dati.
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

        // --- SUCCESSO ---
        echo "<h2 style='color: green; text-align: center; margin-top: 50px;'>Registrazione riuscita!</h2>";
        echo "<p style='text-align: center;'>Benvenuto su coDHex, $nome. <br> <a href='index.html'>Torna alla Home</a></p>";

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