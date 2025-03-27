<?php
require_once '../config.php'; // Ladataan tietokantayhteys


// Testataan tietokantayhteyttä
if (!$conn) {
    die("Tietokantayhteyden muodostaminen epäonnistui.");
}

// Haetaan opettajat
$sql = "SELECT id, name FROM teachers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Opettajat:</h2><ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row["name"]) . "</li>";
    }
    echo "</ul>";
} else {
    echo "Ei opettajia tietokannassa.";
}

// Suljetaan yhteys lopuksi
$conn->close();
?>
<a href="how_to_play.php" class="btn">Miten pelataan?</a>
