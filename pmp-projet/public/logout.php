<?php
session_start();
session_destroy();

if (isset($_GET['directeur'])) {
    header('Location: directeur_login.php');
    exit;
}
header('Location: accueil.php');
exit;
?>

<!-- Header à placer en haut de chaque page -->
<header class="header">
  <div class="header-content">
    <img src="R.jpg" alt="Logo Entreprise" class="logo">
    <span class="site-title">Entreprise Réussite</span>
  </div>
</header>

<!-- Footer à placer en bas de chaque page -->
<footer class="footer">
  <div>
    &copy; <?php echo date('Y'); ?> pakistan maroc phosphore. Tous droits réservés.
  </div>
</footer>

