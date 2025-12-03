<?php
session_start();
session_destroy(); // Distrugge tutti i dati della sessione
header("Location: index.html"); // Rimanda alla home/login
exit;
?>