<?php
// filepath: c:\xampp\htdocs\pmp-projet\public\changer_mdp_directeur_code.php
session_start();
require_once '../backend/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveau = $_POST['nouveau'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    if ($nouveau !== $confirm) {
        $message = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($nouveau) < 4) {
        $message = "Mot de passe trop court (minimum 4 caractères).";
    } else {
        $hash = password_hash($nouveau, PASSWORD_BCRYPT);
        // Change le mot de passe du directeur (email stocké en session)
        $stmt = $conn->prepare("UPDATE directeurs SET password = ? WHERE email = ?");
        $stmt->execute([$hash, $_SESSION['email_mdp']]);
        unset($_SESSION['code_mdp'], $_SESSION['email_mdp']);
        $message = "Mot de passe changé avec succès.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nouveau mot de passe directeur</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <h2>Définir un nouveau mot de passe</h2>
  <?php if ($message): ?>
    <div class="msg"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>
  <form method="post">
    <input type="password" name="nouveau" placeholder="Nouveau mot de passe" required>
    <input type="password" name="confirm" placeholder="Confirmer le nouveau mot de passe" required>
    <button type="submit" class="btn-panel">Changer le mot de passe</button>
  </form>
  <a href="directeur_login.php" class="btn-panel">Retour à la connexion</a>
</div>
<?php include 'footer.php'; ?>
</body>
</html>