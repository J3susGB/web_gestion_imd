document.addEventListener('DOMContentLoaded', function () {
    const iconosRechazado = document.querySelectorAll('.modi');

    // Crear el modal dinámicamente
    const modal = document.createElement('div');
    modal.classList.add('modal-hover-modi');
    document.body.appendChild(modal);

    iconosRechazado.forEach(icon => {
        icon.addEventListener('mouseenter', function (e) {
            mostrarModal(e, icon);
        });

        icon.addEventListener('mouseleave', function () {
            ocultarModal();
        });
    });

    /**
     * Mostrar Modal al Hover
     * @param {Event} e - Evento de mouse
     * @param {Element} icon - Elemento sobre el que se hizo hover
     */
    function mostrarModal(e, icon) {
        const rect = icon.getBoundingClientRect();
        const observaciones = icon.getAttribute('data-observaciones') || 'Sin observaciones';

        modal.textContent = observaciones; // Mostrar el valor único de observaciones
        modal.style.left = `${rect.left + window.scrollX + 35}px`;
        modal.style.top = `${rect.top + window.scrollY - 5}px`;

        modal.style.display = 'block';
    }

    /**
     * Ocultar Modal al salir del Hover
     */
    function ocultarModal() {
        modal.style.display = 'none';
    }
});
