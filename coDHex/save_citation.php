<?php
session_start();
require 'db_connect.php';

// 1. Controllo sicurezza: Utente loggato?
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Non autorizzato
    die("Errore: Devi effettuare il login.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $uid = $_SESSION['user_id'];
    // Recuperiamo il tipo (che invieremo via JS)
    $tipo = $_POST['sourceType'] ?? 'libro';

    // Inizializziamo le variabili vuote
    $nome = $cognome = $titolo = $data = $genere = $formato = null;
    $citta = $editore = $pagine = $edizione = $isbn = null;
    $titolo_rivista = $volume = $fascicolo = $issn = null;
    $titolo_sito = $url = null;

    // --- LOGICA DI MAPPING ---
    // In base al tipo, leggiamo i campi con il suffisso giusto (-b, -a, -w)
    
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

    // Se la data arriva vuota, la settiamo a NULL per non rompere il DB
    if (empty($data)) $data = null;
    if (empty($pagine)) $pagine = null;
    if (empty($volume)) $volume = null;
    if (empty($fascicolo)) $fascicolo = null;
    if (empty($edizione)) $edizione = null;

    // --- QUERY DI INSERIMENTO ---
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
        http_response_code(500);
        echo "Errore Database: " . $e->getMessage();
    }
}
?>