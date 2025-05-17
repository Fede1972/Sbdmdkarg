document.addEventListener('DOMContentLoaded', () => {
  fetch('data/dojangs.json')
    .then(response => response.json())
    .then(data => renderDojangs(data))
    .catch(error => console.error('Error al cargar datos:', error));
});

function renderDojangs(dojangs) {
  const container = document.getElementById('contenedor-dojangs');

  // Agrupar por provincia
  const provincias = {};
  dojangs.forEach(d => {
    if (!provincias[d.provincia]) provincias[d.provincia] = [];
    provincias[d.provincia].push(d);
  });

  // Renderizar cada provincia
  for (const provincia in provincias) {
    const provDiv = document.createElement('div');
    provDiv.classList.add('provincia');

    const title = document.createElement('h2');
    title.textContent = provincia;
    title.classList.add('provincia-titulo');
    title.addEventListener('click', () => {
      lista.classList.toggle('visible');
    });

    const lista = document.createElement('ul');
    lista.classList.add('dojang-lista');

    provincias[provincia].forEach(d => {
      const li = document.createElement('li');
      li.innerHTML = `
        <strong>${d.nombre}</strong><br>
        ${d.ciudad} - ${d.direccion}<br>
        Contacto: ${d.contacto}<br>
        <a href="${d.link}">Ver más</a>
      `;
      lista.appendChild(li);
    });

    provDiv.appendChild(title);
    provDiv.appendChild(lista);
    container.appendChild(provDiv);
  }
}
