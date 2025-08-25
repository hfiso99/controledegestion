<?php
session_start();
require_once '../backend/db.php';

// Ajout d'un commentaire sur une présentation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_presentation_id'])) {
    $presentation_id = intval($_POST['comment_presentation_id']);
    $comment_auteur = $_SESSION['email'];
    $commentaire = trim($_POST['commentaire_presentation'] ?? '');
    if ($commentaire) {
        // On utilise la même table, mais on stocke l'id de la présentation dans le champ photo_id
        $stmt = $conn->prepare("INSERT INTO commentaires (photo_id, auteur, commentaire, type) VALUES (?, ?, ?, 'presentation')");
        $stmt->execute([$presentation_id, $comment_auteur, $commentaire]);
        header("Location: accueil.php");
        exit;
    }
}

// Ajout d'un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_photo_id'])) {
    $photo_id = intval($_POST['comment_photo_id']);
    $comment_auteur = $_SESSION['email']; // Automatique
    $commentaire = trim($_POST['commentaire'] ?? '');
    if ($commentaire) {
        $stmt = $conn->prepare("INSERT INTO commentaires (photo_id, auteur, commentaire) VALUES (?, ?, ?)");
        $stmt->execute([$photo_id, $comment_auteur, $commentaire]);
        header("Location: accueil.php");
        exit;
    }
}

$photos = $conn->query("SELECT p.filename, p.id, p.auteur, p.description FROM photos p ORDER BY p.uploaded_at DESC");
$presentations = $conn->query("SELECT id, titre, embed_code FROM presentations ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil</title>
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
  <h2>Présentations de contrôle de gestion</h2>
  <?php while ($pres = $presentations->fetch(PDO::FETCH_ASSOC)): ?>
    <div class="presentation-block">
      <h3><?php echo htmlspecialchars($pres['titre']); ?></h3>
      <div class="presentation-embed">
        <?php echo $pres['embed_code']; ?>
      </div>
      <!-- Affichage des commentaires -->
      <div class="comments">
        <h4>Commentaires</h4>
        <?php
          $stmt = $conn->prepare("SELECT auteur, commentaire, created_at FROM commentaires WHERE photo_id = ? AND type = 'presentation' ORDER BY created_at ASC");
          $stmt->execute([$pres['id']]);
          while ($com = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
          <div style="background:#f1f1f1;padding:5px 8px;margin:4px 0;border-radius:4px;">
            <b><?php echo htmlspecialchars($com['auteur']); ?>:</b>
            <?php echo nl2br(htmlspecialchars($com['commentaire'])); ?>
            <span style="font-size:0.85em;color:#888;">(<?php echo $com['created_at']; ?>)</span>
          </div>
        <?php endwhile; ?>
        <!-- Formulaire d'ajout de commentaire -->
        <form method="post" style="margin-top:6px;">
          <input type="hidden" name="comment_presentation_id" value="<?php echo $pres['id']; ?>">
          <input type="text" name="comment_auteur" value="<?php echo $_SESSION['email']; ?>" readonly style="width:90%;margin-bottom:4px;">
          <textarea name="commentaire_presentation" placeholder="Votre commentaire" required style="width:90%;"></textarea>
          <button type="submit" style="width:90%;">Commenter</button>
        </form>
      </div>
    </div>
  <?php endwhile; ?>
</div>
  <div class="container">
    <div class="logout">
      <a href="logout.php">Déconnexion</a>
    </div>
    <h1>Bienvenue sur la plateforme</h1>
    <h2>Photos publiées</h2>
    <div class="photos">
      <?php while ($photo = $photos->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="photo-card">
          <strong><?php echo htmlspecialchars($photo['auteur']); ?></strong><br>
          <em><?php echo nl2br(htmlspecialchars($photo['description'])); ?></em>
          <a href="#zoomModal<?php echo $photo['id']; ?>">
            <img src="../uploads/<?php echo htmlspecialchars($photo['filename']); ?>" alt="photo"
                 onclick="zoomPhoto(this.src)">
          </a>
          <!-- ...formulaires modifier/supprimer... -->

          <!-- Affichage des commentaires -->
          <div class="comments">
            <h4>Commentaires</h4>
            <?php
              $stmt = $conn->prepare("SELECT auteur, commentaire, created_at FROM commentaires WHERE photo_id = ? ORDER BY created_at ASC");
              $stmt->execute([$photo['id']]);
              while ($com = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
              <div style="background:#f1f1f1;padding:5px 8px;margin:4px 0;border-radius:4px;">
                <b><?php echo htmlspecialchars($com['auteur']); ?>:</b>
                <?php echo nl2br(htmlspecialchars($com['commentaire'])); ?>
                <span style="font-size:0.85em;color:#888;">(<?php echo $com['created_at']; ?>)</span>
              </div>
            <?php endwhile; ?>
            <!-- Formulaire d'ajout de commentaire -->
            <form method="post" style="margin-top:6px;">
              <input type="hidden" name="comment_photo_id" value="<?php echo $photo['id']; ?>">
              <input type="text" name="comment_auteur" placeholder="Votre nom" value="<?php echo $_SESSION['email']; ?>" required style="width:90%;margin-bottom:4px;" readonly>
              <textarea name="commentaire" placeholder="Votre commentaire" required style="width:90%;"></textarea>
              <button type="submit" style="width:90%;">Commenter</button>
            </form>
          </div>
        </div>

        <!-- Zoom Modal -->
        <div id="zoomModal<?php echo $photo['id']; ?>" class="zoom-modal">
          <div class="close" onclick="document.getElementById('zoomModal<?php echo $photo['id']; ?>').style.display='none'">&times;</div>
          <img src="../uploads/<?php echo htmlspecialchars($photo['filename']); ?>" alt="photo">
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <div id="zoomModal">
    <span class="close" onclick="document.getElementById('zoomModal').style.display='none'">&times;</span>
    <img id="zoomImg" src="" alt="Zoom">
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div>
      &copy; <?php echo date('Y'); ?> Entreprise Réussite. Tous droits réservés.
    </div>
  </footer>

  <script>
  function zoomPhoto(src) {
    document.getElementById('zoomImg').src = src;
    document.getElementById('zoomModal').style.display = 'flex';
  }
  window.onclick = function(event) {
    var modal = document.getElementById('zoomModal');
    if (event.target === modal) {
      modal.style.display = "none";
    }
  }
  </script>
</body>
</html>