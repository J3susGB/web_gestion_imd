document.addEventListener('DOMContentLoaded', () => {
    const dropButtons = document.querySelectorAll('.borrar');

    dropButtons.forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            const parentContainer = this.closest('.partidos__partido');
            if (!parentContainer) {
                alert('Error: No se pudo encontrar el contenedor del partido.');
                return;
            }

            const inputElement = parentContainer.querySelector('input[name="id_arbi"]');
            if (!inputElement) {
                alert('Error: No se encontró el input del árbitro.');
                return;
            }

            const arbitroId = inputElement.value;
            const partidoId = parentContainer.querySelector('input[name="id"]').value;

            // Guarda el ID del partido en localStorage
            localStorage.setItem('scrollToPartidoId', partidoId);

            try {
                const respuesta = await fetch('/admin/partidos/borrar_nombramiento', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ arbitro: arbitroId, partido: partidoId }),
                });

                const respuestaTexto = await respuesta.text();
                try {
                    const data = JSON.parse(respuestaTexto);

                    if (respuesta.ok && data.success) {
                        alert(data.message || '¡Árbitro borrado correctamente!');
                        location.reload();
                    } else {
                        alert(data.message || 'Hubo un error al procesar la solicitud.');
                    }
                } catch (error) {
                    console.error('Error al procesar la respuesta del servidor:', respuestaTexto, error);
                    alert('Error en la comunicación con el servidor.');
                }
            } catch (error) {
                console.error('Error en la comunicación con el servidor:', error);
                alert('Error en la comunicación con el servidor.');
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
        localStorage.removeItem('scrollToPartidoId');
    }
});
