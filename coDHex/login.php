<?php

session_start();

require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        die("Errore: Inserisci sia l'email che la password.");
    }

    $sql = "SELECT * FROM utenti WHERE email = :email";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(); // Prende i dati dell'utente (se esiste)


        if ($user && password_verify($password, $user['password'])) {


            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_cognome'] = $user['cognome'];


            echo "Login riuscito!";

        } else {

            echo "Errore: Credenziali non valide (Email o Password errati).";
        }

    } catch(PDOException $e) {
        echo "Errore del sistema: " . $e->getMessage();
    }
}
?>