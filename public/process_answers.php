<?php
session_start();
require_once "../config.php"; // Tietokantayhteys

// Tarkistetaan, onko käyttäjä kirjautunut
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Tarkistetaan, onko vastauksia lähetetty
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['answers'])) {
    echo "<p style='color: red;'>Virheellinen lomaketietojen lähetys.</p>";
    exit();
}

$category = $_POST['category'];
$answers = $_POST['answers'];
$username = $_SESSION['username'];
$score = 0;
$totalQuestions = count($answers);

// Haetaan oikeat vastaukset
$sql = "SELECT id, correct_answer FROM questions WHERE category = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
$correctAnswers = [];
while ($row = $result->fetch_assoc()) {
    $correctAnswers[$row['id']] = $row['correct_answer'];
}

// Lasketaan pisteet
foreach ($answers as $question_id => $user_answer) {
    if (isset($correctAnswers[$question_id]) && strtolower(trim($user_answer)) === strtolower(trim($correctAnswers[$question_id]))) {
        $score++;
    }
}

// Tallennetaan tulos tietokantaan
$sql = "INSERT INTO answers (username, category, score, total_questions, created_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $username, $category, $score, $totalQuestions);
$stmt->execute();

echo "<h2>Testin tulokset</h2>";
echo "<p>Oikein: $score / $totalQuestions</p>";
echo "<a href='questions.php?category=" . htmlspecialchars($category) . "'>Kokeile uudelleen</a>";
?>
