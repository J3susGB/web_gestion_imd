// // Inicialización de valores
// const valores = {
//     'futbol-partidos': null,
//     'futbol-sxjx': null,
//     'sala-partidos': null,
//     'sala-sxjx': null
// };

// // Actualizar el valor en el HTML
// function actualizarNumero(tipo) {
//     const elemento = document.getElementById(tipo);
//     const botonMenos = document.getElementById(`menos-${tipo}`);
//     if (valores[tipo] === null) {
//         elemento.textContent = '-'; // Mostrar "-" si el valor es null
//         botonMenos.disabled = true; // Deshabilitar botón de "menos"
//     } else {
//         elemento.textContent = valores[tipo];
//         botonMenos.disabled = false; // Habilitar botón de "menos"
//     }
// }

// // Funciones para incrementar y decrementar
// function decrementar(tipo) {
//     if (valores[tipo] === 1) {
//         valores[tipo] = null; // Cambia a null si llega a 1 y se reduce
//     } else if (valores[tipo] !== null) {
//         valores[tipo]--;
//     }
//     actualizarNumero(tipo);
// }

// function incrementar(tipo) {
//     if (valores[tipo] === null) {
//         valores[tipo] = 1; // Inicializa desde null a 1 si se hace clic en más
//     } else {
//         valores[tipo]++;
//     }
//     actualizarNumero(tipo);
// }

// // Añadir eventos a los botones de más y menos para Fútbol
// document.getElementById('menos-futbol-partidos').addEventListener('click', (event) => {
//     event.preventDefault(); // Evita comportamientos por defecto
//     decrementar('futbol-partidos');
// });

// document.getElementById('mas-futbol-partidos').addEventListener('click', (event) => {
//     event.preventDefault(); // Evita comportamientos por defecto
//     incrementar('futbol-partidos');
// });

// document.getElementById('menos-futbol-sxjx').addEventListener('click', (event) => {
//     event.preventDefault(); // Evita comportamientos por defecto
//     decrementar('futbol-sxjx');
// });

// document.getElementById('mas-futbol-sxjx').addEventListener('click', (event) => {
//     event.preventDefault(); // Evita comportamientos por defecto
//     incrementar('futbol-sxjx');
// });

// // Botón guardar para Fútbol
// document.getElementById('guardar-futbol').addEventListener('click', () => {
//     const datos = {
//         tipo: 'futbol',
//         maxPartidos: valores['futbol-partidos'] === null ? '-' : valores['futbol-partidos'],
//         maxSXJX: valores['futbol-sxjx'] === null ? '-' : valores['futbol-sxjx']
//     };

//     fetch('/admin/acciones/guardar_futbol', {
//         method: 'POST',
//         headers: { 'Content-Type': 'application/json' },
//         body: JSON.stringify(datos)
//     })
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Error en la solicitud');
//             }
//             return response.json();
//         })
//         .then(data => {
//             if (data.success) {
//                 alert(data.message || 'Restricción aplicada con éxito.');
//             } else {
//                 alert(data.message || 'Error al guardar los datos de Fútbol.');
//             }
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             alert('Ocurrió un error al procesar la solicitud.');
//         });
// });

// // Añadir eventos a los botones de más y menos para Sala
// document.getElementById('menos-sala-partidos').addEventListener('click', (event) => {
//     event.preventDefault(); // Evita comportamientos por defecto
//     decrementar('sala-partidos');
// });

// document.getElementById('mas-sala-partidos').addEventListener('click', (event) => {
//     event.preventDefault(); // Evita comportamientos por defecto
//     incrementar('sala-partidos');
// });

// document.getElementById('menos-sala-sxjx').addEventListener('click', (event) => {
//     event.preventDefault(); // Evita comportamientos por defecto
//     decrementar('sala-sxjx');
// });

// document.getElementById('mas-sala-sxjx').addEventListener('click', (event) => {
//     event.preventDefault(); // Evita comportamientos por defecto
//     incrementar('sala-sxjx');
// });

// // Botón guardar para Sala
// document.getElementById('guardar-sala').addEventListener('click', () => {
//     const datos = {
//         tipo: 'sala',
//         maxPartidos: valores['sala-partidos'] === null ? '-' : valores['sala-partidos'],
//         maxSXJX: valores['sala-sxjx'] === null ? '-' : valores['sala-sxjx']
//     };

