<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirjautuminen</title>
</head>
<body>
    <h2>Kirjautuminen</h2>
    <form method="POST" action="auth.php">
        <label for="username">Käyttäjätunnus:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Salasana:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Kirjaudu">
    </form>
</body>
</html>
