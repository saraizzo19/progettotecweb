<?php
class CitationFormatter {
       // Classe che gestisce la formattazione delle citazioni: è una classe "statica" da usare direttamente senza dover scrivere "new CitationFormatter()" ogni volta.

    // Funzione principale: riceve tutti i dati e decide quale funzione specifica chiamare
    public static function format($dati) {
        $tipo = $dati['tipo'];       // Es: libro, articolo, sito
        $stile = $dati['formato_citazione']; // Es: apa, mla, chicago

        // Smistamento in base al tipo di fonte -> chiamo la funzione dedicata 'format'
        if ($tipo == 'libro') {
            return self::formatLibro($dati, $stile);
        } elseif ($tipo == 'articolo') {
            return self::formatArticolo($dati, $stile);
        } elseif ($tipo == 'sito') {
            return self::formatSito($dati, $stile);
        }
        return "Tipo sconosciuto";
    }

    // LOGICA PER I LIBRI
    private static function formatLibro($d, $stile) { // Privata perché viene usata solo internamente a questa classe
        // Preparazione dati comuni
        // substr(..., 0, 1) serve a prendere solo l'iniziale del nome (Es. Mario -> M.)
        $autore = $d['cognome_autore'] . ", " . substr($d['nome_autore'], 0, 1) . ".";
        // htmlspecialchars serve per sicurezza: evita che caratteri speciali rompano l'HTML
        $titolo = "<i>" . htmlspecialchars($d['titolo']) . "</i>";
        // Estrae solo l'anno dalla data completa (YYYY-MM-DD -> YYYY)
        $anno = date('Y', strtotime($d['data_pubblicazione']));
        $editore = htmlspecialchars($d['editore']);
        $citta = htmlspecialchars($d['citta']);
        // substr: per ottenere le iniziali del nome e concatenare le stringhe per ottenere la citazione finale
        // htmlspecialchars: su tutti i dati inseriti dall'utente per evitare che codice malevolo o caratteri strani rompano la pagina HTML


        // Switch: cambia l'ordine e la punteggiatura in base allo stile scelto
        switch ($stile) {
            case 'apa':
                // Struttura APA: Autore (Anno). Titolo. Editore.
                return "$autore ($anno). $titolo. $editore.";

            case 'mla':
                // Struttura MLA: Autore completo. Titolo. Editore, Anno.
                $autoreMla = $d['cognome_autore'] . ", " . $d['nome_autore'];
                return "$autoreMla. $titolo. $editore, $anno.";

            case 'ieee':
                // Struttura IEEE: Iniziale. Cognome, "Titolo", Città: Editore, Anno.
                $autoreIeee = substr($d['nome_autore'], 0, 1) . ". " . $d['cognome_autore'];
                return "$autoreIeee, \"$titolo\", $citta: $editore, $anno.";

            case 'chicago':
                 // // Struttura Chicago: Autore. Titolo. Città: Editore, Anno.
                 $autoreChi = $d['cognome_autore'] . ", " . $d['nome_autore'];
                 return "$autoreChi. $titolo. $citta: $editore, $anno.";

            default: return "$autore ($anno). $titolo.";
        }
    }


    private static function formatArticolo($d, $stile) {
        // Logica simile ai libri, ma con campi specifici per le riviste
        $autore = $d['cognome_autore'] . ", " . substr($d['nome_autore'], 0, 1) . ".";
        $titoloArt = htmlspecialchars($d['titolo']);
        $rivista = "<i>" . htmlspecialchars($d['titolo_rivista']) . "</i>";
        $anno = date('Y', strtotime($d['data_pubblicazione']));
        $vol = $d['volume'];
        $fasc = $d['fascicolo'];

        switch ($stile) {
            case 'apa':
                // Include volume e fascicolo tra parentesi: 10(2)
                return "$autore ($anno). $titoloArt. $rivista, $vol($fasc).";

            case 'mla':
                // Include "vol." e "no." espliciti
                $autoreMla = $d['cognome_autore'] . ", " . $d['nome_autore'];
                return "$autoreMla. \"$titoloArt.\" $rivista, vol. $vol, no. $fasc, $anno.";

            // ... puoi aggiungere chicago e ieee qui ...
            default: return "$autore. $titoloArt. $rivista ($anno).";
        }
    }


    private static function formatSito($d, $stile) {
        $autore = $d['cognome_autore'] . ", " . substr($d['nome_autore'], 0, 1) . ".";
        $titoloPagina = htmlspecialchars($d['titolo']);
        $nomeSito = "<i>" . htmlspecialchars($d['titolo_sito']) . "</i>";
        // Creiamo un link cliccabile per l'URL
        $url = "<a href='{$d['url']}' target='_blank'>{$d['url']}</a>";
        // Gestione data: se manca, scriviamo 'n.d' (non disponibile/no data) -> operatore ternario: condizione ? vero : falso
        $anno = $d['data_pubblicazione'] ? date('Y', strtotime($d['data_pubblicazione'])) : 'n.d.';
        $dataCompleta = $d['data_pubblicazione'] ? date('d M. Y', strtotime($d['data_pubblicazione'])) : 'n.d.';

        switch ($stile) {
            case 'apa':
                 // Rossi, M. (2020). Titolo pagina. Nome Sito. URL
                return "$autore ($anno). $titoloPagina. $nomeSito. $url";

            case 'mla':
                // Richiede la data completa (Giorno Mese Anno)
                $autoreMla = $d['cognome_autore'] . ", " . $d['nome_autore'];
                return "$autoreMla. \"$titoloPagina.\" $nomeSito, $dataCompleta, $url.";

            default: return "$autore. $titoloPagina. $url";
        }
    }
}
?>