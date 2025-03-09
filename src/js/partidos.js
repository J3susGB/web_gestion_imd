// // Autocompletado de árbitros
// document.addEventListener('DOMContentLoaded', function () {
//     // Delegación de eventos
//     document.body.addEventListener('input', function (event) {
//         if (event.target && event.target.id === 'arbitro_autocomplete') {
//             const input = event.target;
//             const suggestionsList = input.nextElementSibling; // La lista de sugerencias está después del input
//             const query = input.value.trim();

//             // Búsqueda solo si hay 2 o más caracteres
//             if (query.length >= 2) {
//                 fetch(`/admin/partidos/autocompletar_arbitros`, {
//                     method: 'POST',
//                     headers: {
//                         'Content-Type': 'application/x-www-form-urlencoded',
//                     },
//                     body: `q=${encodeURIComponent(query)}`
//                 })
//                 .then(response => {
//                     if (!response.ok) {
//                         throw new Error(`Error HTTP: ${response.status}`);
//                     }
//                     return response.json();
//                 })
//                 .then(data => {
//                     console.log('Respuesta del servidor:', data); // Depuración
//                     suggestionsList.innerHTML = ''; // Limpiar sugerencias

//                     if (data.length > 0) {
//                         data.forEach(árbitro => {
//                             const li = document.createElement('li');
//                             li.textContent = `${árbitro.apellido1} ${árbitro.apellido2}, ${árbitro.nombre}`;
//                             li.setAttribute('data-id', árbitro.id);
//                             suggestionsList.appendChild(li);

//                             li.addEventListener('click', function () {
//                                 input.value = `${árbitro.apellido1} ${árbitro.apellido2}, ${árbitro.nombre}`;
//                                 input.setAttribute('data-arbitro-id', árbitro.id);
//                                 suggestionsList.innerHTML = '';
//                             });
//                         });
//                     } else {
//                         suggestionsList.innerHTML = '<li>No se encontraron árbitros.</li>';
//                     }
//                 })
//                 .catch(error => {
//                     console.error('Error al obtener los árbitros:', error);
//                     suggestionsList.innerHTML = `<li>Error: ${error.message}</li>`;
//                 });
//             } else {
//                 suggestionsList.innerHTML = ''; // Limpiar si hay menos de 2 caracteres
//             }
//         }
//     });

//     // Cerrar sugerencias al hacer clic fuera
//     document.addEventListener('click', function (event) {
//         if (!event.target.closest('.partidos__campo')) {
//             const suggestionLists = document.querySelectorAll('.autocomplete-suggestions');
//             suggestionLists.forEach(suggestionsList => {
//                 suggestionsList.innerHTML = '';
//             });
//         }
//     });
// });

document.addEventListener('DOMContentLoaded', function () {

    // ✅ Delegación de eventos: capturamos cualquier input con id 'arbitro_autocomplete'
    document.body.addEventListener('input', function (event) {
        const input = event.target;

        // Asegurarse que es uno de los inputs arbitro_autocomplete (mismo id repetido)
        if (input && input.id === 'arbitro_autocomplete') {

            // Buscar la lista de sugerencias justo después del input
            const suggestionsList = input.nextElementSibling;

            if (!suggestionsList || !suggestionsList.classList.contains('autocomplete-suggestions')) {
                console.warn('No se encontró la lista de sugerencias para este input.');
                return;
            }

            const query = input.value.trim();

            // 🔥 Aquí capturamos modalidad y categoria desde el input
            const modalidad = input.getAttribute('data-modalidad');
            const categoria = input.getAttribute('data-categoria');

            if (query.length >= 2) {
                fetch(`/admin/partidos/autocompletar_arbitros`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `q=${encodeURIComponent(query)}&modalidad=${encodeURIComponent(modalidad)}&categoria=${encodeURIComponent(categoria)}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta del servidor:', data); // Depuración
                    suggestionsList.innerHTML = ''; // Limpiar sugerencias

                    if (data.length > 0) {
                        data.forEach(árbitro => {
                            const li = document.createElement('li');
                            li.textContent = `${árbitro.apellido1} ${árbitro.apellido2}, ${árbitro.nombre}`;

                            if (árbitro.restringido) {
                                li.classList.add('arbitro-restringido');
                                li.title = árbitro.detalle.join('\n'); // Motivos de la restricción
                            } else {
                                li.addEventListener('click', function () {
                                    input.value = `${árbitro.apellido1} ${árbitro.apellido2}, ${árbitro.nombre}`;
                                    input.setAttribute('data-arbitro-id', árbitro.id);
                                    suggestionsList.innerHTML = '';
                                });
                            }

                            suggestionsList.appendChild(li);
                        });
                    } else {
                        suggestionsList.innerHTML = '<li>No se encontraron árbitros.</li>';
                    }
                })
                .catch(error => {
                    console.error('Error al obtener los árbitros:', error);
                    suggestionsList.innerHTML = `<li>Error: ${error.message}</li>`;
                });
            } else {
                suggestionsList.innerHTML = ''; // Limpiar si menos de 2 caracteres
            }
        }
    });

    // ✅ Cerrar sugerencias al hacer clic fuera
    document.addEventListener('click', function (event) {
        // Solo cerrar si no se hace clic dentro de un campo de autocompletar
        const inputs = document.querySelectorAll('#arbitro_autocomplete');

        inputs.forEach(input => {
            const campo = input.closest('.partidos__campo');

            if (campo && !campo.contains(event.target)) {
                const suggestionsList = campo.querySelector('.autocomplete-suggestions');

                if (suggestionsList) {
                    suggestionsList.innerHTML = '';
                }
            }
        });
    });
});

