<?php
// creer_tableau_bord.php
session_start();
if (!isset($_SESSION['directeur'])) {
    header('Location: directeur_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer un tableau de bord</title>
  <link rel="stylesheet" href="style.css">
 
</head>
<body>
  <div class="form-container">
    <h2><i class="fas fa-chart-line"></i> Créer un nouveau tableau de bord</h2>
    <form action="enregistrer_tableau_bord.php" method="POST" id="dashboardForm">
      <label for="titre">Titre du tableau</label>
      <input type="text" name="titre" id="titre" placeholder="Exemple : Statistiques Mensuelles" required>

      <label for="description">Description</label>
      <textarea name="description" id="description" placeholder="Décrire le but ou le contenu du tableau..."></textarea>

      <label for="theme">Thème</label>
      <select name="theme" id="theme">
        <option value="clair">Clair</option>
        <option value="sombre">Sombre</option>
        <option value="bleu">Bleu</option>
        <option value="vert">Vert</option>
      </select>

      <h3 style="margin-top: 2em;">Ajouter des widgets</h3>
      <div id="widgetsContainer"></div>
      <button type="button" onclick="ajouterWidget()"><i class="fas fa-plus"></i> Ajouter un widget</button>

      <input type="hidden" name="widgets" id="widgetsInput">

      <div style="text-align: center;">
        <button type="submit"><i class="fas fa-save"></i> Créer le tableau de bord</button>
      </div>
    </form>
  </div>

  <script>
    let widgets = [];

    function ajouterWidget() {
      const container = document.getElementById('widgetsContainer');
      const index = widgets.length;

      const widgetHTML = `
        <div class="widget-item" data-index="${index}">
          <span class="remove-widget" onclick="supprimerWidget(${index})"><i class="fas fa-times"></i></span>
          <label>Type de widget</label>
          <select onchange="mettreAJourWidgets()" name="type">
            <option value="stats">Statistiques</option>
            <option value="graphique">Graphique</option>
            <option value="liste">Liste</option>
            <option value="texte">Zone de texte</option>
          </select>

          <label>Titre du widget</label>
          <input type="text" placeholder="Titre du widget" oninput="mettreAJourWidgets()" name="titre">
        </div>
      `;

      const div = document.createElement('div');
      div.innerHTML = widgetHTML;
      container.appendChild(div);

      widgets.push({ type: 'stats', titre: '' });
      mettreAJourWidgets();
    }

    function supprimerWidget(index) {
      const container = document.getElementById('widgetsContainer');
      container.children[index].remove();
      widgets.splice(index, 1);
      mettreAJourWidgets();
    }

    function mettreAJourWidgets() {
      const widgetDivs = document.querySelectorAll('.widget-item');
      const data = [];

      widgetDivs.forEach(div => {
        const type = div.querySelector('select').value;
        const titre = div.querySelector('input').value;
        data.push({ type, titre });
      });

      document.getElementById('widgetsInput').value = JSON.stringify(data);
    }
  </script>
</body>
</html>
