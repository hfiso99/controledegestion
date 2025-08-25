<?php
$host = "localhost";
$dbname = "pmp_db";
$username = "root";
$password = ""; // mot de passe par défaut sous XAMPP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
