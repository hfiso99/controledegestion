<?php
session_start();
if (!isset($_SESSION['directeur'])) {
    header('Location: directeur_login.php');
    exit;
}
require_once '../backend/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide.";
    } elseif (strlen($password) < 4) {
        $message = "Mot de passe trop court.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $message = "Cet email existe déjà.";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'utilisateur')");
            if ($stmt->execute([$email, $hashed])) {
                $message = "Compte utilisateur créé !";
            } else {
                $message = "Erreur lors de la création.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer un utilisateur</title>
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
  </div></header>

  <div class="container">
    <h2>Créer un utilisateur</h2>
    <?php if ($message): ?>
      <div class="msg<?php echo (strpos($message, 'créé') === false) ? ' error' : ''; ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>
    <form method="post">
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Mot de passe" required />
      <button type="submit">Créer</button>
    </form>
    <a href="directeur_panel.php">Retour</a>
  </div>

  <!-- Footer à placer en bas de chaque page -->
  <footer class="footer">
    <div>
      &copy; <?php echo date('Y'); ?> pakistan maroc phosphore. Tous droits réservés.
    </div>
  </footer>
</body>
</html>