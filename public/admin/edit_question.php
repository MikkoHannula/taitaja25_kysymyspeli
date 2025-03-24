<?php
// Yhdistä tietokantaan
require_once '../../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Haetaan kysymys tietokannasta
    $sql = "SELECT * FROM questions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $question = $result->fetch_assoc();

    if ($question) {
        // Lomakkeen käsittely
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $updated_question = $_POST['question'];
            $updated_answer = $_POST['correct_answer'];
            $updated_category = $_POST['category'];

            // Päivitetään tietokantaan
            $update_sql = "UPDATE questions SET question = ?, correct_answer = ?, category = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssi", $updated_question, $updated_answer, $updated_category, $id);
            $update_stmt->execute();

            // Ohjataan takaisin admin-sivulle
            header("Location: admin.php");
            exit();
        }
?>

<!-- Lomake kysymyksen muokkaamiseen -->
<h2>Muokkaa Kysymystä</h2>
<form action="" method="POST">
    <label for="question">Kysymys:</label><br>
    <input type="text" id="question" name="question" value="<?php echo $question['question']; ?>" required><br><br>
    
    <label for="correct_answer">Oikea Vastaus:</label><br>
    <input type="text" id="correct_answer" name="correct_answer" value="<?php echo $question['correct_answer']; ?>" required><br><br>
    
    <label for="category">Kategoria:</label><br>
    <select id="category" name="category" required>
        <option value="pasi" <?php echo ($question['category'] == 'pasi') ? 'selected' : ''; ?>>Pasi (HTML, CSS, PHP, JS)</option>
        <option value="pauli" <?php echo ($question['category'] == 'pauli') ? 'selected' : ''; ?>>Pauli (Python)</option>
    </select><br><br>

    <input type="submit" value="Päivitä">
</form>

<?php
    } else {
        echo "<p>Kysymystä ei löytynyt!</p>";
    }
}
?>
