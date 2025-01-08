document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.alert2'); // Selecciona todos los botones de eliminación

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            const form = this.closest('form'); // Obtén el formulario asociado al botón
            const itemId = form.querySelector('input[name="id"]').value; // Obtén el ID desde el campo oculto

            Swal.fire({
                title: "Al pulsar Sí, el contenido será eliminado, ¿Desea confirmar?",
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
                    form.submit(); // Envía el formulario si el usuario confirma
                }
            });
        });
    });

    function mostrar_alerta() {
        Swal.fire({
            icon: "success",
            title: "¡Hecho!"
        });
    }
});
