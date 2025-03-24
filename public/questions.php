<?php
session_start();
require_once "../config.php";

// Onko käyttäjä kirjautunut?
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Onko kategoria valittu?
if (!isset($_GET['category']) || !in_array($_GET['category'], ['pasi', 'pauli'])) {
    echo "<p style='color: red;'>Virheellinen kategoria!</p>";
    exit();
}

$category = $_GET['category'];

// Haetaan kysymykset kategorian mukaan
$sql = "SELECT id, question FROM questions WHERE category = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kysymykset</title>
</head>
<body>
    <h2>Kysymykset kategoriassa: <?= htmlspecialchars($category) ?></h2>
    <form action="process_answers.php" method="post">
        <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
        <?php foreach ($questions as $question): ?>
            <p><?= htmlspecialchars($question['question']) ?></p>
            <input type="text" name="answers[<?= $question['id'] ?>]" required>
        <?php endforeach; ?>
        <br>
        <button type="submit">Lähetä vastaukset</button>
    </form>
</body>
</html>
