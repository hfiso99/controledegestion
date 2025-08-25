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
