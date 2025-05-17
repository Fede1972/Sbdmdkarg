document.addEventListener("DOMContentLoaded", () => {
  fetch('data/dojangs.json')
    .then(response => response.json())
    .then(data => mostrarDojangs(data))
    .catch(error => console.error('Error cargando los dojangs:', error));
});

function mostrarDojangs(dojangs) {
  const contenedor = document.getElementById("contenedor-dojangs");
  const provincias = {};

  dojangs.forEach(d => {
    if (!provincias[d.provincia]) provincias[d.provincia] = [];
    provincias[d.provincia].push(d);
  });

  for (const provincia in provincias) {
    const seccion = document.createElement("div");
    seccion.classList.add("provincia");

    const titulo = document.createElement("div");
    titulo.classList.add("provincia-titulo");
    titulo.textContent = provincia;
    titulo.onclick = () => ul.classList.toggle("visible");

    const ul = document.createElement("ul");
    ul.classList.add("dojang-lista");

    provincias[provincia].forEach(d => {
      const li = document.createElement("li");
      li.innerHTML = `<strong>${d.dojang}</strong> (${d.ciudad})<br>
                      Dirección: ${d.direccion}<br>
                      Instructor: ${d.instructor}<br>
                      <a href="${d.link}" target="_blank">Ver más</a>`;
      ul.appendChild(li);
    });

    seccion.appendChild(titulo);
    seccion.appendChild(ul);
    contenedor.appendChild(seccion);
  }
}
