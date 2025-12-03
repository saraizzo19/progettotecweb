<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signup.html");
    exit;
}

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
        /* --- RESET E VARIABILI --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary-color: #B56952; /* Terracotta principale */
            --secondary-color: #DDBEB9; /* Rosato chiaro */
            --bg-light: #FDFBF4; /* Sfondo pagina crema chiaro */
            --bg-medium: #EBE7DE; /* Sfondo sezioni beige */
            --text-dark: #333;
            --text-light: #555;
            --text-white: #FFFFFF;
            --shadow-light: rgba(0,0,0,0.05);
            --shadow-medium: rgba(181, 105, 82, 0.2);
            --border-radius-lg: 20px;
            --border-radius-md: 12px;
            --padding-section: 60px 0;
        }

        body {
            background-color: var(--bg-light); /* Sfondo uniforme come nell'immagine */
            color: var(--text-dark);
            line-height: 1.7;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* --- TYPOGRAPHY --- */
        h1, h2, h3 {
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 20px;
        }

        h1 { font-size: 3em; line-height: 1.1; margin-top: 40px; }

        /* Titolo sezione con sottolineatura */
        h2 {
            font-size: 2.5em;
            margin-bottom: 40px;
            position: relative;
            display: inline-block;
            color: var(--text-dark);
        }

        h2::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 60px; /* Lunghezza della linea */
            height: 5px; /* Spessore della linea */
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        h3 {
            font-size: 1.2em; /* Dimensione testo card */
            margin-top: 15px;
            margin-bottom: 0;
            color: var(--primary-color);
        }

        p { margin-bottom: 15px; color: var(--text-light); }

        /* --- CARDS GRID --- */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }

        /* Link che avvolge la card per renderla cliccabile */
        .card-link {
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .card {
            background-color: #fff; /* Sfondo bianco della card */
            padding: 50px 30px;
            border-radius: var(--border-radius-lg);
            box-shadow: 0 5px 20px var(--shadow-light); /* Ombra leggera */
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(0,0,0,0.02);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Effetto Hover sulla card */
        .card-link:hover .card {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px var(--shadow-medium);
        }

        .card .icon {
            font-size: 3.5em; /* Dimensione Icona */
            color: var(--primary-color);
            margin-bottom: 25px;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            h1 { font-size: 2.5em; text-align: center; }
            h2 { font-size: 2em; display: block; text-align: center; }
            h2::after { left: 50%; transform: translateX(-50%); } /* Centra la linea su mobile */
            .card { padding: 30px 20px; }
        }
    </style>
</head>
<body>


<br> <br> <br> <br>


<div class="container">
    <h1 align="center">Benvenuto nella tua Area Riservata</h1>
    <p style="font-size: 1.1em;" align="center">Da qui potrai accedere alla home page e alla pagina di generazione delle fonti</p>
</div>

<br>

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

        </div>
    </div>
</section>

</body>
</html>