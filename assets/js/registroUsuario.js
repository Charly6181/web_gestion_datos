const alertContainer = document.getElementById('alert-container');
const formRegistro = document.getElementById('registro-form');

if (formRegistro) {
    formRegistro.addEventListener('submit', async (e) => {
        // evita recargar pagina
        e.preventDefault();

        // limpia alertas anteriores
        alertContainer.innerHTML = '';

        // recoge datos inputs
        const formData = new FormData(formRegistro);

        try {
            const response = await fetch('../api/registrousuarioprocess.php', {
                method: 'POST',
                body: formData
            });

            const resultado = await response.json();

            if (resultado.exito) {
                formRegistro.style.display = 'none';

                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success py-3 text-center';
                alertDiv.setAttribute('role', 'alert');

                alertDiv.innerHTML = `
                    <div class="fw-semibold mb-3">${resultado.mensaje}</div>
                    <a href="vistaloginusuario.php" class="btn btn-primary btn-sm shadow-sm px-4">Iniciar sesión</a>
                `;
                alertContainer.appendChild(alertDiv);

            } else {
                // si hay error en algo muestra mensaje error
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger py-2 mb-3 text-center';
                errorDiv.setAttribute('role', 'alert');
                errorDiv.textContent = resultado.mensaje;
                alertContainer.appendChild(errorDiv);
            }

        } catch (error) {
            console.error('Error en el fetch:', error);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger py-2 mb-3 text-center';
            errorDiv.textContent = 'Ocurrió un error inesperado al procesar el registro.';
            alertContainer.appendChild(errorDiv);
        }
    });
}