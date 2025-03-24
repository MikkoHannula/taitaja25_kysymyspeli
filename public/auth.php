<?php
session_start();
require_once "../config.php"; // Otetaan yhteys tietokantaan

// Tarkistetaan, onko käyttäjä jo kirjautunut
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: admin/admin.php");
    exit();
}

// Kun lomake lähetetään
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Haetaan käyttäjä tietokannasta
    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Tarkistetaan löytyikö käyttäjä ja onko salasana oikein
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: admin/admin.php");
        exit();
    } else {
        echo "<p style='color: red;'>Väärä käyttäjätunnus tai salasana!</p>";
    }
}
?>
