<?php
session_start();
if (!isset($_SESSION['directeur'])) {
    header('Location: directeur_login.php');
    exit;
}
require_once '../backend/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $embed_code = trim($_POST['embed_code'] ?? '');
    if ($titre && $embed_code) {
        $stmt = $conn->prepare("INSERT INTO presentations (titre, embed_code) VALUES (?, ?)");
        $stmt->execute([$titre, $embed_code]);
        $message = "Présentation ajoutée avec succès !";
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter une présentation</title>

  <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <header class="header">
  <div class="header-content">
    <a href="directeur_panel.php">
      <img src="R.jpg" alt="Logo Entreprise" class="logo">
    </a>
    <span class="site-title">Pakistan Maroc Phosphore</span>
  </div></header>
  
  <div class="container">
    <h2>Ajouter une présentation</h2>
    <?php if ($message): ?><div class="msg"><?php echo $message; ?></div><?php endif; ?>
    <form method="post">
      <input type="text" name="titre" placeholder="Titre de la présentation" required>
      <textarea name="embed_code" placeholder="Collez ici le code d'intégration Canva, Google Slides, etc." required style="height:90px;"></textarea>
      <button type="submit">Ajouter</button>
    </form>
    <p style="font-size:0.95em;color:#888;">
      <b>Astuce :</b> Collez ici le code d'intégration (iframe) de Canva, Google Slides ou <b>Power BI</b>.<br>
      Pour Power BI : ouvrez votre rapport, cliquez sur <b>Fichier &gt; Publier sur le web</b> puis copiez le code d'intégration.
    </p>
  </div>
 
</body>
</html>