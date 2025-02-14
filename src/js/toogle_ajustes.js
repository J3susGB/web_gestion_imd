// document.addEventListener("DOMContentLoaded", function () {
//     if (document.querySelector('.ajustes')) {

//         // Capturamos valores iniciales desde la vista
//         function obtenerValorInicial(id) {
//             const elemento = document.getElementById(id);
//             if (!elemento) return null;
//             const texto = elemento.textContent.trim();
//             return texto === '-' ? null : parseInt(texto, 10);
//         }

//         // Objeto valores, inicializado con los valores de la vista
//         const valores = {
//             'futbol-partidos': obtenerValorInicial('futbol-partidos'),
//             'futbol-sxjx': obtenerValorInicial('futbol-sxjx'),
//             'sala-partidos': obtenerValorInicial('sala-partidos'),
//             'sala-sxjx': obtenerValorInicial('sala-sxjx')
//         };

//         function actualizarNumero(tipo) {
//             const elemento = document.getElementById(tipo);
//             const botonMenos = document.getElementById(`menos-${tipo}`);
//             if (!elemento || !botonMenos) return;

//             if (valores[tipo] === null) {
//                 elemento.textContent = '-';
//                 botonMenos.disabled = true;
//             } else {
//                 elemento.textContent = valores[tipo];
//                 botonMenos.disabled = (valores[tipo] === 0);
//             }
//         }

//         function decrementar(tipo) {
//             if (valores[tipo] === null) {
//                 valores[tipo] = 0; // Inicializar en 0 si estaba en null
//             } else if (valores[tipo] > 0) {
//                 valores[tipo]--;
//             }
//             actualizarNumero(tipo);
//         }

//         function incrementar(tipo) {
//             if (valores[tipo] === null || valores[tipo] === 0) {
//                 valores[tipo] = 1; // Si está en null o 0, comenzar desde 1
//             } else {
//                 valores[tipo]++;
//             }
//             actualizarNumero(tipo);
//         }

//         const botones = [
//             'menos-futbol-partidos', 'mas-futbol-partidos', 'menos-futbol-sxjx', 'mas-futbol-sxjx',
//             'menos-sala-partidos', 'mas-sala-partidos', 'menos-sala-sxjx', 'mas-sala-sxjx'
//         ];

//         botones.forEach(id => {
//             const boton = document.getElementById(id);
//             if (boton) {
//                 boton.addEventListener('click', (event) => {
//                     event.preventDefault();
//                     if (id.includes('menos')) {
//                         decrementar(id.replace('menos-', ''));
//                     } else {
//                         incrementar(id.replace('mas-', ''));
//                     }
//                 });
//             }
//         });

//         // Guardar datos de Fútbol
//         const guardarFutbol = document.getElementById('guardar-futbol');
//         if (guardarFutbol) {
//             guardarFutbol.addEventListener('click', () => {
//                 fetch('/admin/acciones/guardar_futbol', {
//                     method: 'POST',
//                     headers: { 'Content-Type': 'application/json' },
//                     body: JSON.stringify({
//                         tipo: 'futbol',
//                         maxPartidos: valores['futbol-partidos'] ?? '-',
//                         maxSXJX: valores['futbol-sxjx'] ?? '-'
//                     })
//                 })
//                 .then(response => response.json())
//                 .then(data => alert(data.message || 'Restricción aplicada con éxito'))
//                 .catch(error => console.error('Error:', error));
//             });
//         }

//         // Guardar datos de Sala
//         const guardarSala = document.getElementById('guardar-sala');
//         if (guardarSala) {
//             guardarSala.addEventListener('click', () => {
//                 fetch('/admin/acciones/guardar_sala', {
//                     method: 'POST',
//                     headers: { 'Content-Type': 'application/json' },
//                     body: JSON.stringify({
//                         tipo: 'sala',
//                         maxPartidos: valores['sala-partidos'] ?? '-',
//                         maxSXJX: valores['sala-sxjx'] ?? '-'
//                     })
//                 })
//                 .then(response => response.json())
//                 .then(data => alert(data.message || 'Restricción aplicada con éxito'))
//                 .catch(error => console.error('Error:', error));
//             });
//         }

//         // Inicializar valores en la vista
//         actualizarNumero('futbol-partidos');
//         actualizarNumero('futbol-sxjx');
//         actualizarNumero('sala-partidos');
//         actualizarNumero('sala-sxjx');
//     }
// });

document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector('.ajustes')) {

        // Capturar valores iniciales desde la vista
        function obtenerValorInicial(id) {
            const elemento = document.getElementById(id);
            if (!elemento) return null;
            const texto = elemento.textContent.trim();
            return texto === '-' ? null : parseInt(texto, 10);
        }

        // Objeto valores, inicializado con los valores de la vista
        const valores = {
            'futbol-partidos': obtenerValorInicial('futbol-partidos'),
            'futbol-sxjx': obtenerValorInicial('futbol-sxjx'),
            'sala-partidos': obtenerValorInicial('sala-partidos'),
            'sala-sxjx': obtenerValorInicial('sala-sxjx')
        };

        function actualizarNumero(tipo) {
            const elemento = document.getElementById(tipo);
            const botonMenos = document.getElementById(`menos-${tipo}`);
            if (!elemento || !botonMenos) return;

            if (valores[tipo] === null) {
                elemento.textContent = '-';
                botonMenos.disabled = true;
            } else {
                elemento.textContent = valores[tipo];
                botonMenos.disabled = (valores[tipo] === 0);
            }
        }

        function decrementar(tipo) {
            if (valores[tipo] === null) {
                valores[tipo] = 0; // Inicializar en 0 si estaba en null
            } else if (valores[tipo] > 0) {
                valores[tipo]--;
            }
            actualizarNumero(tipo);
        }

        function incrementar(tipo) {
            if (valores[tipo] === null || valores[tipo] === 0) {
                valores[tipo] = 1; // Si está en null o 0, comenzar desde 1
            } else {
                valores[tipo]++;
            }
            actualizarNumero(tipo);
        }

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
                        maxPartidos: valores['futbol-partidos'] ?? '-',
                        maxSXJX: valores['futbol-sxjx'] ?? '-'
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
                        maxPartidos: valores['sala-partidos'] ?? '-',
                        maxSXJX: valores['sala-sxjx'] ?? '-'
                    })
                })
                .then(response => response.json())
                .then(data => alert(data.message || 'Restricción aplicada con éxito'))
                .catch(error => console.error('Error:', error));
            });
        }

        // Inicializar valores en la vista
        actualizarNumero('futbol-partidos');
        actualizarNumero('futbol-sxjx');
        actualizarNumero('sala-partidos');
        actualizarNumero('sala-sxjx');
    }
});

