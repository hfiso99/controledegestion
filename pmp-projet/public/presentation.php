<?php
session_start();
require_once '../backend/db.php';
$presentations = $conn->query("SELECT titre, embed_code FROM presentations ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Présentations</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Header -->
  <header class="header">
    <div class="header-content">
      <img src="R.jpg" alt="Logo Entreprise" class="logo">
      <span class="site-title">Pakistan Maroc Phosphore</span>
    </div>
  </header>

  <div class="container">
    <h2>tableau de bord de l'entreprise</h2>
    <?php while ($pres = $presentations->fetch(PDO::FETCH_ASSOC)): ?>
      <div class="presentation-block">
        <h3><?php echo htmlspecialchars($pres['titre']); ?></h3>
        <div class="presentation-embed">
          <?php echo $pres['embed_code']; ?>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div>
      &copy; <?php echo date('Y'); ?> pakistan maroc phosphore. Tous droits réservés.
    </div>
  </footer>
</body>
</html>