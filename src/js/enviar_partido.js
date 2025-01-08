// document.addEventListener('DOMContentLoaded', () => {
//     const sendButtons = document.querySelectorAll('.send');

//     sendButtons.forEach(button => {
//         button.addEventListener('click', async function (e) {
//             e.preventDefault();

//             const parentContainer = this.closest('.partidos__partido');
//             if (!parentContainer) {
//                 alert('Error: No se pudo encontrar el contenedor del partido.');
//                 return;
//             }

//             const inputElement = parentContainer.querySelector('input[name="id_arbi"]');
//             if (!inputElement) {
//                 alert('Error: No se encontró el input del árbitro.');
//                 return;
//             }

//             const arbitroId = inputElement.value;
//             const partidoId = parentContainer.querySelector('input[name="id"]').value;

//             await enviarSolicitud(partidoId, arbitroId);

//             async function enviarSolicitud(partidoId, arbitroId) {
//                 try {
//                     const respuesta = await fetch('/admin/partidos/enviar_partido', {
//                         method: 'POST',
//                         headers: {
//                             'Content-Type': 'application/json',
//                         },
//                         body: JSON.stringify({ arbitro: arbitroId, partido: partidoId }),
//                     });

//                     if (!respuesta.ok) {
//                         alert('Error en el servidor.');
//                         return;
//                     }

//                     const data = await respuesta.json();

//                     if (data.success) {
//                         alert(data.message || '¡Partido designado y enviado!');

//                         // Actualiza el nombre del árbitro si hay un contenedor específico
//                         const arbitroNombre = inputElement.getAttribute('data-arbitro-nombre') || 'Árbitro asignado';
//                         const partidoArbitroContainer = parentContainer.querySelector('.partidos__partido--arbitro');
//                         if (partidoArbitroContainer) {
//                             partidoArbitroContainer.innerHTML = `
//                                 <span class="arbitro-nombre">${arbitroNombre}</span>
//                             `;
//                         }

//                         // Actualiza la imagen dentro del <picture>
//                         const pictureElement = parentContainer.querySelector('picture');
//                         if (pictureElement) {
//                             const estadoImage = pictureElement.querySelector('#send');
//                             if (estadoImage) {
//                                 estadoImage.src = "/build/img/nombrado.png";
//                                 estadoImage.alt = "Botón para nombrar";

//                                 // Actualiza los atributos `srcset` en <source>
//                                 const sources = pictureElement.querySelectorAll('source');
//                                 sources.forEach(source => {
//                                     const type = source.getAttribute('type');
//                                     if (type === 'image/avif') {
//                                         source.srcset = "/build/img/nombrado.avif";
//                                     } else if (type === 'image/webp') {
//                                         source.srcset = "/build/img/nombrado.webp";
//                                     }
//                                 });
//                             }
//                         }
//                     } else {
//                         alert(data.message || 'Hubo un error al enviar los datos.');
//                     }
//                 } catch (error) {
//                     console.error('Error en la comunicación con el servidor:', error);
//                     alert('Error en la comunicación con el servidor.');
//                 }
//             }
//         });
//     });

//     // Manejo de desplazamiento al partido desde la URL
//     const urlParams = new URLSearchParams(window.location.search);
//     const partidoId = urlParams.get('partidoId');

//     if (partidoId) {
//         const partidoElement = document.querySelector(`.partidos__partido[data-partido-id="${partidoId}"]`);
//         if (partidoElement) {
//             partidoElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
//             const newUrl = window.location.href.split('?')[0];
//             window.history.replaceState({}, document.title, newUrl);
//         }
//     }
// });

document.addEventListener('DOMContentLoaded', () => {
    const sendButtons = document.querySelectorAll('.send');

    sendButtons.forEach(button => {
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

            await enviarSolicitud(partidoId, arbitroId);

            async function enviarSolicitud(partidoId, arbitroId) {
                try {
                    const respuesta = await fetch('/admin/partidos/enviar_partido', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ arbitro: arbitroId, partido: partidoId }),
                    });

                    if (!respuesta.ok) {
                        alert('Error en el servidor.');
                        return;
                    }

                    const data = await respuesta.json();

                    if (data.success) {
                        alert(data.message || '¡Partido designado y enviado!');

                        // Recarga la página para reflejar los cambios
                        location.reload();
                    } else {
                        alert(data.message || 'Hubo un error al enviar los datos.');
                    }
                } catch (error) {
                    console.error('Error en la comunicación con el servidor:', error);
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
