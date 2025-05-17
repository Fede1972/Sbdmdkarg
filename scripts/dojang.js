document.addEventListener("DOMContentLoaded", () => {
  fetch('data/dojangs.json')
    .then(response => response.json())
    .then(data => mostrarDojangs(data))
    .catch(error => console.error('Error cargando los dojangs:', error));
});

function mostrarDojangs(dojangs) {
  const contenedor = document.getElementById("contenedor-dojangs");
  const regiones = {};

  dojangs.forEach(d => {
    if (!regiones[d.region]) regiones[d.region] = [];
    regiones[d.region].push(d);
  });

  for (const region in regiones) {
    const seccion = document.createElement("div");
    seccion.classList.add("region");

    const titulo = document.createElement("div");
    titulo.classList.add("region-titulo");
    titulo.textContent = region;
    titulo.onclick = () => ul.classList.toggle("visible");

    const ul = document.createElement("ul");
    ul.classList.add("dojang-lista");

    regiones[region].forEach(d => {
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
