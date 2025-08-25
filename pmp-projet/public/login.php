<?php
require_once '../backend/db.php';
session_start();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide.";
    } else {
        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role'];
            header('Location: accueil.php');
            exit;
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
 <link rel="stylesheet" href="style.css">
  

</head>
<body>
  <!-- Header à placer en haut de chaque page -->
  <header class="header">
    <div class="header-content">
      <img src="R.jpg" alt="Logo Entreprise" class="logo">
      <span class="site-title">Pakistan Maroc Phosphore</span>
    </div>
  </header>

  <div class="container">
    <h2>Connexion</h2>
    <?php if ($message): ?>
      <div class="msg"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Mot de passe" required />
      <button type="submit">Se connecter</button>
    </form>
  </div>

  <!-- Footer à placer en bas de chaque page -->
  <footer class="footer">
    <div>
      &copy; <?php echo date('Y'); ?> pakistan maroc phosphore. Tous droits réservés.
    </div>
  </footer>
</body>
</html>
