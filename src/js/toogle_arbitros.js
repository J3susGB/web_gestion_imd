// (function() {
//     document.querySelectorAll('.check_arbis').forEach(toggle_arbitros => {
//         toggle_arbitros.addEventListener('change', async function () {
//             const arbitro_id = this.getAttribute('data-id');
//             const activo = this.checked ? 1 : 0;

//             // console.log(`Toggle changed for ID: ${arbitro_id}, Active: ${activo}`); // Para depuración

//             try {
//                 const respuesta = await fetch('/admin/arbitros/actualizar-estado', {
//                     method: 'POST',
//                     headers: {
//                         'Content-Type': 'application/json',
//                     },
//                     body: JSON.stringify({ id: arbitro_id, activo: activo }),
//                 });

//                 // Verifica si la respuesta es exitosa
//                 if (respuesta.ok) {
//                     const resultado_arbis = await respuesta.json(); // Convierte la respuesta a JSON

//                     // console.log('Respuesta recibida:', resultado_arbis); // Depuración

//                     // Solo mostrar alerta si resultado_arbis.success es verdadero
//                     if (resultado_arbis.success) {
//                         // console.log('Estado actualizado con éxito');
//                         // alert('Estado actualizado con éxito');
                        
//                         // Actualizar el estado visual en la UI inmediatamente
//                         // Si el estado cambia a activo (1), el toggle se marca, si no, se desmarca.
//                         this.checked = activo === 1; 

//                     } else {
//                         // console.log('Hubo un error en el servidor al actualizar el estado');
//                         alert('Error actualizando el estado');
//                         // Si hubo un error, revertir el estado del checkbox
//                         this.checked = !this.checked; 
//                     }
//                 } else {
//                     throw new Error('Error en la respuesta del servidor');
//                 }
//             } catch (error) {
//                 console.error('Error en la comunicación con el servidor:', error);
//                 alert('Error en el servidor');
//                 // Si hay un error en la comunicación, revertir el estado
//                 this.checked = !this.checked; 
//             }
//         });
//     });
// })();

(function() {
    // Solo ejecuta el script si existen checkboxes de árbitros
    if (document.querySelector('.check_arbis')) {
        document.querySelectorAll('.check_arbis').forEach(toggle_arbitros => {
            toggle_arbitros.addEventListener('change', async function () {
                const arbitro_id = this.getAttribute('data-id');
                const activo = this.checked ? 1 : 0;

                try {
                    const respuesta = await fetch('/admin/arbitros/actualizar-estado', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: arbitro_id, activo: activo }),
                    });

                    if (respuesta.ok) {
                        const resultado_arbis = await respuesta.json();
                        if (resultado_arbis.success) {
                            this.checked = activo === 1; 
                        } else {
                            alert('Error actualizando el estado');
                            this.checked = !this.checked;
                        }
                    } else {
                        throw new Error('Error en la respuesta del servidor');
                    }
                } catch (error) {
                    console.error('Error en la comunicación con el servidor:', error);
                    alert('Error en el servidor');
                    this.checked = !this.checked;
                }
            });
        });
    }
})();
