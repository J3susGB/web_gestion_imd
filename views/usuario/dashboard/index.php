<div class="dashboard__box-titulos">
    <h2 class="dashboard__heading"><?php echo $titulo ?></h2>
</div>

<form class="fom" method="GET" action="/admin/partidos">
    <h3 class="partidos__titulo-filtro">Realice su búsqueda:</h3>
    <div class="partidos__partido partidos__filtro partidos__filtro-especifico">

        <!-- Árbitro -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por árbitro/a:</h4>
            <input type="text" name="arbitro" placeholder="Ej: Santizo Álvarez" value="<?php echo htmlspecialchars($_GET['arbitro'] ?? '', ENT_QUOTES); ?>">
        </div>

        <!-- ID Partido -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por ID</h4>
            <input type="text" name="id_partido" placeholder="Ej: 405688" value="<?php echo htmlspecialchars($_GET['id_partido'] ?? '', ENT_QUOTES); ?>">
        </div>

        <!-- Campo -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por campo</h4>
            <input type="text" name="campo" placeholder="Ej: CD Ifni" value="<?php echo htmlspecialchars($_GET['campo'] ?? '', ENT_QUOTES); ?>">
        </div>

        <!-- Equipo -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por equipo</h4>
            <input type="text" name="equipo" placeholder="Ej: UD Soleá" value="<?php echo htmlspecialchars($_GET['equipo'] ?? '', ENT_QUOTES); ?>">
        </div>

        <!-- Categoría -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por categoría</h4>
            <select name="categoria">
                <option value="">Ver todos</option>
                <?php foreach ($categorias as $cat) { ?>
                    <option value="<?php echo $cat->nombre; ?>" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] == $cat->nombre) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat->nombre, ENT_QUOTES); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Fecha -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por fecha</h4>
            <select name="fecha">
                <option value="">Ver todos</option>
                <?php foreach ($fechasAsociativo as $cat) { ?>
                    <option value="<?php echo $cat['fecha']; ?>" <?php echo (isset($_GET['fecha']) && $_GET['fecha'] == $cat['fecha']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['fecha'], ENT_QUOTES); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Distrito -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por distrito</h4>
            <select name="distrito">
                <option value="">Ver todos</option>
                <?php foreach ($distritosAsociativo as $cat) { ?>
                    <option value="<?php echo $cat['distrito']; ?>" <?php echo (isset($_GET['distrito']) && $_GET['distrito'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['distrito'], ENT_QUOTES); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Estado -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por estado</h4>
            <select name="estado">
                <option value="">Ver todos</option>
                <option value="1" <?php echo (isset($_GET['estado']) && $_GET['estado'] === '1') ? 'selected' : ''; ?>>Nombrado</option>
                <option value="2" <?php echo (isset($_GET['estado']) && $_GET['estado'] === '2') ? 'selected' : ''; ?>>Enviado</option>
                <option value="3" <?php echo (isset($_GET['estado']) && $_GET['estado'] === '3') ? 'selected' : ''; ?>>Aceptado</option>
                <option value="4" <?php echo (isset($_GET['estado']) && $_GET['estado'] === '4') ? 'selected' : ''; ?>>Rechazado</option>
            </select>
        </div>

        <!-- Modalidad -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Elije modalidad</h4>
            <div class="estado-opciones">
                <label class="estado-label">
                    <input type="radio" name="deporte" value="futbol"
                        <?php echo (isset($_GET['deporte']) && $_GET['deporte'] === 'futbol') ? 'checked' : ''; ?>>
                    <span class="radio-custom"></span>
                    Fútbol
                </label>
                <label class="estado-label">
                    <input type="radio" name="deporte" value="sala"
                        <?php echo (isset($_GET['deporte']) && $_GET['deporte'] === 'sala') ? 'checked' : ''; ?>>
                    <span class="radio-custom"></span>
                    Fútbol Sala
                </label>
            </div>
        </div>

        <!-- Botones -->
        <div class="partidos__campo">
            <button type="button" class="partidos__boton-filtro" onclick="enviarFormularioManualmente()">Aplicar Filtros</button>
            <!-- <button type="submit" class="partidos__boton-filtro">Aplicar Filtros</button> -->
            <a href="/admin/partidos" class="partidos__boton-filtro" onclick="restablecerFiltros(event)">Restablecer</a>
        </div>
    </div>
</form>

<!-- Enviar formulario de forma manual -->
<script>
    function enviarFormularioManualmente() {
        let params = new URLSearchParams();

        // Capturar manualmente el campo "arbitro"
        let arbitro = document.querySelector('input[name="arbitro"]');
        if (arbitro) {
            params.append('arbitro', arbitro.value.trim());
        }

        // Capturar manualmente el campo "deporte"
        let deporte = document.querySelector('input[name="deporte"]:checked');
        if (deporte) {
            console.log('Valor capturado de deporte:', deporte.value);
            params.append('deporte', deporte.value);
        } else {
            console.log('Ninguna modalidad seleccionada');
        }

        // Capturar el resto de los campos
        document.querySelectorAll('form input, form select').forEach(input => {
            if (input.name === 'arbitro' || input.name === 'deporte') return; // Evitar duplicados
            if (input.type === 'radio' && input.checked) {
                params.append(input.name, input.value);
            } else if (input.type === 'text' || input.tagName === 'SELECT') {
                params.append(input.name, input.value.trim());
            }
        });

        console.log('Datos enviados manualmente:', params.toString());

        // Enviar con retraso para garantizar captura correcta
        setTimeout(() => {
            window.location.href = '/usuario/dashboard?' + params.toString();
        }, 100); // 100ms de retraso
    }
</script>
<!-- Restablecer filtros -->
<script>
    function restablecerFiltros(event) {
        event.preventDefault(); // Evita el comportamiento por defecto del enlace
        window.location.href = '/usuario/dashboard'; // Recarga la página limpia
    }
</script>

<?php if (empty($partidos)) { ?>
    <div class="partidos__partido-1">
        <p>Esperando carga de partidos</p>
        <div class="partidos__loader"></div>
    </div>

<?php } else { ?>

    <div class="contar_partidos">
        <h3></h3>
    </div>

    <!-- Checkbox de "Seleccionar todo" -->
    <div class="partidos__seleccionar-todo">
        <label>
            <input type="checkbox" id="seleccionar-todo"> Seleccionar todo
        </label>
    </div>

    <!-- Formulario para enviar los partidos seleccionados -->
    <form method="POST" action='/admin/partidos' id="formPartidos">
        <div id="contenedor_partidos" class="partidos">
            <?php foreach ($partidos as $p) { ?>
                <?php if ($p->estado == 0) { ?>
                    <div id="<?php echo $p->id_partido; ?>" class="partidos__partido">
                        <div class="partidos__partido--id">
                            <?php if ($p->estado == 1) { ?>
                                <input type="checkbox" name="seleccionar[]" value=<?php echo $p->id_partido; ?>>
                            <?php } ?>
                            <p><?php echo $p->id_partido . " - " . $p->categoria; ?></p>
                            <picture>
                                <source srcset="/build/img/excla2.avif" type="image/avif">
                                <source srcset="/build/img/excla2.webp" type="image/webp">
                                <img id="excla" loading="lazy" width="20" height="20" src="/build/img/excla2.png" alt="botón para resetear">
                            </picture>
                        </div> <!--partidos__id-->

                        <div class="partidos__partido--texto">
                            <div class="partidos__partido--fecha">
                                <p><?php echo $p->fecha; ?></p>
                                <p><?php echo $p->hora; ?></p>
                            </div>
                            <p><?php echo $p->terreno; ?></p>
                            <p><?php echo $p->local . " - " . $p->visitante; ?></p>

                            <div class="partidos__partido--arbitro">
                                <div class="partidos__campo">
                                    <input
                                        type="text"
                                        id="arbitro_autocomplete"
                                        class="arbitro_autocomplete"
                                        name="arbitro"
                                        placeholder="Pendiente designar"
                                        value=""
                                        autocomplete="off" />
                                    <ul id="arbitro_suggestions" class="autocomplete-suggestions"></ul> <!-- Aquí aparecerán las sugerencias -->
                                </div>
                            </div> <!--cierre partido__arbitro-->
                        </div> <!--cierre partido__texto-->

                        <div class="partidos__acciones">
                            <form class="partidos__acciones--guardar" method="post" action="/admin/partidos/guardar?id=<?php echo $p->id; ?>">
                                <input type="hidden" name="id" value="<?php echo $p->id ?>">
                                <button class="arbitros__card-acciones--eliminar" type="button">
                                    <picture>
                                        <source srcset="/build/img/save.avif" type="image/avif">
                                        <source srcset="/build/img/save.webp" type="image/webp">
                                        <img id="save" class="save" loading="lazy" width="20" height="20" src="/build/img/save.png" alt="botón para resetear">
                                    </picture>
                                </button>
                            </form>
                            <form class="partidos__acciones--enviar" method="post" action="/admin/partidos/enviar?id=<?php echo $p->id; ?>">
                                <button class="arbitros__card-acciones--eliminar" type="button"
                                    <?php echo ($p->estado == 0 || $p->estado == 2 || $p->partido == 3) ? 'disabled' : ''; ?>>
                                    <picture>
                                        <source srcset="/build/img/enviar.avif" type="image/avif">
                                        <source srcset="/build/img/enviar.webp" type="image/webp">
                                        <img id="send" loading="lazy" width="20" height="20" src="/build/img/enviar.png" alt="botón para resetear">
                                    </picture>
                                </button>
                            </form>
                            <?php if ($p->estado == 2) { ?>
                                <form class="partidos__acciones--enviar" method="post" action="/admin/partidos/autoaceptar?id=<?php echo $p->id; ?>">
                                    <button class="arbitros__card-acciones--eliminar" type="button">
                                        <picture>
                                            <source srcset="/build/img/auto.avif" type="image/avif">
                                            <source srcset="/build/img/auto.webp" type="image/webp">
                                            <img class="auto_aceptar" id="auto_aceptar" loading="lazy" width="20" height="20" src="/build/img/auto.png" alt="botón para aceptar">
                                        </picture>
                                    </button>
                                </form>
                            <?php } ?>
                        </div> <!--cierre acciones-->
                    </div> <!--partidos__partido-->

                <?php } else { ?> <!--cierre if estado == 0-->
                    <div id="<?php echo $p->id_partido; ?>" class="partidos__partido" data-partido-id="<?php echo $p->id_partido; ?>">
                        <div class="partidos__partido--id">
                            <?php if ($p->estado == 1) { ?>
                                <input type="checkbox" name="seleccionar[]" value=<?php echo $p->id_partido; ?>>
                            <?php } ?> <p><?php echo $p->id_partido . " - " . $p->categoria; ?></p> <!--***-->

                            <?php if ($p->estado == 1) { ?>
                                <picture>
                                    <source srcset="/build/img/guardado.avif" type="image/avif">
                                    <source srcset="/build/img/guardado.webp" type="image/webp">
                                    <img id="nombrado" loading="lazy" width="20" height="20" src="/build/img/guardado.png" alt="botón para resetear">
                                </picture>
                            <?php } ?>
                            <?php if ($p->estado == 2) { ?>
                                <picture>
                                    <source srcset="/build/img/nombrado.avif" type="image/avif">
                                    <source srcset="/build/img/nombrado.webp" type="image/webp">
                                    <img id="enviado" loading="lazy" width="20" height="20" src="/build/img/nombrado.png" alt="botón para resetear">
                                </picture>
                            <?php } ?>
                            <?php if ($p->estado == 3) { ?>
                                <picture>
                                    <source srcset="/build/img/enviado.avif" type="image/avif">
                                    <source srcset="/build/img/enviado.webp" type="image/webp">
                                    <img id="aceptado" loading="lazy" width="20" height="20" src="/build/img/enviado.png" alt="botón para resetear">
                                </picture>
                            <?php } ?>
                            <?php if ($p->estado == 4) { ?>
                                <picture>
                                    <source srcset="/build/img/rechazado.avif" type="image/avif">
                                    <source srcset="/build/img/rechazado.webp" type="image/webp">
                                    <img id="rechazado" class="negacion" loading="lazy" width="20" height="20" src="/build/img/rechazado.png" alt="botón para resetear" data-observaciones="<?php echo htmlspecialchars($p->observaciones, ENT_QUOTES, 'UTF-8'); ?>">
                                </picture>
                            <?php } ?>
                        </div> <!--partidos__id-->

                        <div class="partidos__partido--texto">
                            <div class="partidos__partido--fecha">
                                <p><?php echo $p->fecha; ?></p>
                                <p><?php echo $p->hora; ?></p>
                            </div>
                            <p><?php echo $p->terreno; ?></p>
                            <p><?php echo $p->local . " - " . $p->visitante; ?></p>

                            <div class="partidos__partido--arbitro">
                                <?php foreach ($arbitros as $a) { ?>
                                    <?php if ($p->id_arbitro == $a->id) { ?>
                                        <a href="#">
                                            <pictures>
                                                <source srcset="/build/img/lupita.avif" type="image/avif">
                                                <source srcset="/build/img/lupita.webp" type="image/webp">
                                                <img id="lupita" loading="lazy" width="20" height="20" src="/build/img/lupita.png" alt="botón para resetear">
                                            </pictures>
                                        </a>
                                        <a href="#"><?php echo $a->apellido1 . " " . $a->apellido2 . ", " . $a->nombre; ?></a>
                                        <input type="hidden" name="id_arbi" id="id_arbi" value="<?php echo $a->id ?>">
                                    <?php } ?>
                                <?php } ?> <!--cierre foreach partido__arbitro-->
                            </div> <!--cierre partido__arbitro-->
                        </div> <!--cierre partido__texto-->

                        <div class="partidos__acciones">
                            <!-- Acción de editar -->
                            <form class="partidos__acciones--guardar" method="post" action="/admin/partidos/borrar_nombramiento?id=<?php echo $p->id; ?>">
                                <input type="hidden" name="id" value="<?php echo $p->id ?>">
                                <button class="arbitros__card-acciones--eliminar" type="button">
                                    <picture>
                                        <source srcset="/build/img/borrar.avif" type="image/avif">
                                        <source srcset="/build/img/borrar.webp" type="image/webp">
                                        <img id="borrar" class="borrar" loading="lazy" width="20" height="20" src="/build/img/borrar.png" alt="botón para resetear">
                                    </picture>
                                </button>
                            </form>
                            <form class="partidos__acciones--enviar" method="post" action="/admin/partidos/enviar?id=<?php echo $p->id; ?>">
                                <button class="arbitros__card-acciones--eliminar <?php echo ($p->estado == 0 || $p->estado == 2) ? 'disabled' : ''; ?>"
                                    type="submit"
                                    <?php echo ($p->estado == 0 || $p->estado == 2 || $p->partido == 3) ? 'disabled' : ''; ?>>
                                    <picture>
                                        <source srcset="/build/img/enviar.avif" type="image/avif">
                                        <source srcset="/build/img/enviar.webp" type="image/webp">
                                        <img id="send" class="send" loading="lazy" width="20" height="20" src="/build/img/enviar.png" alt="botón para resetear">
                                    </picture>
                                </button>
                            </form>

                            <?php if ($p->estado == 2) { ?>
                                <form class="partidos__acciones--enviar" method="post" action="/admin/partidos/autoaceptar?id=<?php echo $p->id; ?>">
                                    <button class="arbitros__card-acciones--eliminar" type="button">
                                        <picture>
                                            <source srcset="/build/img/auto.avif" type="image/avif">
                                            <source srcset="/build/img/auto.webp" type="image/webp">
                                            <img class="auto_aceptar" id="auto_aceptar" loading="lazy" width="20" height="20" src="/build/img/auto.png" alt="botón para aceptar">
                                        </picture>
                                    </button>
                                </form>
                            <?php } ?>

                        </div> <!--cierre acciones-->

                    </div> <!--partidos__partido-->

                <?php } ?><!--cierre if estado != 0-->
            <?php } ?> <!--cierre foreach de partidos-->
        </div>
        <!-- Botón para procesar los partidos seleccionados -->
        <div class="partidos__container">
            <button class="partidos__enviar" type="submit" id="submitButton">Enviar</button>
        </div>
    </form>

<?php } ?> <!--CIERRE FINAL FINAL-->


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ✅ Seleccionar/Deseleccionar todos los checkboxes
        const seleccionarTodoCheckbox = document.getElementById('seleccionar-todo');
        seleccionarTodoCheckbox.addEventListener('change', function(e) {
            const isChecked = e.target.checked;
            const checkboxes = document.querySelectorAll('input[name="seleccionar[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        });

        // ✅ Validación del formulario de envío
        const submitButton = document.getElementById('submitButton');
        const formPartidos = document.getElementById('formPartidos');

        submitButton.addEventListener('click', function(event) {
            const checkboxes = document.querySelectorAll('input[name="seleccionar[]"]:checked');

            if (checkboxes.length === 0) {
                event.preventDefault(); // Evita el envío si no hay partidos seleccionados
                alert('Por favor, seleccione al menos un partido antes de enviar.');
            } else {
                console.log('Enviando formulario...');
                formPartidos.submit(); // Envía el formulario manualmente
            }
        });

        // ✅ Capturar el evento submit del formulario para depuración
        formPartidos.addEventListener('submit', function(event) {
            console.log('Formulario enviado correctamente.');
        });
    });
</script>