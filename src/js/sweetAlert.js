document.addEventListener('DOMContentLoaded', () => {
    const button = document.querySelector('.alert');

    const registrer_btn = document.querySelector('.alert');
    registrer_btn.addEventListener('click', function () {
        registrer_btn.classList.add('mostrar_sweet');
        mostrar_alerta();
        setTimeout(function () {
            registrer_btn.classList.remove('mostrar_sweet');
        }, 100)
    });

    function mostrar_alerta() {
        Swal.fire({
            icon: "success", // Ícono de éxito
            title: "¡Hecho!", // Texto de éxito
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