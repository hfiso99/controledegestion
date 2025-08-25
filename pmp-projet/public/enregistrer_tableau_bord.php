<?php
session_start();
if (!isset($_SESSION['directeur'])) {
    header('Location: directeur_login.php');
    exit;
}

$directeur_id = $_SESSION['directeur'];

$conn = new mysqli("localhost", "root", "", "pmp_db");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$titre = $conn->real_escape_string($_POST['titre']);
$description = $conn->real_escape_string($_POST['description']);
$theme = $conn->real_escape_string($_POST['theme']);
$widgets = $conn->real_escape_string($_POST['widgets']);

$sql = "INSERT INTO tableaux_bords (id, titre, description, theme, widgets, date_creation)
        VALUES ('$id', '$titre', '$description', '$theme', '$widgets', NOW())";

if ($conn->query($sql) === TRUE) {
    header('Location: tableaux_bords.php?message=success');
} else {
    echo "Erreur : " . $conn->error;
}

$conn->close();
?>

<!-- Header à placer en haut de chaque page -->
<header class="header">
  <div class="header-content">
    <img src="R.jpg" alt="Logo Entreprise" class="logo">
    <span class="site-title">Pakistan Maroc Phosphore</span>
  </div>
</header>

<!-- Footer à placer en bas de chaque page -->
<footer class="footer">
  <div>
    &copy; <?php echo date('Y'); ?> pakistan maroc phosphore. Tous droits réservés.
  </div>
</footer>


