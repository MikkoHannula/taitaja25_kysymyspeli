<?php
require_once "../../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_text = $_POST['question_text'];

    $sql = "INSERT INTO questions (question_text) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $question_text);
    $stmt->execute();
    
    header("Location: admin.php");
    exit();
}
?>
