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
            <a href="/admin/partidos/agregar" title="Agregar partido">
                <pictures class="dashboard__caja--icono tj">
                    <source srcset="/build/img/tj.avif" type="image/avif">
                    <source srcset="/builpartido.webp" type="image/webp">
                    <img style="width: 45px;" loading="lazy" width="20" height="20" src="/build/img/tj.png" alt="botón para añadir árbitro">
                </pictures>
            </a>
        </div>
    </div>
    <h2 class="dashboard__heading"><?php echo $titulo ?></h2>
</div>

<?php if (empty($partidos)) { ?>
    <div class="partidos__partido-1">
        <p>Esperando carga de partidos</p>
        <div class="partidos__loader"></div>
    </div>

<?php } else { ?>
    <h3 class="partidos__titulo-filtro">Realice su búsqueda:</h3>
    <div class="partidos__partido partidos__filtro partidos__filtro-especifico">
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por árbitro/a:</h4>
            <input
                type="text"
                id="arbitro"
                name="arbitro"
                placeholder="Ej: Santizo Álvarez"
                value="" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por ID</h4>
            <input
                type="text"
                id="id_partido"
                name="id_partido"
                placeholder="Ej: 405688"
                value="" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por campo</h4>
            <input
                type="text"
                id="campo"
                name="campo"
                placeholder="Ej: CD Ifni"
                value="" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por equipo</h4>
            <input
                type="text"
                id="equipo"
                name="equipo"
                placeholder="Ej: UD Soleá"
                value="" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por categoría</h4>
            <select>
                <option selected value="">Ver todos</option>
                <?php foreach ($categorias as $cat) { ?>
                    <option value="<?php echo $cat->id; ?>"><?php echo $cat->nombre; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por fecha</h4>
            <select>
                <option selected value="">Ver todos</option>
                <?php foreach ($fechasAsociativo as $cat) { ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['fecha']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por distrito</h4>
            <select>
                <option selected value="">Ver todos</option>
                <?php foreach ($distritosAsociativo as $cat) { ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['distrito']; ?></option>
                <?php } ?>
            </select>
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
            <h4 class="partidos__titulo-filtros">Estado</h4>
            <div class="estado-opciones">
                <label class="estado-label">
                    <input type="radio" name="estado" value="1">
                    <span class="radio-custom"></span>
                    Designado
                </label>
                <label class="estado-label">
                    <input type="radio" name="estado" value="2">
                    <span class="radio-custom"></span>
                    No designado
                </label>
            </div>
        </div>
        <div class="partidos__campo">
            <button class="partidos__boton-filtro" type="button">Borrar filtros</button>
        </div>
    </div>

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
        <div class="partidos">
            <?php foreach ($partidos as $p) { ?>
                <?php if ($p->designado == 0) { ?>
                    <div class="partidos__partido">
                        <div class="partidos__partido--id">
                            <input type="checkbox" name="seleccionar[]" value=<?php $p->id_partido; ?>>
                            <p><?php echo $p->id_partido . " - " . $p->categoria; ?></p>
                            <picture>
                                <source srcset="/build/img/excla.avif" type="image/avif">
                                <source srcset="/build/img/excla.webp" type="image/webp">
                                <img id="excla" loading="lazy" width="20" height="20" src="/build/img/excla.png" alt="botón para resetear">
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
                                        name="arbitro"
                                        placeholder="Escribe nombre o apellido"
                                        value=""
                                        autocomplete="off" />
                                    <ul id="arbitro_suggestions" class="autocomplete-suggestions"></ul> <!-- Aquí aparecerán las sugerencias -->
                                </div>
                            </div> <!--cierre partido__arbitro-->
                        </div> <!--cierre partido__texto-->

                        <div class="partidos__acciones">
                            <!-- Acción de editar -->
                            <a title="Editar" class="arbitros__card-acciones--editar" href="/admin/partidos/editar?id=<?php echo $p->id; ?>">
                                <picture>
                                    <source srcset="/build/img/editar-partido.avif" type="image/avif">
                                    <source srcset="/build/img/editar-partido.webp" type="image/webp">
                                    <img loading="lazy" width="20" height="20" src="/build/img/editar-partido.png" alt="botón para resetear">
                                </picture>
                            </a>
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
                                <button class="arbitros__card-acciones--eliminar" type="button">
                                    <picture>
                                        <source srcset="/build/img/enviar.avif" type="image/avif">
                                        <source srcset="/build/img/enviar.webp" type="image/webp">
                                        <img id="send" loading="lazy" width="20" height="20" src="/build/img/enviar.png" alt="botón para resetear">
                                    </picture>
                                </button>
                            </form>
                            <form class="partidos__acciones--eliminar" method="post" action="/admin/partidos/eliminar?id=<?php echo $p->id; ?>">
                                <button class="alert4 arbitros__card-acciones--eliminar" type="button">
                                    <picture>
                                        <source srcset="/build/img/envase.avif" type="image/avif">
                                        <source srcset="/build/img/envase.webp" type="image/webp">
                                        <img loading="lazy" width="20" height="20" src="/build/img/envase.png" alt="botón para resetear">
                                    </picture>
                                </button>
                            </form>
                        </div> <!--cierre acciones-->
                    </div> <!--partidos__partido-->

                <?php } else { ?> <!--cierre if designado == 0-->
                    <?php foreach($designaciones as $d) { ?>
                            <div class="partidos__partido">
                            <div class="partidos__partido--id">
                                <input type="checkbox" name="seleccionar[]" value=<?php $d->id_partido; ?>>
                                <p><?php echo $d->id_partido . " - " . $p->categoria; ?></p> <!--***-->
                                <picture>
                                    <source srcset="/build/img/excla.avif" type="image/avif">
                                    <source srcset="/build/img/excla.webp" type="image/webp">
                                    <img id="excla" loading="lazy" width="20" height="20" src="/build/img/excla.png" alt="botón para resetear">
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
                                            name="arbitro"
                                            placeholder="Escribe nombre o apellido"
                                            value=""
                                            autocomplete="off" />
                                        <ul id="arbitro_suggestions" class="autocomplete-suggestions"></ul> <!-- Aquí aparecerán las sugerencias -->
                                    </div>
                                </div> <!--cierre partido__arbitro-->
                            </div> <!--cierre partido__texto-->

                            <div class="partidos__acciones">
                                <!-- Acción de editar -->
                                <a title="Editar" class="arbitros__card-acciones--editar" href="/admin/partidos/editar?id=<?php echo $p->id; ?>">
                                    <picture>
                                        <source srcset="/build/img/editar-partido.avif" type="image/avif">
                                        <source srcset="/build/img/editar-partido.webp" type="image/webp">
                                        <img loading="lazy" width="20" height="20" src="/build/img/editar-partido.png" alt="botón para resetear">
                                    </picture>
                                </a>
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
                                    <button class="arbitros__card-acciones--eliminar" type="button">
                                        <picture>
                                            <source srcset="/build/img/enviar.avif" type="image/avif">
                                            <source srcset="/build/img/enviar.webp" type="image/webp">
                                            <img id="send" loading="lazy" width="20" height="20" src="/build/img/enviar.png" alt="botón para resetear">
                                        </picture>
                                    </button>
                                </form>
                                <form class="partidos__acciones--eliminar" method="post" action="/admin/partidos/eliminar?id=<?php echo $p->id; ?>">
                                    <button class="alert4 arbitros__card-acciones--eliminar" type="button">
                                        <picture>
                                            <source srcset="/build/img/envase.avif" type="image/avif">
                                            <source srcset="/build/img/envase.webp" type="image/webp">
                                            <img loading="lazy" width="20" height="20" src="/build/img/envase.png" alt="botón para resetear">
                                        </picture>
                                    </button>
                                </form>
                            </div> <!--cierre acciones-->
                        </div> <!--partidos__partido-->
                    <?php } ?> <!--cierre foreach de designaciones-->

                <?php } ?><!--cierre if designado != 0-->
            <?php } ?> <!--cierre foreach de partidos-->
        </div>
    </form>

<?php } ?> <!--CIERRE FINAL FINAL-->