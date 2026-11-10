document.addEventListener('DOMContentLoaded', () => {
    // Usamos delegación de eventos en el contenedor de la tabla
    const listaClientes = document.getElementById('lista-clientes');

    if (!listaClientes) return;

    listaClientes.addEventListener('click', async (e) => {
        // Verificamos si el elemento clicado es un botón de eliminar (o está dentro de uno)
        const btnEliminar = e.target.closest('.btn-eliminar');
        if (!btnEliminar) return;

        const clienteId = btnEliminar.getAttribute('data-id');

        if (!confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
            return;
        }

        try {
            const response = await fetch('../api/eliminarclienteprocess.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${encodeURIComponent(clienteId)}`
            });

            const resultado = await response.json();

            if (resultado.exito) {
                // Eliminamos la fila correspondiente de la tabla de forma fluida
                const row = btnEliminar.closest('tr');
                row.remove();
            } else {
                alert('Error: ' + resultado.mensaje);
            }
        } catch (error) {
            console.error('Error en la petición:', error);
            alert('Error de conexión con el servidor.');
        }
    });
});