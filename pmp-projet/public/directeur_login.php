<?php
session_start();
require_once '../backend/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    // Récupère le hash du mot de passe depuis la base
    $stmt = $conn->prepare("SELECT password FROM directeurs WHERE email = ?");
    $stmt->execute(['elmoubarikhafsa@gmail.com']);
    $row = $stmt->fetch();
    if ($row && password_verify($code, $row['password'])) {
        $_SESSION['directeur'] = true;
        header('Location: directeur_panel.php');
        exit;
    } else {
        $message = "Code confidentiel incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion Directeur</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css"> 
</head>
<body>
<header class="header">
  <div class="header-content">
    <img src="R.jpg" alt="Logo Entreprise" class="logo">
    <span class="site-title">Pakistan Maroc Phosphore</span>
  </div>
</header>
<div class="container">
  <h2>Connexion Directeur</h2>
  <?php if ($message): ?><div class="msg"><?php echo $message; ?></div><?php endif; ?>
  <form method="post" autocomplete="off">
    <input type="password" name="code" placeholder="Code confidentiel" required autofocus />
    <button type="submit">Se connecter</button>
  </form>
  <a href="changer_mdp_directeur.php" class="btn-panel" style="display:block;margin-top:1em;">Changer le mot de passe</a>
</div>
<footer class="footer">
  <div>
    &copy; <?php echo date('Y'); ?> pakistan maroc phosphore. Tous droits réservés.
  </div>
</footer>
</body>
</html>
