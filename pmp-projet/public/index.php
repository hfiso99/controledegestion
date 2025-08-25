<?php
// filepath: c:\xampp\htdocs\pmp-projet\public\index.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Bienvenue</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .role-choice-container {
      max-width: 400px;
      margin: 8em auto 0 auto;
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 8px 32px rgba(39,174,96,0.10);
      padding: 2.5em 2em;
      text-align: center;
    }
  body {
  font-family: 'Segoe UI', Arial, sans-serif;
  min-height: 100vh;
  margin: 0;
  padding: 0;
  background: url('cover.jpg') center center/cover no-repeat fixed;
}
.cover-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100vw; height: 100vh;
  background: rgba(255,255,255,0.55); /* ajuste l'opacité si besoin */
  z-index: 0;
}
.role-choice-container, .logo {
  position: relative;
  z-index: 1;
}
  </style>
</head>
<body>
<div class="cover-overlay"></div>  
<div class="cover-container">
    
  </div>
  <div class="role-choice-container">
    <img src="R.jpg" alt="Logo Entreprise" class="logo">
    <h2>Bienvenue sur la plateforme</h2>
    <p>Veuillez choisir votre rôle :</p>
    <a href="directeur_login.php"><button class="role-btn">Responsable / Directeur</button></a>
    <a href="login.php"><button class="role-btn">Utilisateur</button></a>
  </div>
</body>
</html>