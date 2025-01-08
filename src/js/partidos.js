// document.addEventListener('DOMContentLoaded', function () {
//     // Delegación de eventos: aplicar a todos los inputs con la clase 'arbitro_autocomplete'
//     document.body.addEventListener('input', function (event) {
//         // Verificar si el evento es para un input con id 'arbitro_autocomplete'
//         if (event.target && event.target.id === 'arbitro_autocomplete') {
//             const input = event.target;
//             const suggestionsList = input.nextElementSibling; // La lista de sugerencias está justo después del input
//             const query = input.value.trim();

//             // Log para asegurarnos de que el evento se está disparando correctamente
//             console.log('Evento de input detectado para:', input.id);

//             // Realizar búsqueda solo si hay al menos 2 caracteres
//             if (query.length >= 2) {
//                 console.log('Consultando árbitros con la query:', query); // Verifica la consulta

//                 // Realizar una petición AJAX al servidor para obtener los árbitros que coinciden
//                 fetch(`/admin/partidos/autocompletar_arbitros`, {
//                     method: 'POST',
//                     headers: {
//                         'Content-Type': 'application/x-www-form-urlencoded',
//                     },
//                     body: `q=${encodeURIComponent(query)}` // Enviar la consulta con múltiples palabras
//                 })
//                 .then(response => {
//                     if (!response.ok) {
//                         throw new Error('Error al obtener la respuesta del servidor');
//                     }
//                     return response.json();
//                 })
//                 .then(data => {
//                     suggestionsList.innerHTML = ''; // Limpiar las sugerencias previas

//                     if (data.length > 0) {
//                         data.forEach(árbitro => {
//                             const li = document.createElement('li');
//                             li.textContent = `${árbitro.apellido1} ${árbitro.apellido2}, ${árbitro.nombre}`;
//                             li.setAttribute('data-id', árbitro.id);
//                             suggestionsList.appendChild(li);

//                             // Añadir evento de selección al hacer clic en un árbitro
//                             li.addEventListener('click', function () {
//                                 input.value = `${árbitro.apellido1} ${árbitro.apellido2}, ${árbitro.nombre}`;
//                                 input.setAttribute('data-arbitro-id', árbitro.id); // Guardamos el id del árbitro
//                                 suggestionsList.innerHTML = ''; // Limpiar las sugerencias
//                             });
//                         });
//                     } else {
//                         suggestionsList.innerHTML = '<li>No se encontraron árbitros.</li>';
//                     }
//                 })
//                 .catch(error => {
//                     console.error('Error al obtener los árbitros:', error);
//                     suggestionsList.innerHTML = `<li>Error: ${error.message}</li>`; // Mostrar el error en las sugerencias
//                 });
//             } else {
//                 suggestionsList.innerHTML = ''; // Limpiar las sugerencias si el texto es menor de 2 caracteres
//             }
//         }
//     });

//     // Cerrar la lista de sugerencias al hacer clic fuera del input
//     document.addEventListener('click', function (event) {
//         if (!event.target.closest('.partidos__campo').contains(event.target)) {
//             // Si el clic no es sobre el input ni sobre la lista de sugerencias
//             const suggestionLists = document.querySelectorAll('.autocomplete-suggestions');
//             suggestionLists.forEach(suggestionsList => {
//                 suggestionsList.innerHTML = ''; // Limpiar todas las sugerencias
//             });
//         }
//     });
// });

// Autocompletado de árbitros
document.addEventListener('DOMContentLoaded', function () {
    // Delegación de eventos
    document.body.addEventListener('input', function (event) {
        if (event.target && event.target.id === 'arbitro_autocomplete') {
            const input = event.target;
            const suggestionsList = input.nextElementSibling; // La lista de sugerencias está después del input
            const query = input.value.trim();

            // Búsqueda solo si hay 2 o más caracteres
            if (query.length >= 2) {
                fetch(`/admin/partidos/autocompletar_arbitros`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `q=${encodeURIComponent(query)}`
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
                            li.setAttribute('data-id', árbitro.id);
                            suggestionsList.appendChild(li);

                            li.addEventListener('click', function () {
                                input.value = `${árbitro.apellido1} ${árbitro.apellido2}, ${árbitro.nombre}`;
                                input.setAttribute('data-arbitro-id', árbitro.id);
                                suggestionsList.innerHTML = '';
                            });
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
                suggestionsList.innerHTML = ''; // Limpiar si hay menos de 2 caracteres
            }
        }
    });

    // Cerrar sugerencias al hacer clic fuera
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.partidos__campo')) {
            const suggestionLists = document.querySelectorAll('.autocomplete-suggestions');
            suggestionLists.forEach(suggestionsList => {
                suggestionsList.innerHTML = '';
            });
        }
    });
});
