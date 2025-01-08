<div class="dashboard__box-titulos">
    <div class="dashboard__caja">
        <a href="/admin/dashboard" title="Volver a inicio">
            <pictures class="dashboard__caja--icono">
                <source srcset="/build/img/arrow.avif" type="image/avif">
                <source srcset="/build/img/arrow.webp" type="image/webp">
                <img loading="lazy" width="20" height="20" src="/build/img/arrow.png" alt="botón para ir a carga masiva de árbitros">
            </pictures>
        </a>
        <div class="dashboard__caja-routing">
            <a href="/admin/arbitros/agregar" title="Agregar árbitr@">
                <pictures class="dashboard__caja--icono">
                    <source srcset="/build/img/arbitro.avif" type="image/avif">
                    <source srcset="/build/img/arbitro.webp" type="image/webp">
                    <img loading="lazy" width="20" height="20" src="/build/img/arbitro.png" alt="botón para ir a carga masiva de árbitros">
                </pictures>
            </a>
        </div>
    </div>
    <h2 class="dashboard__heading"><?php echo $titulo ?></h2>
</div>

<?php if (empty($arbitros)) { ?>
    <div class="partidos__partido-1">
        <p>Esperando carga de árbitros</p>
        <div class="partidos__loader"></div>
    </div>


<?php } else { ?>

    <h3 class="partidos__titulo-filtro">Realice su búsqueda:</h3>
    <div class="partidos__partido partidos__filtro partidos__filtro-especifico">
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por nombre y apellidos</h4>
            <input
                type="text"
                id="nombre"
                name="nombre"
                placeholder="Ej: Santizo Álvarez"
                value="" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por teléfono</h4>
            <input
                type="text"
                id="telefono"
                name="telefono"
                placeholder="Ej: 647654009"
                value="" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por código postal</h4>
            <input
                type="text"
                id="cp"
                name="co"
                placeholder="Ej: 41006"
                value="" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Coche</h4>
            <div class="estado-opciones">
                <label class="estado-label">
                    <input type="radio" name="coche" value="1">
                    <span class="radio-custom"></span>
                    Si
                </label>
                <label class="estado-label">
                    <input type="radio" name="coche" value="0">
                    <span class="radio-custom"></span>
                    No
                </label>
            </div>
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Elije modalidad</h4>
            <div class="estado-opciones">
                <label class="estado-label">
                    <input type="radio" name="deporte" value="futbol">
                    <span class="radio-custom"></span>
                    Fútbol
                </label>
                <label class="estado-label">
                    <input type="radio" name="deporte" value="futbol_sala">
                    <span class="radio-custom"></span>
                    Fútbol Sala
                </label>
            </div>
        </div>

        <div class="partidos__campo">
            <button class="partidos__boton-filtro" type="button">Borrar filtros</button>
        </div>
    </div>

    <div class="contar_arbitros">
        <h3></h3>
    </div>

    <div class="arbitros">
        <?php foreach ($arbitros as $a) { ?>
            <div class="arbitros__card">
                <?php if ($a->modalidad === "1") { ?>
                    <div class="arbitros__card-titulo">
                        <p class="arbitros__card--titulo1"><?php echo $a->apellido1 . " " . $a->apellido2 . ", " . $a->nombre; ?></p>
                        <p class="arbitros__card--titulo"><?php echo $a->modalidad_nombre; ?></p>
                    </div>

                    <div class="toggles">
                        <div class="toggle-border">
                            <input class="check_arbis"
                                type="checkbox"
                                id="toggle_arbitros-<?php echo $a->id; ?>"
                                <?php echo $a->activo == "1" ? 'checked' : ''; ?>
                                data-id="<?php echo $a->id; ?>" />
                            <label class="toogle_label" for="toggle_arbitros-<?php echo $a->id; ?>">
                                <div class="handle"></div>
                            </label>
                        </div>
                    </div>

                <?php } else { ?>
                    <div class="arbitros__card-titulo">
                        <p class="arbitros__card--titulo1-rojo"><?php echo $a->apellido1 . " " . $a->apellido2 . ", " . $a->nombre; ?></p>
                        <p class="arbitros__card--titulo-rojo"><?php echo $a->modalidad_nombre; ?></p>
                    </div>

                    <div class="toggles">
                        <div class="toggle-border">
                            <input class="check_arbis"
                                type="checkbox"
                                id="toggle_arbitros-<?php echo $a->id; ?>"
                                <?php echo $a->activo == "1" ? 'checked' : ''; ?>
                                data-id="<?php echo $a->id; ?>" />
                            <label class="toogle_label" for="toggle_arbitros-<?php echo $a->id; ?>">
                                <div class="handle"></div>
                            </label>
                        </div>
                    </div>

                <?php } ?>
                <div class="arbitros__card-texto">
                    <p class="arbitros__card--telefono">Teléfono: <?php echo $a->telefono; ?></p>
                    <p class="arbitros__card--email"><?php echo $a->email; ?></p>
                    <p class="arbitros__card--codigo_postal">CP: <?php echo $a->codigo_postal; ?> | Coche: <?php echo $a->coche_nombre; ?></p>
                </div>
                <div class="arbitros__card-acciones">
                    <a title="Editar" href="/admin/arbitros/editar?id=<?php echo $a->id; ?>" class="arbitros__card-acciones--editar">
                        <pictures>
                            <source srcset="/build/img/edit.avif" type="image/avif">
                            <source srcset="/build/img/edit.webp" type="image/webp">
                            <img loading="lazy" width="20" height="20" src="/build/img/edit.png" alt="botón para resetear">
                        </pictures>
                    </a>
                    <form method="POST" action="/admin/arbitros/eliminar?id=<?php echo $a->id; ?>" class="arbitros__card-acciones--eliminar">
                        <input  type="hidden" name="id" value="<?php echo $a->id ?>">
                        <button class="alert2 arbitros__card-acciones--eliminar" type="button">
                            <pictures>
                                <source srcset="/build/img/envase.avif" type="image/avif">
                                <source srcset="/build/img/envase.webp" type="image/webp">
                                <img loading="lazy" width="20" height="20" src="/build/img/envase.png" alt="botón para resetear">
                            </pictures>
                        </button>
                    </form>
                </div>
            </div>
        <?php } ?>

    </div>

<?php } ?>