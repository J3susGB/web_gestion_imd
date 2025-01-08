
document.addEventListener('DOMContentLoaded', function () {
    // Contar los árbitros visibles en la pantalla
    function contarArbitros() {
        // Obtenemos todos los contenedores de árbitros
        const arbitrosVisibles = document.querySelectorAll('.arbitros__card');

        // Actualizamos el contador en la pantalla
        const contarElement = document.querySelector('.contar_arbitros h3');
        contarElement.textContent = `${arbitrosVisibles.length} personas`;
    }

    // Llamamos a contarArbitros para mostrar el número al cargar la página
    contarArbitros();

    // Event listener para cuando los filtros cambien
    const filtros = document.querySelectorAll('input[type="text"], input[type="radio"], button');

    filtros.forEach(filtro => {
        filtro.addEventListener('change', function () {
            // Llamamos nuevamente a contarArbitros cuando se cambien los filtros
            contarArbitros();
        });
    });
});

