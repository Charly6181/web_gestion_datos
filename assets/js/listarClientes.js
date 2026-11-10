document.addEventListener('DOMContentLoaded', () => {
    const tbody = document.getElementById('lista-clientes');

    fetch('../api/obtenerclientesprocess.php')
        .then(response => response.json())
        .then(data => {
            if (!data.exito || data.clientes.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">No hay clientes registrados todavía.</td></tr>`;
                return;
            }

            let html = '';      /* prepara una variable para almacenar los datos */
            data.clientes.forEach((cliente, index) => {
                const dniTexto = cliente.dni ? cliente.dni : 'N/D';
                const dirTexto = cliente.direccion ? cliente.direccion : 'Sin dirección';

                // Comprobamos si el cliente esta activo o no en la bbdd
                const esActivo = Number(cliente.activo) === 1;
                const badgeClase = esActivo ? 'bg-success' : 'bg-danger';
                const badgeTexto = esActivo ? 'Activo' : 'Inactivo';

                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(cliente.nombre + ' ' + cliente.apellidos)}</td>
                        <td>${escapeHtml(dniTexto)}</td>
                        <td>${escapeHtml(cliente.email)}</td>
                        <td>${escapeHtml(dirTexto)}</td>
                        <td class="text-center">
                            <span class="badge ${badgeClase}">${badgeTexto}</span>
                        </td>
                        <td class="text-center">        
                            <a href="vistaeditarcliente.php?id=${cliente.id}" class="btn btn-sm btn-outline-info" title="Editar cliente">      
                                Editar
                            </a>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar" data-id="${cliente.id}" title="Eliminar cliente">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error al cargar los clientes:', error);
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-4">Error al cargar los datos del servidor.</td></tr>`;
        });
});

function escapeHtml(text) {    //metodo seguridad para evitar inyecciones de datos maliciosos
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, function (m) { return map[m]; });
}