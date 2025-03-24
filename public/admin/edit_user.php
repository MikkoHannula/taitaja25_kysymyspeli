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

// Haetaan käyttäjän tiedot tietokannasta
$user_id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo "Käyttäjää ei löytynyt.";
    exit();
}

$user = $result->fetch_assoc();

// Muokataan käyttäjää
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Päivitetään käyttäjän tiedot tietokantaan
    $update_query = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssi", $username, $email, $role, $user_id);
    $stmt->execute();

    header("Location: users.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Käyttäjän muokkaaminen</title>
</head>
<body>

<h2>Muokkaa käyttäjää</h2>

<form action="edit_user.php?id=<?php echo $user_id; ?>" method="post">
    <label for="username">Käyttäjänimi:</label>
    <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>

    <label for="email">Sähköposti:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>

    <label for="role">Rooli:</label>
    <select name="role">
        <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>Käyttäjä</option>
        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
    </select><br>

    <input type="submit" value="Päivitä käyttäjä">
</form>

<a href="users.php">Takaisin käyttäjien hallintaan</a>

</body>
</html>
