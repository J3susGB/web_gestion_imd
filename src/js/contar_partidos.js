document.addEventListener('DOMContentLoaded', function () {
    // Contar los partidos visibles en la pantalla
    function contarPartidos() {
        // Obtenemos todos los contenedores de partidos visibles que NO son filtros
        const partidosVisibles = document.querySelectorAll('.partidos__partido');
        const partidosFiltrados = Array.from(partidosVisibles).filter(partido => {
            return partido.offsetParent !== null && 
                   !partido.classList.contains('partidos__filtro') && 
                   partido.querySelector('.partidos__partido--id');
        });

        // Actualizamos el contador en la pantalla
        const contarElement = document.querySelector('.contar_partidos h3');
        contarElement.textContent = `${partidosFiltrados.length} partidos`;
    }

    // Llamamos a contarPartidos para mostrar el número al cargar la página
    contarPartidos();

    // Event listener para cuando los filtros cambien
    const filtros = document.querySelectorAll('input[type="text"], input[type="radio"], select, button.partidos__boton-filtro');

    filtros.forEach(filtro => {
        filtro.addEventListener('change', function () {
            // Llamamos nuevamente a contarPartidos cuando se cambien los filtros
            contarPartidos();
        });
    });

    // Validación para el botón "Borrar filtros"
    const botonRestablecer = document.querySelector('.partidos__boton-filtro');
    if (botonRestablecer) {
        botonRestablecer.addEventListener('click', function () {
            // Limpiamos los filtros
            document.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
            document.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);
            document.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

            // Recontamos los partidos visibles
            contarPartidos();
        });
    }
});

