<?php
// Avvia o riprende la sessione esistente per accedere alle variabili superglobali $_SESSION
session_start();


// Verifica se l'utente è loggato controllando l'esistenza dell'ID utente nella sessione.
if (!isset($_SESSION['user_id'])) {
    // Se la chiave 'user_id' non esiste, reindirizza l'utente alla pagina di registrazione
    header("Location: signup.html");
    exit; // Interrompe lo script per evitare che il resto della pagina venga caricato
}

// Se $_SESSION['user_nome'] esiste lo assegna a $nome, altrimenti usa 'Utente' come default.
$nome = $_SESSION['user_nome'] ?? 'Utente';
$cognome = $_SESSION['user_cognome'] ?? '';
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coDHex - Pagina riservata</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* TEMPLATE DA CODEPEN MODIFICATO IN BASE ALLO STILE PREFERITO */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }


        :root {
            --primary-color: #B56952;
            --secondary-color: #DDBEB9;
            --bg-light: #FDFBF4;
            --text-dark: #333;
            --shadow-light: rgba(0,0,0,0.05);
            --shadow-medium: rgba(181, 105, 82, 0.2);
            --border-radius-lg: 20px;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }


        h1 { font-size: 3em; line-height: 1.1; margin-top: 40px; }


        h2 {
            font-size: 2.5em;
            position: relative;
            display: inline-block;
        }


        h2::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 60px;
            height: 5px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }


        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px; /* Spazio tra le card */
            margin-top: 20px;
        }


        .card-link {
            text-decoration: none;
            display: block;
            color: inherit;
        }


        .card {
            background-color: #fff;
            padding: 50px 30px;
            border-radius: var(--border-radius-lg);
            box-shadow: 0 5px 20px var(--shadow-light);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .card-link:hover .card {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px var(--shadow-medium);
        }

        .card .icon {
            font-size: 3.5em;
            color: var(--primary-color);
            margin-bottom: 25px;
        }

        @media (max-width: 768px) {
            h1 { font-size: 2.5em; text-align: center; }
            h2::after { left: 50%; transform: translateX(-50%); }
        }
    </style>
</head>
<body>

<br> <br> <br> <br>
<!-- Messaggio di benvenuto nell'area riservata-->
<div class="container">
    <h1 align="center">Benvenutə nella tua Area Riservata</h1>
    <p style="font-size: 1.1em;" align="center">Da qui potrai accedere alla home page e alla pagina di generazione delle fonti</p>
</div>

<br>
<!-- Sezione con diverse card che permettono la navigazione tra le diverse pagine, come index. generatorefonti e libreria,
permettendo anche il logout, collegato al logout.php-->
<section class="section">
    <div class="container">

        <div class="cards-grid">

            <a href="index.html" class="card-link">
                <div class="card">
                    <i class="fas fa-home icon"></i>
                    <h3>Home</h3>
                </div>
            </a>

            <a href="generatorefonti.html" class="card-link">
                <div class="card">
                    <i class="fas fa-sync icon"></i>
                    <h3>Generazione delle fonti</h3>
                </div>
            </a>

            <a href="libreria.php" class="card-link">
                <div class="card">
                    <i class="fas fa-book icon"></i>
                    <h3>La mia libreria</h3>
                </div>
            </a>

            <br> <br>

            <a href="logout.php" class="card-link">
                <div class="card">
                    <i class="fas fa-sign-out-alt icon"></i>
                    <h3>Disconnetti</h3>
                </div>
            </a>

        </div>
    </div>
</section>

</body>
</html>