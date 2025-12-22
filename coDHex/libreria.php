<?php
// Avvia la sessione per poter leggere i dati dell'utente loggato
session_start();

// Verifica se l'utente è autenticato controllando la presenza di 'user_id' nella sessione
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Reindirizza al login se non autenticato
    exit; // Interrompe l'esecuzione dello script immediatamente
}

// Include il file per la connessione al database ($pdo)
require 'db_connect.php';
// Include la classe helper che si occupa di formattare le stringhe in base al tipo di formattazione decisa
require 'citationformatter.php';

$uid = $_SESSION['user_id']; // Recupera l'ID dell'utente dalla sessione corrente

// Con la query sql, vengono selezionate tutte le righe dalla tabella 'bibliografia' associate all'utente loggato
// Ordine dalla più recente alla più vecchia (DESC)
$sql = "SELECT * FROM bibliografia WHERE utente_id = :uid ORDER BY created_at DESC";

// Prepara la query per prevenire SQL Injection
$stmt = $pdo->prepare($sql);

// Esegue la query passando l'ID utente come parametro sicuro
$stmt->execute(['uid' => $uid]);

// Recupera tutti i risultati in un array associativo
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
        /* TEMPLATE DA CODEPEN MODIFICATO IN BASE ALLO STILE PREFERITO */
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
            padding-right: 40px;
        }


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


        .badge.style { background-color: #e3f2fd; color: #1565c0; }
        .badge.type { background-color: #fce4ec; color: #c2185b; }


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
<!--div che contiene h1 e href per tornare alla pagina riservata-->
<div class="container">
    <header>
        <h1>La tua bibliografia</h1>
        <a href="pagina_riservata.php" class="btn-back">← Torna alla pagina riservata</a>
    </header>

    <?php
    // Verifica se l'array $citazioni contiene elementi (count > 0)
    if (count($citazioni) > 0):
    ?>

        <div class="citations-list">
            <?php
            // Iterazione attraverso ogni riga recuperata dal database
            foreach ($citazioni as $riga):
            ?>
                <div class="citation-card">
                    <div class="citation-text">
                        <?php
                        // Richiama la classe helper per formattare la stringa secondo il formato di citazione
                        echo citationformatter::format($riga);
                        ?>
                    </div>

                    <div class="badges">
                        <span class="badge style"><?php echo htmlspecialchars($riga['formato_citazione']); ?></span>

                        <span class="badge type"><?php echo htmlspecialchars($riga['tipo']); ?></span>

                        <span class="badge date">Aggiunto il <?php echo date('d/m/Y', strtotime($riga['created_at'])); ?></span>
                    </div>
                </div>
            <?php endforeach; // Fine del ciclo delle citazioni ?>
        </div>

    <?php else: ?>
        <div class="empty-state">
            <h3>Non hai ancora salvato nessuna fonte.</h3>
            <p>Inizia subito a costruire la tua bibliografia.</p>
            <a href="generatorefonti.html" class="btn-add">Aggiungi la prima fonte</a>
        </div>

    <?php endif; // Fine del controllo if/else ?>

    </div>

</body>
</html>