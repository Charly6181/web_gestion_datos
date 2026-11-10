document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-cliente-form');
    const errorContainer = document.getElementById('error-container');
    const btnSubmit = document.getElementById('btn-submit');

    form.addEventListener('submit', async (e) => {
        // Evitamos que la página se recargue por completo
        e.preventDefault();

        //se limpian errores y se bloquea boton
        errorContainer.innerHTML = '';
        btnSubmit.disabled = true;
        btnSubmit.textContent = 'Comprobando...';

        try {
            //  Recopilamos los datos del formulario automáticamente usando FormData
            const formData = new FormData(form);    //FormData es un objeto nativo de js

            //  Enviamos los datos por POST en segundo plano con fetch (AJAX)
            const response = await fetch('../api/loginclienteprocess.php', {
                method: 'POST',
                body: formData  //mete dentro del paquete http los datos cogidos del FormData   
            });

            // Convertimos la respuesta del servidor a formato JSON
            const data = await response.json();

            if (!data.exito) {
                // si hay error muestra mensaje
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger py-2 mb-3 text-center';
                alertDiv.setAttribute('role', 'alert');
                alertDiv.textContent = data.mensaje;
                errorContainer.appendChild(alertDiv);

                // Restauramos el botón
                btnSubmit.disabled = false;
                btnSubmit.textContent = 'Acceder';
            } else {
                // si todo ok te manda a la pagina
                window.location.href = data.redirect;
            }

        } catch (error) {
            // Por si ocurre un error de red inesperado
            console.error('Error en la petición:', error);
            btnSubmit.disabled = false;
            btnSubmit.textContent = 'Acceder';
        }
    });

    // Lógica para el botón del "ojito" de la contraseña
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', () => {
            // Cambia el tipo de input entre 'password' y 'text'
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Cambia el icono SVG según el estado actual
            if (type === 'text') {
                // Icono de ojo tachado (ocultar contraseña)
                togglePassword.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                        <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755l-.809-2.805z"/>
                        <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.828 2.828l.823.823z"/>
                        <path d="M3.35 5.47c-.18.16-.353.322-.518.488A13.133 13.133 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.12 11.332 5.88 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l-.77-.77A6.028 6.028 0 0 1 8 11.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8c.058-.087.122-.183.195-.288.335-.48.83-1.12 1.465-1.755l.518-.487z"/>
                        <path d="M11.297 12.716l-1.432-1.432a4.5 4.5 0 0 1-6.364-6.364L2.069 3.492a5.5 5.5 0 0 0 9.228 9.224z"/>
                    </svg>`;
            } else {
                // Icono de ojo abierto (mostrar contraseña)
                togglePassword.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5m4.5 2.5a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0"/>
                    </svg>`;
            }
        });
    }
});