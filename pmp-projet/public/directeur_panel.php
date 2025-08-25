<?php
session_start();
if (!isset($_SESSION['directeur'])) {
    header('Location: directeur_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Espace Directeur</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css"> 

</head>
<body>
  <!-- Header à placer en haut de chaque page -->
  <header class="header">
  <div class="header-content">
    <a href="directeur_panel.php">
      <img src="R.jpg" alt="Logo Entreprise" class="logo">
    </a>
    <span class="site-title">Pakistan Maroc Phosphore</span>
  </div>
</header>

  <div class="container">
    <h1>Espace Directeur</h1>
    <ul class="directeur-actions">
      <li><a href="creer_utilisateur.php" class="btn-panel">Créer un compte utilisateur</a></li>
      <li><a href="publier_photo.php" class="btn-panel">Publier une photo</a></li>
      <li><a href="ajouter_presentation.php" class="btn-panel">Ajouter une présentation</a></li>
      <li><a href="accueil.php"><button class="role-btn">Voir les publications</button></a></li>
      <li><a href="gerer_publications.php" class="btn-panel">Gérer les publications</a></li>
      <li><a href="logout.php?directeur=1" class="btn-panel">Déconnexion</a></li>
    </ul>
  </div>

  <!-- Footer à placer en bas de chaque page -->
  <footer class="footer">
    <div>
      &copy; <?php echo date('Y'); ?> pakistan maroc phosphore. Tous droits réservés.
    </div>
  </footer>
</body>
</html>
