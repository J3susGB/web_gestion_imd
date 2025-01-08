document.addEventListener('DOMContentLoaded', () => {
    const saveButtons = document.querySelectorAll('.save');

    saveButtons.forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            const parentContainer = this.closest('.partidos__partido');
            if (!parentContainer) {
                alert('Error: No se pudo encontrar el contenedor del partido.');
                return;
            }

            const partidoArbitroContainer = parentContainer.querySelector('.partidos__partido--arbitro');
            if (!partidoArbitroContainer) {
                alert('Error: No se encontró el input del árbitro.');
                return;
            }

            const inputElement = partidoArbitroContainer.querySelector('.arbitro_autocomplete');
            if (!inputElement || !inputElement.hasAttribute('data-arbitro-id')) {
                alert('Por favor, selecciona un árbitro de la lista antes de guardar.');
                return;
            }

            const arbitroId = inputElement.getAttribute('data-arbitro-id');
            const partidoId = this.closest('form').querySelector('input[name="id"]').value;

            // Guarda el ID del partido en localStorage
            localStorage.setItem('scrollToPartidoId', partidoId);

            await enviarSolicitud(partidoId, arbitroId);

            async function enviarSolicitud(partidoId, arbitroId) {
                try {
                    const respuesta = await fetch('/admin/partidos/nombrar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ arbitro: arbitroId, partido: partidoId }),
                    });

                    const respuestaTexto = await respuesta.text();

                    if (!respuesta.ok) {
                        alert('Error en el servidor.');
                        return;
                    }

                    const data = JSON.parse(respuestaTexto);

                    if (data.success) {
                        const successMessage = data.message || '¡Árbitro guardado correctamente!';
                        alert(successMessage);

                        // Recarga la página para reflejar los cambios
                        location.reload();
                    } else {
                        alert(data.message || 'Hubo un error al guardar los datos.');
                    }
                } catch (error) {
                    alert('Error en la comunicación con el servidor.');
                }
            }
        });
    });

    // Desplazarse al partido guardado después de recargar
    const storedPartidoId = localStorage.getItem('scrollToPartidoId');
    if (storedPartidoId) {
        const partidoElement = document.querySelector(`.partidos__partido[data-partido-id="${storedPartidoId}"]`);
        if (partidoElement) {
            partidoElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        // Eliminar el ID del localStorage una vez usado
        localStorage.removeItem('scrollToPartidoId');
    }
});
