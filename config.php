<?php
// Tietokantayhteyden asetukset
$servername = "localhost";
$username = "root"; // Käyttäjätunnus
$password = ""; // Salasana, jos käytössä
$dbname = "taitaja_db"; // Tietokannan nimi

// Luo yhteys
$conn = new mysqli($servername, $username, $password, $dbname);

// Tarkistetaan yhteys
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}

// Merkkikoodaus
$conn->set_charset("utf8");

// Virheilmoitukset päälle kehityskäyttöön
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
