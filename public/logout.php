<?php
session_start();
session_unset(); // Poistaa kaikki sessiomuuttujat
session_destroy(); // Tuhoaa session
header("Location: login.php"); // Ohjaa takaisin kirjautumissivulle
exit();
?>
