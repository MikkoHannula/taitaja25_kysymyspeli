<?php
// Yhteys tietokantaan
require_once "../../config.php";

// Käyttäjätiedot (voit vaihtaa nämä arvoiksi, jotka haluat lisätä)
$username = "Pasi";
$email = "pasi@example.com";
$password = "Tt25";  // Salasana, joka tulee hashata ennen tallentamista
$role = "admin";  // Käyttäjän rooli

// Luo hashattu salasana
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL-lause käyttäjän lisäämiseksi
$sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

// Suorita SQL-lause
if ($stmt->execute()) {
    echo "Käyttäjä lisätty onnistuneesti!";
} else {
    echo "Virhe käyttäjän lisäämisessä: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
