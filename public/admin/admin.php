<?php
session_start();
require_once "../../config.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$sql_users = "SELECT id, username, role FROM users";
$result_users = $conn->query($sql_users);

$category_filter = isset($_GET['category']) ? $_GET['category'] : 'Pasi';
$sql_questions = "SELECT id, question_text, option_a, option_b, option_c, option_d, correct_answer, category 
                  FROM questions WHERE category = ?";
$stmt = $conn->prepare($sql_questions);
$stmt->bind_param("s", $category_filter);
$stmt->execute();
$result_questions = $stmt->get_result();

if (!$result_questions) {
    die("Kysymysten haku epäonnistui: " . $conn->error);
}

if (isset($_GET['show_results'])) {
    $sql_results = "SELECT users.username, results.score, results.created_at 
            FROM results 
                JOIN users ON results.username = users.username";
    $result_results = $conn->query($sql_results);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kysymykset</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-buttons {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .admin-buttons button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 15px;
            margin: 5px 0;
            cursor: pointer;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .admin-buttons button:hover {
            background-color: #45a049;
        }
        .category-dropdown {
            padding: 8px;
            font-size: 14px;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .center-content {
            text-align: center;
            font-size: 1.5em;
            transition: opacity 0.5s ease-out;
            margin-top: -250px; /* Move content higher */
        }
        .hidden-content {
            display: none;
        }
        .question-list {
            font-size: 14px; /* Smaller text */
            margin-top: -40px; /* Move questions higher */
        }
        .question-item {
            margin-bottom: 5px;
            padding: 5px;
        }
        .correct-answer {
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body>

<h2>Hallinta</h2>

<p>Tervetuloa, <?php echo $_SESSION['username']; ?>! Sinulla on rooli: <?php echo $_SESSION['role']; ?></p>
<p><a href="../logout.php">Kirjaudu ulos</a></p>

<div class="admin-buttons">
    <button id="addQuestionBtn">Lisää kysymys</button>
    <button id="editQuestionBtn">Muokkaa kysymyksiä</button>
    <button id="showResultsBtn">Käyttäjien tulokset</button>
    <select class="category-dropdown" id="categoryDropdown">
        <option value="Pasi">Pasi</option>
        <option value="Pauli">Pauli</option>
    </select>
</div>

<div class="center-content">
    <h1 id="welcomeText">Welcome Sir.</h1>
    <div id="content" class="hidden-content"></div>
</div>

<script>
    function showContent(content) {
        document.getElementById('welcomeText').style.opacity = '0';
        setTimeout(function() {
            document.getElementById('content').innerHTML = content;
            document.getElementById('content').classList.remove('hidden-content');
            document.getElementById('welcomeText').style.display = 'none';
        }, 500);
    }

    document.getElementById('addQuestionBtn').onclick = function() {
        showContent('<h3>Lisää uusi kysymys</h3>' +
            '<form action="admin.php" method="POST">' +
            '<label for="question_text">Kysymys:</label><br>' +
            '<textarea name="question_text" required></textarea><br><br>' +
            '<label for="option_a">Vastaus A:</label><br>' +
            '<input type="text" name="option_a" required><br><br>' +
            '<label for="option_b">Vastaus B:</label><br>' +
            '<input type="text" name="option_b" required><br><br>' +
            '<label for="option_c">Vastaus C:</label><br>' +
            '<input type="text" name="option_c" required><br><br>' +
            '<label for="option_d">Vastaus D:</label><br>' +
            '<input type="text" name="option_d" required><br><br>' +
            '<label for="correct_answer">Oikea vastaus (A, B, C, D):</label><br>' +
            '<input type="text" name="correct_answer" required><br><br>' +
            '<label for="category">Kategoria:</label><br>' +
            '<select name="category" required>' +
            '<option value="Pasi">Pasi</option>' +
            '<option value="Pauli">Pauli</option>' +
            '</select><br><br>' +
            '<input type="submit" name="add_question" value="Lisää kysymys">' +
            '</form>');
    };

    document.getElementById('editQuestionBtn').onclick = function() {
    let content = '<h3>Muokkaa kysymyksiä</h3><div class="question-list">';

    <?php while ($row = $result_questions->fetch_assoc()) { ?>
        content += '<details class="question-box">' +
            '<summary><strong><?php echo $row['question_text']; ?></strong></summary>' +
            '<div class="question-content">' +
            '<p>A) <?php echo $row['option_a']; ?> <?php echo ($row['correct_answer'] === "A") ? "<span class=\'correct-answer\'>(Oikea vastaus)</span>" : ""; ?></p>' +
            '<p>B) <?php echo $row['option_b']; ?> <?php echo ($row['correct_answer'] === "B") ? "<span class=\'correct-answer\'>(Oikea vastaus)</span>" : ""; ?></p>' +
            '<p>C) <?php echo $row['option_c']; ?> <?php echo ($row['correct_answer'] === "C") ? "<span class=\'correct-answer\'>(Oikea vastaus)</span>" : ""; ?></p>' +
            '<p>D) <?php echo $row['option_d']; ?> <?php echo ($row['correct_answer'] === "D") ? "<span class=\'correct-answer\'>(Oikea vastaus)</span>" : ""; ?></p>' +
            '<a href="admin.php?delete_question=<?php echo $row['id']; ?>">Poista</a> | ' +
            '<a href="edit_question.php?id=<?php echo $row['id']; ?>">Muokkaa</a>' +
            '</div>' +
        '</details>';
    <?php } ?>

    content += '</div>';
    showContent(content);
    };



    document.getElementById('showResultsBtn').onclick = function() {
        showContent('<h3>Käyttäjien tulokset</h3>');
    };

    document.getElementById('categoryDropdown').onchange = function() {
        window.location.href = "admin.php?category=" + this.value;
    };
</script>

</body>
</html>
