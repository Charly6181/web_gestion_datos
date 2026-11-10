const alertContainer = document.getElementById('alert-container');
const formAltaCliente = document.getElementById('alta-cliente-form');

// Función para validar DNI o CIF en el cliente con algoritmo matematico oficial
/* function validarDniCif(valor) {
    valor = valor.trim().toUpperCase();
    const dniRegex = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/;
    const cifRegex = /^[ABCDEFGHJKLMNPQRSUVW][0-9]{7}[0-9A-J]$/;

    if (dniRegex.test(valor)) {
        const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        const numero = parseInt(valor.substring(0, 8), 10);
        return valor.charAt(8) === letras[numero % 23];
    }

    if (cifRegex.test(valor)) {
        return true;
    }

    return false;
} */

function validarDniCif(valor) {
    valor = valor.trim().toUpperCase();

    // Expresiones regulares flexibles (8 números + cualquier letra, o CIF válido)
    const dniRegex = /^[0-9]{8}[A-Z]$/;
    const cifRegex = /^[ABCDEFGHJKLMNPQRSUVW][0-9]{7}[0-9A-J]$/;

    // Si cumple el formato de DNI o de CIF, se acepta directamente sin cálculos matemáticos
    if (dniRegex.test(valor) || cifRegex.test(valor)) {
        return true;
    }

    return false;
}

if (formAltaCliente) {
    formAltaCliente.addEventListener('submit', async (e) => {
        // evita recargar la página
        e.preventDefault();

        // Limpia alertas anteriores
        alertContainer.innerHTML = '';

        const dniInput = formAltaCliente.querySelector('[name="dni"]');
        if (dniInput && dniInput.value.trim() !== '') {
            if (!validarDniCif(dniInput.value)) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger py-2 mb-3 text-center';
                errorDiv.setAttribute('role', 'alert');
                errorDiv.textContent = 'El formato del DNI/CIF no es válido.';
                alertContainer.appendChild(errorDiv);
                return; // Detiene el envío si no es válido
            }
        }

        // recoge datos inputs
        const formData = new FormData(formAltaCliente);

        try {
            const response = await fetch('../api/altaclienteprocess.php', {
                method: 'POST',
                body: formData
            });

            const resultado = await response.json();

            if (resultado.exito) {

                formAltaCliente.reset();

                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success py-3 text-center';
                alertDiv.setAttribute('role', 'alert');

                alertDiv.innerHTML = `
                    <div class="fw-semibold mb-2">${resultado.mensaje}</div>
                    <a href="vistacliente.php" class="btn btn-outline-light btn-sm shadow-sm px-3">Ver listado de clientes</a>
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
            errorDiv.setAttribute('role', 'alert');
            errorDiv.textContent = 'Ocurrió un error inesperado al procesar el alta del cliente.';
            alertContainer.appendChild(errorDiv);
        }
    });
}