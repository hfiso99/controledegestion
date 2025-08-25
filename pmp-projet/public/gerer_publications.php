<?php
// filepath: c:\xampp\htdocs\pmp-projet\public\gerer_publications.php
session_start();
if (!isset($_SESSION['directeur'])) {
    header('Location: directeur_login.php');
    exit;
}
require_once '../backend/db.php';

// Suppression d'une présentation
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM presentations WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: gerer_publications.php");
    exit;
}

// Modification d'une présentation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $titre = trim($_POST['edit_titre'] ?? '');
    $embed_code = trim($_POST['edit_embed_code'] ?? '');
    if ($titre && $embed_code) {
        $stmt = $conn->prepare("UPDATE presentations SET titre = ?, embed_code = ? WHERE id = ?");
        $stmt->execute([$titre, $embed_code, $id]);
    }
    header("Location: gerer_publications.php");
    exit;
}

// Récupération des présentations
$presentations = $conn->query("SELECT id, titre, embed_code FROM presentations ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gérer les présentations</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <h2>Gestion des présentations publiées</h2>
  <div class="photos-list">
    <?php while ($pres = $presentations->fetch(PDO::FETCH_ASSOC)): ?>
      <div class="presentation-block">
        <h3><?php echo htmlspecialchars($pres['titre']); ?></h3>
        <div class="presentation-embed">
          <?php echo $pres['embed_code']; ?>
        </div>
        <!-- Formulaire de modification -->
        <form method="post" style="margin-top:1em;">
          <input type="hidden" name="edit_id" value="<?php echo $pres['id']; ?>">
          <input type="text" name="edit_titre" value="<?php echo htmlspecialchars($pres['titre']); ?>" required>
          <textarea name="edit_embed_code" required style="width:100%;"><?php echo htmlspecialchars($pres['embed_code']); ?></textarea>
          <button type="submit" class="btn-panel">Modifier</button>
        </form>
        <!-- Bouton de suppression -->
        <form method="get" onsubmit="return confirm('Supprimer cette présentation ?');" style="margin-top:0.5em;">
          <input type="hidden" name="delete" value="<?php echo $pres['id']; ?>">
          <button type="submit" class="btn-panel" style="background:#e74c3c;">Supprimer</button>
        </form>
      </div>
    <?php endwhile; ?>
  </div>
  <a href="directeur_panel.php" class="btn-panel">Retour au panel</a>
</div>
<?php include 'footer.php'; ?>
</body>
</html>