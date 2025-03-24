<?php
session_start();
require_once '../config.php';

// Tarkistetaan, onko käyttäjä kirjautunut sisään ja onko hän admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Tarkistetaan, että käyttäjän ID on määritelty URL:ssa
if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

// Poistetaan käyttäjä tietokannasta
$user_id = $_GET['id'];
$delete_query = "DELETE FROM users WHERE id = $user_id";
$conn->query($delete_query);

header("Location: users.php");
exit();
