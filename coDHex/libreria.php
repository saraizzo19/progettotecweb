<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Se non sei loggato, via al login
    exit;
}


require 'db_connect.php';
require 'citationformatter.php';


$uid = $_SESSION['user_id'];


$sql = "SELECT * FROM bibliografia WHERE utente_id = :uid ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['uid' => $uid]);
$citazioni = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La mia Libreria - coDHex</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>

        :root {
            --bg-color: #FDFBF4;
            --card-bg: #EBE7DE;
            --primary-color: #B56952;
            --text-dark: #333;
            --text-muted: #666;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        h1 { font-weight: 600; color: var(--primary-color); }

        .btn-back {
            text-decoration: none;
            color: var(--text-dark);
            border: 1px solid var(--text-dark);
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 0.9em;
            transition: 0.2s;
        }
        .btn-back:hover { background-color: var(--text-dark); color: #fff; }

        /* LISTA DELLE CITAZIONI */
        .citation-card {
            background-color: #fff;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-left: 5px solid var(--primary-color);
            position: relative;
        }

        .citation-text {
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 15px;
            padding-right: 40px; /* Spazio per le icone a destra */
        }

        /* Badge (etichette tipo APA, LIBRO, ecc) */
        .badges {
            display: flex;
            gap: 10px;
            font-size: 0.75em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            background-color: #eee;
            color: #555;
        }

        .badge.style { background-color: #e3f2fd; color: #1565c0; } /* Azzurro per lo stile */
        .badge.type { background-color: #fce4ec; color: #c2185b; }  /* Rosa per il tipo */

        /* Stato Vuoto */
        .empty-state {
            text-align: center;
            padding: 50px;
            color: var(--text-muted);
        }
        .btn-add {
            display: inline-block;
            margin-top: 15px;
            background-color: var(--primary-color);
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>La tua bibliografia</h1>
        <a href="pagina_riservata.php" class="btn-back">← Torna alla pagina riservata</a>
    </header>

    <?php if (count($citazioni) > 0): ?>

        <div class="citations-list">
            <?php foreach ($citazioni as $riga): ?>
                <div class="citation-card">
                    <div class="citation-text">
                        <?php echo citationformatter::format($riga); ?>
                    </div>

                    <div class="badges">
                        <span class="badge style"><?php echo htmlspecialchars($riga['formato_citazione']); ?></span>
                        <span class="badge type"><?php echo htmlspecialchars($riga['tipo']); ?></span>
                        <span class="badge date">Aggiunto il <?php echo date('d/m/Y', strtotime($riga['created_at'])); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>

        <div class="empty-state">
            <h3>Non hai ancora salvato nessuna fonte.</h3>
            <p>Inizia subito a costruire la tua bibliografia.</p>
            <a href="generatorefonti.html" class="btn-add">Aggiungi la prima fonte</a>
        </div>

    <?php endif; ?>

</div>

</body>
</html>