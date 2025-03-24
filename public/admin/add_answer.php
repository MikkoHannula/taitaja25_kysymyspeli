<?php
require_once "../../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_id = $_POST['question_id'];
    $user_answer = $_POST['user_answer'];
    $correct = $_POST['correct'];

    $sql = "INSERT INTO answers (question_id, user_answer, correct) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $question_id, $user_answer, $correct);
    $stmt->execute();
    
    header("Location: admin.php");
    exit();
}
?>
