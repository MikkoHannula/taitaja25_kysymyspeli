<?php
// Yhteys tietokantaan
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Tarkistetaan yhteys
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Haetaan tulokset
$sql_results = "SELECT users.username, results.score, results.created_at 
                FROM results 
                JOIN users ON results.username = users.username";
$result_results = $conn->query($sql_results);

$results = [];
if ($result_results->num_rows > 0) {
    while ($row = $result_results->fetch_assoc()) {
        $results[] = $row;
    }
}

// Palautetaan JSON
header('Content-Type: application/json');
echo json_encode($results);

$conn->close();
?>
