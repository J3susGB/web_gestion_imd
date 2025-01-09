// document.addEventListener('DOMContentLoaded', () => {
//     const buttons = document.querySelectorAll('.alert_restablecer');

//     buttons.forEach(button => {
//         button.addEventListener('click', function () {
//             const form = button.closest('form'); // Obtiene el formulario más cercano al botón
//             const formData = new FormData(form); // Crea un objeto FormData con los datos del formulario

//             // URL específica para el envío
//             const url = '/admin/perfiles/restablecer_password';

//             fetch(url, {
//                 method: 'POST',
//                 body: formData,
//             })
//             .then(response => {
//                 if (!response.ok) {
//                     throw new Error('Error en la solicitud'); // Manejo de errores si la respuesta no es OK
//                 }
//                 return response.json(); // Procesa la respuesta como JSON
//             })
//             .then(data => {
//                 if (data.success) {
//                     mostrar_alerta(data.message); // Muestra alerta de éxito
//                 } else {
//                     mostrar_error(data.message); // Muestra alerta de error
//                 }
//             })
//             .catch(error => {
//                 console.error('Hubo un problema con la solicitud:', error); // Muestra el error en la consola
//                 mostrar_error('Hubo un problema inesperado.');
//             });
//         });
//     });

//     function mostrar_alerta(message) {
//         Swal.fire({
//             icon: "success", // Ícono de éxito
//             title: message, // Mensaje dinámico
//             background: "#4e4d4b",  // Fondo personalizado de la alerta
//             color: "#DDDDDD",        // Color del texto
//             customClass: {
//                 popup: 'custom-popup',     // Clase personalizada para el popup
//                 title: 'custom-title',     // Clase personalizada para el título
//                 confirmButton: 'custom-button'  // Clase personalizada para el botón de confirmación
//             },
//             showConfirmButton: true, // Mostrar botón de confirmación
//             confirmButtonColor: "#71B100",  // Color para el botón de confirmación
//             timer: 3000, // Tiempo automático de cierre (opcional)
//         });
//     }

//     function mostrar_error(message) {
//         Swal.fire({
//             icon: "error", // Ícono de error
//             title: message, // Mensaje dinámico
//             background: "#4e4d4b",  // Fondo personalizado
//             color: "#DDDDDD",        // Color del texto
//             showConfirmButton: true, // Mostrar botón de confirmación
//             confirmButtonColor: "#FF0000",  // Color para el botón de confirmación
//         });
//     }
// });
