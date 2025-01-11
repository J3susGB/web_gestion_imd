// Esperar a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function () {
    // Valores para cambiar el texto
    var valores = ["1.00", "0.50", "0.25"];
    var coloresTexto = {
        "1.00": "#30475E", // Azul oscuro
        "0.50": "#E08709", // Naranja
        "0.25": "#F05454"  // Rojo claro
    };

    // Delegación de eventos en el contenedor padre
    document.addEventListener("click", function (event) {
        // Verificar si el clic ocurrió en un botón con la clase 'unidad-boton'
        if (event.target.closest(".unidad-boton")) {
            var boton = event.target.closest(".unidad-boton"); // El botón específico clicado
            var texto = boton.querySelector("#unidad-texto"); // Párrafo interno del botón

            // Verificar y establecer el índice del botón
            var indice = parseInt(boton.dataset.indice || 0, 10);
            indice = (indice + 1) % valores.length; // Incrementar el índice cíclicamente
            boton.dataset.indice = indice; // Guardar el índice actualizado en el botón

            // Cambiar el texto y el color
            texto.innerHTML = valores[indice];
            texto.style.color = coloresTexto[valores[indice]];
        }
    });

    // Inicializar el estado para todos los botones al cargar la página
    document.querySelectorAll(".unidad-boton").forEach(function (boton) {
        var texto = boton.querySelector("#unidad-texto"); // Párrafo interno
        var valorActual = texto.innerHTML.trim(); // Obtener el valor actual del texto
        var indiceActual = valores.indexOf(valorActual); // Buscar el índice del valor actual

        if (indiceActual !== -1) {
            texto.style.color = coloresTexto[valorActual]; // Aplicar el color correspondiente
            boton.dataset.indice = indiceActual; // Sincronizar el índice con el valor actual
        } else {
            boton.dataset.indice = 0; // Si no se encuentra el valor, usar el primer índice
        }
    });
});
