<?php
$password = 'Tt25';  // Syötä käyttäjän salasana
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password;
?>