//     fetch('/admin/acciones/guardar_sala', {
//         method: 'POST',
//         headers: { 'Content-Type': 'application/json' },
//         body: JSON.stringify(datos)
//     })
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Error en la solicitud');
//             }
//             return response.json();
//         })
//         .then(data => {
//             if (data.success) {
//                 alert(data.message || 'Restricción aplicada con éxito');
//             } else {
//                 alert(data.message || 'Error al guardar los datos de Sala.');
//             }
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             alert('Ocurrió un error al procesar la solicitud.');
//         });
// });

// // Inicializar la vista con los valores actuales
// actualizarNumero('futbol-partidos');
// actualizarNumero('futbol-sxjx');
// actualizarNumero('sala-partidos');
// actualizarNumero('sala-sxjx');

document.addEventListener("DOMContentLoaded", function () {
    // Comprobamos que la vista 2 está cargada buscando un elemento clave
    if (document.querySelector('.ajustes')) {
        const valores = {
            'futbol-partidos': null,
            'futbol-sxjx': null,
            'sala-partidos': null,
            'sala-sxjx': null
        };

        function actualizarNumero(tipo) {
            const elemento = document.getElementById(tipo);
            const botonMenos = document.getElementById(`menos-${tipo}`);
            if (!elemento || !botonMenos) return; // Si no existe, no ejecutamos nada

            if (valores[tipo] === null) {
                elemento.textContent = '-';
                botonMenos.disabled = true;
            } else {
                elemento.textContent = valores[tipo];
                botonMenos.disabled = false;
            }
        }

        function decrementar(tipo) {
            if (valores[tipo] === 1) {
                valores[tipo] = null;
            } else if (valores[tipo] !== null) {
                valores[tipo]--;
            }
            actualizarNumero(tipo);
        }

        function incrementar(tipo) {
            if (valores[tipo] === null) {
                valores[tipo] = 1;
            } else {
                valores[tipo]++;
            }
            actualizarNumero(tipo);
        }

        // Solo añadimos eventos si los botones existen
        const botones = [
            'menos-futbol-partidos', 'mas-futbol-partidos', 'menos-futbol-sxjx', 'mas-futbol-sxjx',
            'menos-sala-partidos', 'mas-sala-partidos', 'menos-sala-sxjx', 'mas-sala-sxjx'
        ];

        botones.forEach(id => {
            const boton = document.getElementById(id);
            if (boton) {
                boton.addEventListener('click', (event) => {
                    event.preventDefault();
                    if (id.includes('menos')) {
                        decrementar(id.replace('menos-', ''));
                    } else {
                        incrementar(id.replace('mas-', ''));
                    }
                });
            }
        });

        // Guardar datos de Fútbol
        const guardarFutbol = document.getElementById('guardar-futbol');
        if (guardarFutbol) {
            guardarFutbol.addEventListener('click', () => {
                fetch('/admin/acciones/guardar_futbol', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        tipo: 'futbol',
                        maxPartidos: valores['futbol-partidos'] || '-',
                        maxSXJX: valores['futbol-sxjx'] || '-'
                    })
                })
                .then(response => response.json())
                .then(data => alert(data.message || 'Restricción aplicada con éxito'))
                .catch(error => console.error('Error:', error));
            });
        }

        // Guardar datos de Sala
        const guardarSala = document.getElementById('guardar-sala');
        if (guardarSala) {
            guardarSala.addEventListener('click', () => {
                fetch('/admin/acciones/guardar_sala', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        tipo: 'sala',
                        maxPartidos: valores['sala-partidos'] || '-',
                        maxSXJX: valores['sala-sxjx'] || '-'
                    })
                })
                .then(response => response.json())
                .then(data => alert(data.message || 'Restricción aplicada con éxito'))
                .catch(error => console.error('Error:', error));
            });
        }

        // Inicializar los valores si los elementos existen
        actualizarNumero('futbol-partidos');
        actualizarNumero('futbol-sxjx');
        actualizarNumero('sala-partidos');
        actualizarNumero('sala-sxjx');
    }
});
