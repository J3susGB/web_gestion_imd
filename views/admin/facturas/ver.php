<div class="dashboard__caja">
    <a href="/admin/facturas" title="Volver a atrás">
        <picture class="dashboard__caja--icono">
            <source srcset="/build/img/arrow.avif" type="image/avif">
            <source srcset="/build/img/arrow.webp" type="image/webp">
            <img loading="lazy" width="20" height="20" src="/build/img/arrow.png" alt="botón para ir a carga masiva de árbitros">
        </picture>
    </a>
</div>

<h2 class="dashboard__heading"><?php echo $titulo ?></h2>

<div class="dashboard__caja-excel">
    <form method="POST" action=<?php echo "/admin/facturas/generar_excel_jornada?jornada_editada=" . $jornada_editada; ?> id="exportarExcelForm">
        <input type="hidden" id="<?php echo $titulo; ?>">
        <button type="submit" class="boton-exportar-excel">
            <picture>
                <source srcset="/build/img/excel.avif" type="image/avif">
                <source srcset="/build/img/excel.webp" type="image/webp">
                <img loading="lazy" width="20" height="20" src="/build/img/excel.png" alt="botón para exportar a Excel">
            </picture>
        </button>
    </form>
</div>


<?php foreach ($designaciones as $d) { ?>
    <form data-categoria="<?php echo $d->categoria; ?>" method="post" action="/admin/facturas/cambiar_unidad" id="<?php echo $d->id_partido; ?>" class="partidos__partido" data-partido-id="<?php echo $d->id_partido; ?>">
        <div class="partidos__partido--id1">
            <div class="partidos__partido--logos">
                <?php foreach ($modalidades as $m) { ?>
                    <p>
                        <?php echo $m->id == $d->modalidad ? $m->nombre . "<strong style='color: #DDDDDD;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|| &nbsp;&nbsp;</strong> " : ''; ?>
                    </p>
                <?php } ?>
                <p>
                    <?php echo $d->id_partido . " - " . $d->categoria; ?>
                </p>
                <?php foreach ($arbitros as $p) { ?>
                    <?php if ($p->id == $d->id_arbitro) { ?>
                        <p>
                            <?php
                            echo "<strong style='color: #DDDDDD;'>&nbsp;&nbsp;&nbsp;|| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong> " . $p->apellido1 . " " . $p->apellido2 . ", " . $p->nombre;
                            ?>
                        </p>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

        <div class="partidos__partido--texto">
            <div class="partidos__partido--fecha">
                <p><?php echo $d->fecha; ?></p>
                <p><?php echo $d->hora; ?></p>
            </div>
            <p><?php echo $d->terreno; ?></p>
            <p><?php echo $d->local . " - " . $d->visitante; ?></p>
        </div>

        <div class="partidos__partido--facturacion">
            <div class="partidos__partido__unidad">
                <p class="partidos__partido__unidad--label">Unidad</p>
                <button type="button" class="unidad-boton" id="unidad-boton" data-id_designacion="<?php echo $d->id; ?>">
                    <p class="unidad-texto" id="unidad-texto"><?php echo $d->unidad; ?></p>
                </button>
            </div>


            <div class="partidos__partido__unidad">
                <p class="partidos__partido__unidad--label">Tarifa</p>
                <p><?php echo $d->tarifa . " €"; ?></p>
            </div>

            <div class="partidos__partido__unidad">
                <p class="partidos__partido__unidad--label">Facturar</p>
                <p><?php echo $d->facturar . " €"; ?></p>
            </div>

            <div class="partidos__partido__unidad">
                <p class="partidos__partido__unidad--label">Pago árbitr@</p>
                <p><?php echo $d->pago_arbitro . " €"; ?></p>
            </div>

            <div class="partidos__partido__unidad">
                <p class="partidos__partido__unidad--label">OA</p>
                <p><?php echo $d->oa . " €"; ?></p>
            </div>
        </div>

        <div class="partidos__acciones">
            <a title="Editar" class="arbitros__card-acciones--editar" href="/admin/facturas/editar?id=<?php echo $d->id; ?>">
                <picture>
                    <source srcset="/build/img/editar_factura.avif" type="image/avif">
                    <source srcset="/build/img/editar_factura.webp" type="image/webp">
                    <img loading="lazy" width="20" height="20" src="/build/img/editar_factura.png" alt="botón para resetear">
                </picture>
            </a>
            <input type="hidden" name="id_partido_factura" value="<?php echo $d->id_partido ?>">
            <button class="arbitros__card-acciones--eliminar save1" type="button" data-partido-id="<?php echo $d->id_partido; ?>">
                <picture>
                    <source srcset="/build/img/save.avif" type="image/avif">
                    <source srcset="/build/img/save.webp" type="image/webp">
                    <img id="save1" class="save1" loading="lazy" width="20" height="20" src="/build/img/save.png" alt="botón para resetear">
                </picture>
            </button>
        </div>
    </form>
<?php } ?>

<div class="botones__scroll">
    <div class="botones__scroll--boton">
        <picture>
            <source srcset="/build/img/scroll.avif" type="image/avif">
            <source srcset="/build/img/scroll.webp" type="image/webp">
            <img id="arriba" loading="lazy" width="20" height="20" src="/build/img/scroll.png" alt="botón para aceptar">
        </picture>
    </div>
    <div class="botones__scroll--boton">
        <picture>
            <source srcset="/build/img/scroll.avif" type="image/avif">
            <source srcset="/build/img/scroll.webp" type="image/webp">
            <img id="abajo" loading="lazy" width="20" height="20" src="/build/img/scroll.png" alt="botón para aceptar">
        </picture>
    </div>
</div>

<script>
    // Esperar a que el DOM esté completamente cargado
    document.addEventListener("DOMContentLoaded", function() {
        // Valores para cambiar el texto
        var valoresGlobales = ["1.00", "0.00", "0.50", "0.25", "2.00"]; // Todas las opciones
        var valoresLimitados = ["1.00", "0.50"]; // Opciones limitadas para otras categorías
        var categoriasCompleta = ["SX", "JX"]; // Categorías que permiten todas las opciones
        var coloresTexto = {
            "0.00": "#7008A8", // Morado
            "1.00": "#30475E", // Azul oscuro
            "0.50": "#E08709", // Naranja
            "0.25": "#F05454", // Rojo claro
            "2.00": "#1E90FF", // Celeste
        };

        // Delegación de eventos en el contenedor padre
        document.addEventListener("click", function(event) {
            // Verificar si el clic ocurrió en un botón con la clase 'unidad-boton'
            if (event.target.closest(".unidad-boton")) {
                var boton = event.target.closest(".unidad-boton"); // El botón específico clicado
                var texto = boton.querySelector("#unidad-texto"); // Párrafo interno del botón
                var partido = boton.closest(".partidos__partido"); // Obtener el formulario padre
                var categoria = partido.getAttribute("data-categoria"); // Leer el atributo data-categoria

                // Determinar los valores a usar según la categoría
                var valores = categoriasCompleta.includes(categoria) ? valoresGlobales : valoresLimitados;

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
        document.querySelectorAll(".unidad-boton").forEach(function(boton) {
            var texto = boton.querySelector("#unidad-texto"); // Párrafo interno
            var partido = boton.closest(".partidos__partido"); // Obtener el formulario padre
            var categoria = partido.getAttribute("data-categoria"); // Leer el atributo data-categoria

            // Determinar los valores a usar según la categoría
            var valores = categoriasCompleta.includes(categoria) ? valoresGlobales : valoresLimitados;

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
</script>

<!-- <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Delegación de eventos para manejar clics en todos los botones .save1
        document.addEventListener("click", event => {
            // Verifica si el elemento clicado o un padre cercano tiene la clase .save1
            const button = event.target.closest(".save1");
            if (!button) return; // Si no es un botón .save1, salir

            handleSave(event, button);
        });

        function handleSave(event, button) {
            event.stopPropagation(); // Evitar propagación de eventos
            event.preventDefault(); // Prevenir comportamientos por defecto si aplica

            // Evitar múltiples clics mientras se procesa
            if (button.dataset.processing === "true") return;
            button.dataset.processing = "true"; // Marcar el botón como en proceso

            const form = button.closest("form"); // Formulario asociado al botón clicado
            const partidoId = form.querySelector("input[name='id_partido_factura']")?.value; // ID del partido
            const unidadTexto = form.querySelector(".unidad-texto")?.textContent?.trim(); // Texto de unidad

            // Log para depuración
            console.log("Datos antes de enviar:", {
                partidoId,
                unidadTexto
            });

            // Validar datos
            if (!partidoId || !unidadTexto) {
                console.error("Datos faltantes:", {
                    partidoId,
                    unidadTexto
                });
                alert("Faltan datos para enviar la solicitud");
                button.dataset.processing = "false"; // Liberar botón
                return;
            }

            const uniqueRequestId = Date.now(); // Crear un ID único para esta solicitud
            console.log(`Iniciando solicitud con ID: ${uniqueRequestId}`);

            // Realizar la solicitud
            fetch("/admin/facturas/cambiar_unidad", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id_partido: partidoId,
                        unidad: unidadTexto
                    }),
                })
                .then(async response => {
                    const status = response.status;
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(`Error en la solicitud: ${status}. ${data.message || ""}`);
                    }

                    console.log(`Respuesta exitosa para ID: ${uniqueRequestId}`, data);
                    alert(`Datos enviados correctamente: ${data.message}`);

                    // Recarga la página para reflejar los cambios
                    location.reload();
                })
                .catch(error => {
                    console.error(`Error en la solicitud con ID: ${uniqueRequestId}`, error);
                    alert(`Hubo un error: ${error.message}`);
                })
                .finally(() => {
                    button.dataset.processing = "false"; // Liberar el botón al finalizar
                });
        }
    });
</script> -->

<script>
    document.addEventListener("DOMContentLoaded", () => {
    // Delegación de eventos para manejar clics en todos los botones .save1
    document.addEventListener("click", event => {
        // Verifica si el elemento clicado o un padre cercano tiene la clase .save1
        const button = event.target.closest(".save1");
        if (!button) return; // Si no es un botón .save1, salir

        handleSave(event, button);
    });

    function handleSave(event, button) {
        event.stopPropagation(); // Evitar propagación de eventos
        event.preventDefault(); // Prevenir comportamientos por defecto si aplica

        // Evitar múltiples clics mientras se procesa
        if (button.dataset.processing === "true") return;
        button.dataset.processing = "true"; // Marcar el botón como en proceso

        const form = button.closest("form"); // Formulario asociado al botón clicado
        const partidoId = form.querySelector("input[name='id_partido_factura']")?.value; // ID del partido
        const unidadTexto = form.querySelector(".unidad-texto")?.textContent?.trim(); // Texto de unidad
        const idDesignacion = form.querySelector(".unidad-boton")?.getAttribute("data-id_designacion"); // Capturar ID de la designación

        // Log para depuración
        console.log("Datos antes de enviar:", {
            partidoId,
            unidadTexto,
            idDesignacion
        });

        // Validar datos
        if (!partidoId || !unidadTexto || !idDesignacion) {
            console.error("Datos faltantes:", {
                partidoId,
                unidadTexto,
                idDesignacion
            });
            alert("Faltan datos para enviar la solicitud");
            button.dataset.processing = "false"; // Liberar botón
            return;
        }

        const uniqueRequestId = Date.now(); // Crear un ID único para esta solicitud
        console.log(`Iniciando solicitud con ID: ${uniqueRequestId}`);

        // Realizar la solicitud
        fetch("/admin/facturas/cambiar_unidad", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id_partido: partidoId,
                    unidad: unidadTexto,
                    id_designacion: idDesignacion // Ahora enviamos el ID de la designación
                }),
            })
            .then(async response => {
                const status = response.status;
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(`Error en la solicitud: ${status}. ${data.message || ""}`);
                }

                console.log(`Respuesta exitosa para ID: ${uniqueRequestId}`, data);
                alert(`Datos enviados correctamente: ${data.message}`);

                // Recarga la página para reflejar los cambios
                location.reload();
            })
            .catch(error => {
                console.error(`Error en la solicitud con ID: ${uniqueRequestId}`, error);
                alert(`Hubo un error: ${error.message}`);
            })
            .finally(() => {
                button.dataset.processing = "false"; // Liberar el botón al finalizar
            });
    }
});

</script>