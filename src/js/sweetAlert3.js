document.addEventListener('DOMContentLoaded', () => {
    // Selecciona el botón de reset
    const resetButton = document.querySelector('.alert3');

    // Añade un evento de clic al botón
    resetButton.addEventListener('click', function () {
        // Muestra el cuadro de confirmación de SweetAlert
        Swal.fire({
            title: "Al pulsar Sí, se eliminarán todos los registros, ¿Desea continuar?",
            showCancelButton: true,
            confirmButtonText: "Sí",
            cancelButtonText: "No",
            confirmButtonColor: "#71B100",  // Color para el botón de confirmación
            cancelButtonColor: "#F05454",   // Color para el botón de cancelación
            backdrop: true,  // Oscurecer el fondo
            background: "#4e4d4b",  // Fondo personalizado de la alerta
            color: "#DDDDDD",        // Color del texto
            icon: "warning",
            customClass: {
                popup: 'custom-popup',     // Clase personalizada para el popup
                title: 'custom-title',     // Clase personalizada para el título
                confirmButton: 'custom-button',  // Clase personalizada para el botón de confirmación
                cancelButton: 'custom-button'   // Clase personalizada para el botón de cancelación
            }

        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, envía el formulario
                const form = this.closest('form'); // Obtén el formulario asociado al botón
                form.submit(); // Envía el formulario para hacer el reset
            }
        });
    });
});
