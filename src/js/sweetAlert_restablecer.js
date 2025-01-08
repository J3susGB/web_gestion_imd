document.addEventListener('DOMContentLoaded', () => {
    const button = document.querySelector('.alert_restablecer');

    button.addEventListener('click', function () {
        const form = button.closest('form'); // Obtiene el formulario más cercano al botón
        const formData = new FormData(form); // Crea un objeto FormData con los datos del formulario

        // URL específica para el envío
        const url = '/admin/perfiles/restablecer_password';

        fetch(url, {
            method: 'POST',
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la solicitud'); // Manejo de errores si la respuesta no es OK
            }
            return response.json(); // Procesa la respuesta como JSON
        })
        .then(data => {
            // Muestra la alerta de éxito al finalizar el POST
            mostrar_alerta();
        })
        .catch(error => {
            console.error('Hubo un problema con la solicitud:', error); // Muestra el error en la consola
        });
    });

    function mostrar_alerta() {
        Swal.fire({
            icon: "success", // Ícono de éxito
            title: "Se ha enviado email para restablecer la contraseña", // Texto de éxito
            background: "#4e4d4b",  // Fondo personalizado de la alerta
            color: "#DDDDDD",        // Color del texto
            customClass: {
                popup: 'custom-popup',     // Clase personalizada para el popup
                title: 'custom-title',     // Clase personalizada para el título
                confirmButton: 'custom-button'  // Clase personalizada para el botón de confirmación
            },
            showConfirmButton: true, // Mostrar botón de confirmación
            confirmButtonColor: "#71B100",  // Color para el botón de confirmación
            timer: 3000, // Tiempo automático de cierre (opcional)
        });
    }
});
