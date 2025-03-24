<?php
// Yhdistä tietokantaan
require_once '../../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Poistetaan kysymys tietokannasta
    $sql = "DELETE FROM questions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Ohjataan takaisin admin-sivulle
    header("Location: admin.php");
    exit();
} else {
    echo "<p>Kysymystä ei löytynyt!</p>";
}
?>
