document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.alert5');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const form = this.closest('form');

            Swal.fire({
                title: "Al pulsar Sí, la categoría será eliminada, ¿Desea confirmar?",
                showCancelButton: true,
                confirmButtonText: "Sí",
                cancelButtonText: "No",
                confirmButtonColor: "#71B100",
                cancelButtonColor: "#F05454",
                backdrop: true,
                background: "#4e4d4b",
                color: "#DDDDDD",
                icon: "warning"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Eliminado',
                                    text: 'La categoría ha sido eliminada.',
                                    icon: 'success',
                                    confirmButtonText: "Aceptar",
                                    confirmButtonColor: "#71B100",
                                    background: "#4e4d4b",  // Fondo personalizado de la alerta
                                    color: "#DDDDDD",  // Color del texto
                                    customClass: {
                                        popup: 'custom-popup',  // Clase personalizada para el popup
                                        title: 'custom-title',  // Clase personalizada para el título
                                        confirmButton: 'custom-button',  // Clase personalizada para el botón de confirmación
                                    }
                                }).then(() => {
                                    window.location.href = '/admin/categorias'; // Redirige al usuario
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: "Aceptar",
                                    confirmButtonColor: "#F05454",
                                    background: "#4e4d4b",  // Fondo personalizado de la alerta
                                    color: "#DDDDDD",  // Color del texto
                                    customClass: {
                                        popup: 'custom-popup',  // Clase personalizada para el popup
                                        title: 'custom-title',  // Clase personalizada para el título
                                        confirmButton: 'custom-button',  // Clase personalizada para el botón de confirmación
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error',
                                text: 'Algo salió mal.',
                                icon: 'error',
                                confirmButtonText: "Aceptar",
                                confirmButtonColor: "#F05454",
                                background: "#4e4d4b",  // Fondo personalizado de la alerta
                                color: "#DDDDDD",  // Color del texto
                                customClass: {
                                    popup: 'custom-popup',  // Clase personalizada para el popup
                                    title: 'custom-title',  // Clase personalizada para el título
                                    confirmButton: 'custom-button',  // Clase personalizada para el botón de confirmación
                                }
                            });
                        });
                }
            });
        });
    });
});
