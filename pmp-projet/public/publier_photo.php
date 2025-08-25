<?php
session_start();
if (!isset($_SESSION['directeur'])) {
    header('Location: directeur_login.php');
    exit;
}
require_once '../backend/db.php';

// Suppression d'une photo
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("SELECT filename FROM photos WHERE id = ?");
    $stmt->execute([$id]);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($photo) {
        @unlink("../uploads/" . $photo['filename']);
        $conn->prepare("DELETE FROM photos WHERE id = ?")->execute([$id]);
        $message = "Photo supprimée.";
    }
}

// Modification d'une photo (remplacement)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id']) && isset($_FILES['edit_photo'])) {
    $id = intval($_POST['edit_id']);
    $file = $_FILES['edit_photo'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $newName = uniqid('photo_', true) . '.' . $ext;
            $dest = '../uploads/' . $newName;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                // Supprimer l'ancienne photo
                $stmt = $conn->prepare("SELECT filename FROM photos WHERE id = ?");
                $stmt->execute([$id]);
                $old = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($old) @unlink("../uploads/" . $old['filename']);
                // Mettre à jour la BDD
                $conn->prepare("UPDATE photos SET filename = ? WHERE id = ?")->execute([$newName, $id]);
                $message = "Photo modifiée avec succès.";
            } else {
                $message = "Erreur lors du remplacement du fichier.";
            }
        } else {
            $message = "Format de fichier non autorisé.";
        }
    } else {
        $message = "Erreur lors de l'envoi du fichier.";
    }
}

// Ajout d'une nouvelle photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo']) && !isset($_POST['edit_id'])) {
    $file = $_FILES['photo'];
    $auteur = trim($_POST['auteur'] ?? '');
    $description = trim($_POST['description'] ?? '');
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $newName = uniqid('photo_', true) . '.' . $ext;
            $dest = '../uploads/' . $newName;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $stmt = $conn->prepare("INSERT INTO photos (filename, description, auteur) VALUES (?, ?, ?)");
                $stmt->execute([$newName, $description, $auteur]);
                $message = "Photo publiée avec succès.";
            } else {
                $message = "Erreur lors de l'envoi du fichier.";
            }
        } else {
            $message = "Format de fichier non autorisé.";
        }
    } else {
        $message = "Erreur lors de l'envoi du fichier.";
    }
}

// Récupérer toutes les photos
$photos = $conn->query("SELECT id, filename FROM photos ORDER BY uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Publier une photo</title>
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
  </div></header>   

  <div class="container">
    <h2>Publier une photo</h2>
    <?php if (!empty($message)): ?>
      <div class="msg<?php echo (strpos($message, 'succès') === false) ? ' error' : ''; ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
      <input type="text" name="auteur" placeholder="Votre nom" required>
      <textarea name="description" placeholder="Description de la photo" required></textarea>
      <input type="file" name="photo" accept="image/*" required>
      <button type="submit">Publier</button>
    </form>
    <h3>Photos publiées</h3>
    <div class="photos-list">
      <?php while ($photo = $photos->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="photo-card">
          <img src="../uploads/<?php echo htmlspecialchars($photo['filename']); ?>" alt="photo">
          <form method="post" enctype="multipart/form-data" style="margin-bottom:0.5em;">
            <input type="hidden" name="edit_id" value="<?php echo $photo['id']; ?>">
            <input type="file" name="edit_photo" accept="image/*" required>
            <button type="submit">Modifier</button>
          </form>
          <form method="get" onsubmit="return confirm('Supprimer cette photo ?');">
            <input type="hidden" name="delete" value="<?php echo $photo['id']; ?>">
            <button type="submit" style="background:#e74c3c;">Supprimer</button>
          </form>
        </div>
      <?php endwhile; ?>
    </div>
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