document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-editar-cliente');
    const alertaContainer = document.getElementById('alerta-container'); // Un contenedor vacío en el HTML para los avisos

    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault(); // Evitamos la recarga tradicional de la página

        const formData = new FormData(form);
        const urlParams = new URLSearchParams(window.location.search);
        const clienteId = urlParams.get('id');

        try {
            const response = await fetch(`../api/editarclienteprocess.php?id=${clienteId}`, {
                method: 'POST',
                body: formData
            });

            const resultado = await response.json();

            if (resultado.exito) {
                // Mostramos alerta de éxito
                alertaContainer.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                        ${escapeHtml(resultado.mensaje)}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            } else {
                // Mostramos alerta de error
                alertaContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                        ${escapeHtml(resultado.mensaje)}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error en la petición:', error);
            alertaContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                    Error de conexión con el servidor.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
        }
    });
});

function escapeHtml(text) {
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return text.toString().replace(/[&<>"']/g, function (m) { return map[m]; });
}