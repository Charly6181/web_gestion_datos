document.addEventListener('DOMContentLoaded', async () => {
    const contenedor = document.getElementById('contenedor-datos-cliente');
    const spanNav = document.getElementById('nombre-usuario-nav');

    try {
        const response = await fetch('../api/obtenerdatosclienteprocess.php');
        const data = await response.json();

        if (!data.exito) {
            contenedor.innerHTML = `<div class="alert alert-danger text-center">${data.mensaje}</div>`;
            return;
        }

        const cliente = data.cliente;

        // Actualizamos el nombre en la barra de navegación
        if (spanNav) {
            spanNav.textContent = cliente.nombre;
        }

        // Pintamos la lista de datos de forma limpia
        contenedor.innerHTML = `
            <ul class="list-group list-group-flush bg-transparent">
                <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between py-3">
                    <span class="fw-bold">Nombre completo:</span>
                    <span>${cliente.nombre} ${cliente.apellidos}</span>
                </li>
                <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between py-3">
                    <span class="fw-bold">DNI / CIF:</span>
                    <span>${cliente.dni}</span>
                </li>
                <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between py-3">
                    <span class="fw-bold">Correo electrónico:</span>
                    <span>${cliente.email}</span>
                </li>
                <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between py-3">
                    <span class="fw-bold">Dirección:</span>
                    <span>${cliente.direccion}</span>
                </li>
            </ul>
        `;

    } catch (error) {
        console.error('Error al cargar el perfil:', error);
        contenedor.innerHTML = `<div class="alert alert-warning text-center">Error al conectar con el servidor.</div>`;
    }
});