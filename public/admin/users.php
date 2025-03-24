<?php
session_start();
require_once '../config.php';

// Tarkistetaan, onko käyttäjä kirjautunut sisään ja onko hän admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Haetaan kaikki käyttäjät tietokannasta
$query = "SELECT id, username, email, role FROM users";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Käyttäjät</title>
</head>
<body>

<h2>Käyttäjähallinta</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Käyttäjänimi</th>
        <th>Sähköposti</th>
        <th>Rooli</th>
        <th>Toiminnot</th>
    </tr>

    <?php
    // Näytetään kaikki käyttäjät
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['role'] . "</td>";
            echo "<td><a href='edit_user.php?id=" . $row['id'] . "'>Muokkaa</a> | <a href='delete_user.php?id=" . $row['id'] . "'>Poista</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Ei löytynyt käyttäjiä.</td></tr>";
    }
    ?>

</table>

<a href="admin.php">Takaisin hallintapaneeliin</a>

</body>
</html>
