<?php
session_start();
require 'db_connect.php';

// 1. Controllo sicurezza: Verifica se l'utente è loggato -> se 'user_id' non esiste nella sessione, blocchiamo tutto.
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Codice HTTP non autorizzato
    die("Errore: Devi effettuare il login.");
}

// 2. Verifica se il form è stato inviato (metodo POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera l'ID dell'utente della sessione
    $uid = $_SESSION['user_id'];
    // Recupera il tipo di fonte
    $tipo = $_POST['sourceType'] ?? 'libro';

    // 3. Inizializzazione delle variabili -> impostiamo tutto a null per evitare errori (del tipo "undefined variable")
    $nome = $cognome = $titolo = $data = $genere = $formato = null;
    $citta = $editore = $pagine = $edizione = $isbn = null;
    $titolo_rivista = $volume = $fascicolo = $issn = null;
    $titolo_sito = $url = null;

    // LOGICA DI MAPPING
    // In base al tipo, leggiamo i campi con il suffisso giusto (-b per book, -a per article, -w per web)
    
    if ($tipo === 'libro') {
        $nome = $_POST['authorfname-b'] ?? '';
        $cognome = $_POST['authorlname-b'] ?? '';
        $titolo = $_POST['title-b'] ?? '';
        $citta = $_POST['city-b'] ?? '';
        $editore = $_POST['publisher-b'] ?? '';
        $data = $_POST['publishdate-b'] ?? null;
        $pagine = $_POST['pages-b'] ?? null;
        $edizione = $_POST['edition-b'] ?? null;
        $isbn = $_POST['isbn-b'] ?? '';
        $genere = $_POST['genre-b'] ?? ''; // Nota: nel tuo HTML manca il name="genre-b" nella select, aggiungilo!
        $formato = $_POST['citazione-b'] ?? 'apa';

    } elseif ($tipo === 'articolo') {
        $nome = $_POST['authorfname-a'] ?? '';
        $cognome = $_POST['authorlname-a'] ?? '';
        $titolo = $_POST['title-a'] ?? '';
        $titolo_rivista = $_POST['titleriv-a'] ?? '';
        $volume = $_POST['vol-a'] ?? null;
        $fascicolo = $_POST['fasc-a'] ?? null;
        $data = $_POST['publishdate-a'] ?? null;
        $issn = $_POST['issn-a'] ?? '';
        $genere = $_POST['genre-a'] ?? '';
        $formato = $_POST['citazione-a'] ?? 'apa';

    } elseif ($tipo === 'sito') {
        $nome = $_POST['authorfname-w'] ?? '';
        $cognome = $_POST['authorlname-w'] ?? '';
        $titolo = $_POST['title-w'] ?? ''; // Titolo risorsa
        $titolo_sito = $_POST['titlesite-w'] ?? '';
        $data = $_POST['publishdate-w'] ?? null;
        $url = $_POST['link-w'] ?? '';
        $genere = $_POST['genre-w'] ?? '';
        $formato = $_POST['citazione-w'] ?? 'apa';
    }

    // 4. Pulizia dei dati (Null Handling) -> se un campo numerico o data è vuoto, lo forziamo a NULL per non rompere il DB (darebbe errore cercando di inserire una stringa vuota in un numero);
    if (empty($data)) $data = null;
    if (empty($pagine)) $pagine = null;
    if (empty($volume)) $volume = null;
    if (empty($fascicolo)) $fascicolo = null;
    if (empty($edizione)) $edizione = null;

    // QUERY DI INSERIMENTO
    // Usiamo una sola tabella 'bibliografia' che contiene le colonne per TUTTI i tipi. Le colonne non usate per quel tipo resteranno NULL nel database.
    $sql = "INSERT INTO bibliografia 
            (utente_id, tipo, nome_autore, cognome_autore, titolo, data_pubblicazione, genere, formato_citazione,
             citta, editore, pagine, edizione, isbn,
             titolo_rivista, volume, fascicolo, issn,
             titolo_sito, url)
            VALUES 
            (:uid, :tipo, :nome, :cognome, :titolo, :data, :genere, :formato,
             :citta, :editore, :pagine, :edizione, :isbn,
             :titriv, :vol, :fasc, :issn,
             :titsito, :url)";

    // 5. Preparazione ed esecuzione sicura -> usiamo i prepared statements per evitare SQL Injection
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'uid' => $uid, 'tipo' => $tipo, 'nome' => $nome, 'cognome' => $cognome, 'titolo' => $titolo, 'data' => $data, 'genere' => $genere, 'formato' => $formato,
            'citta' => $citta, 'editore' => $editore, 'pagine' => $pagine, 'edizione' => $edizione, 'isbn' => $isbn,
            'titriv' => $titolo_rivista, 'vol' => $volume, 'fasc' => $fascicolo, 'issn' => $issn,
            'titsito' => $titolo_sito, 'url' => $url
        ]);

        echo "Fonte salvata con successo!";
    } catch (PDOException $e) {
        // 6. Gestione errori database
        http_response_code(500); // Errore interno del server
        echo "Errore Database: " . $e->getMessage();
    }
}
?>