<?php
session_start();
session_destroy(); // Distrugge tutti i dati della sessione. Avevamo riscontrato un problema riguardo le sessioni non chiuse
header("Location: index.html"); // Rimanda alla landing page
exit;
?>