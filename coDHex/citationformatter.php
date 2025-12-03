<?php
class CitationFormatter {


    public static function format($dati) {
        $tipo = $dati['tipo'];       // libro, articolo, sito
        $stile = $dati['formato_citazione']; // apa, mla, chicago, ieee

        if ($tipo == 'libro') {
            return self::formatLibro($dati, $stile);
        } elseif ($tipo == 'articolo') {
            return self::formatArticolo($dati, $stile);
        } elseif ($tipo == 'sito') {
            return self::formatSito($dati, $stile);
        }
        return "Tipo sconosciuto";
    }

    // --- LOGICA PER I LIBRI ---
    private static function formatLibro($d, $stile) {
        $autore = $d['cognome_autore'] . ", " . substr($d['nome_autore'], 0, 1) . ".";
        $titolo = "<i>" . htmlspecialchars($d['titolo']) . "</i>";
        $anno = date('Y', strtotime($d['data_pubblicazione']));
        $editore = htmlspecialchars($d['editore']);
        $citta = htmlspecialchars($d['citta']);

        switch ($stile) {
            case 'apa':
                // Rossi, M. (2020). Titolo in corsivo. Editore.
                return "$autore ($anno). $titolo. $editore.";

            case 'mla':
                // Rossi, Mario. Titolo in corsivo. Editore, 2020.
                $autoreMla = $d['cognome_autore'] . ", " . $d['nome_autore'];
                return "$autoreMla. $titolo. $editore, $anno.";

            case 'ieee':
                // M. Rossi, "Titolo", Città: Editore, 2020.
                $autoreIeee = substr($d['nome_autore'], 0, 1) . ". " . $d['cognome_autore'];
                return "$autoreIeee, \"$titolo\", $citta: $editore, $anno.";

            case 'chicago':
                 // Rossi, Mario. Titolo in corsivo. Città: Editore, 2020.
                 $autoreChi = $d['cognome_autore'] . ", " . $d['nome_autore'];
                 return "$autoreChi. $titolo. $citta: $editore, $anno.";

            default: return "$autore ($anno). $titolo.";
        }
    }


    private static function formatArticolo($d, $stile) {
        $autore = $d['cognome_autore'] . ", " . substr($d['nome_autore'], 0, 1) . ".";
        $titoloArt = htmlspecialchars($d['titolo']);
        $rivista = "<i>" . htmlspecialchars($d['titolo_rivista']) . "</i>";
        $anno = date('Y', strtotime($d['data_pubblicazione']));
        $vol = $d['volume'];
        $fasc = $d['fascicolo'];

        switch ($stile) {
            case 'apa':
                // Rossi, M. (2020). Titolo articolo. Nome Rivista, 10(2).
                return "$autore ($anno). $titoloArt. $rivista, $vol($fasc).";

            case 'mla':
                // Rossi, Mario. "Titolo articolo." Nome Rivista, vol. 10, no. 2, 2020.
                $autoreMla = $d['cognome_autore'] . ", " . $d['nome_autore'];
                return "$autoreMla. \"$titoloArt.\" $rivista, vol. $vol, no. $fasc, $anno.";

            // ... puoi aggiungere chicago e ieee qui ...
            default: return "$autore. $titoloArt. $rivista ($anno).";
        }
    }


    private static function formatSito($d, $stile) {
        $autore = $d['cognome_autore'] . ", " . substr($d['nome_autore'], 0, 1) . ".";
        $titoloPagina = htmlspecialchars($d['titolo']); // Titolo della pagina specifica
        $nomeSito = "<i>" . htmlspecialchars($d['titolo_sito']) . "</i>";
        $url = "<a href='{$d['url']}' target='_blank'>{$d['url']}</a>";
        $anno = $d['data_pubblicazione'] ? date('Y', strtotime($d['data_pubblicazione'])) : 'n.d.';
        $dataCompleta = $d['data_pubblicazione'] ? date('d M. Y', strtotime($d['data_pubblicazione'])) : 'n.d.';

        switch ($stile) {
            case 'apa':
                 // Rossi, M. (2020). Titolo pagina. Nome Sito. URL
                return "$autore ($anno). $titoloPagina. $nomeSito. $url";

            case 'mla':
                // Rossi, Mario. "Titolo Pagina." Nome Sito, 20 Jan. 2020, URL.
                $autoreMla = $d['cognome_autore'] . ", " . $d['nome_autore'];
                return "$autoreMla. \"$titoloPagina.\" $nomeSito, $dataCompleta, $url.";

            default: return "$autore. $titoloPagina. $url";
        }
    }
}
?>