<div class="dashboard__caja">
    <a href="/admin/facturas/ver?jornada_editada=<?php echo $jornada_editada; ?>" title="Volver a atrás">
        <picture class="dashboard__caja--icono">
            <source srcset="/build/img/arrow.avif" type="image/avif">
            <source srcset="/build/img/arrow.webp" type="image/webp">
            <img loading="lazy" width="20" height="20" src="/build/img/arrow.png" alt="botón para ir a carga masiva de árbitros">
        </picture>
    </a>
</div>

<h2 class="dashboard__heading"><?php echo $titulo ?></h2>

<?php
require_once __DIR__ . '/../../templates/alertas.php';
?>

<form class="fom" method="POST">
    <div class="partidos__partido partidos__filtro partidos__filtro-especifico">

        <!-- Arbitro -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Árbitro</h4>
            <select name="arbitro">
                <option value="">Ver todos</option>
                <?php foreach ($arbitros as $cat) { ?>
                    <option
                        value="<?php echo $cat->id; ?>"
                        <?php echo ($designacion_editar->id_arbitro === $cat->id) ? 'selected' : ''; ?>><?php echo $cat->apellido1 . " " . $cat->apellido2 . ", " . $cat->nombre; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Id Partido -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">ID partido</h4>
            <input
                disabled
                type="text"
                name="id_partido"
                value="<?php echo $designacion_editar->id_partido; ?>">
        </div>

        <!-- Categoria -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Categoría</h4>
            <select name="categoria">
                <option value="">Ver todos</option>
                <?php foreach ($categorias as $cat) { ?>
                    <option
                        value="<?php echo $cat->nombre2; ?>"
                        <?php echo ($designacion_editar->categoria === $cat->nombre2) ? 'selected' : ''; ?>><?php echo $cat->nombre2; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Grupo -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Grupo</h4>
            <input
                type="text"
                name="grupo"
                value="<?php echo $designacion_editar->grupo; ?>">
        </div>

        <!-- Fecha -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Fecha</h4>
            <input
                type="text"
                name="fecha"
                value="<?php echo $designacion_editar->fecha; ?>">
        </div>

        <!-- Hora -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Hora</h4>
            <input
                type="text"
                name="hora"
                value="<?php echo $designacion_editar->hora; ?>">
        </div>

        <!-- Campo -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Campo</h4>
            <input
                type="text"
                name="terreno"
                value="<?php echo $designacion_editar->terreno; ?>">
        </div>

        <!-- Distrito -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Distrito</h4>
            <input
                type="text"
                name="distrito"
                value="<?php echo $designacion_editar->distrito; ?>">
        </div>

        <!-- Local -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Local</h4>
            <input
                type="text"
                name="local"
                value="<?php echo $designacion_editar->local; ?>">
        </div>

        <!-- Visitante -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Visitante</h4>
            <input
                type="text"
                name="visitante"
                value="<?php echo $designacion_editar->visitante; ?>">
        </div>

        <!-- Observaciones -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Observaciones</h4>
            <input
                type="text"
                name="observaciones"
                placeholder="Algo que reseñar del partido"
                value="<?php echo $designacion_editar->observaciones; ?>">
        </div>

        <!-- Unidad -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Unidad</h4>
            <select name="unidad">
                <option value="1.00" <?php echo ($designacion_editar == '1.00') ? 'selected' : ''; ?>>1.00</option>
                <option value="0.50" <?php echo ($designacion_editar == '0.50') ? 'selected' : ''; ?>>0.50</option>
                <option value="0.25" <?php echo ($designacion_editar == '0.25') ? 'selected' : ''; ?>>0.25</option>
            </select>
        </div>

        <!-- Jornada -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Jornada</h4>
            <input
                type="text"
                name="jornada_editada"
                value="<?php echo $designacion_editar->jornada_editada; ?>">
        </div>

        <!-- Modalidad -->
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Modalidad</h4>
            <div class="estado-opciones">
                <label class="estado-label">
                    <input type="radio" name="modalidad" value="1"
                        <?php echo $designacion_editar->modalidad == 1 ? 'checked' : ''; ?>>
                    <span class="radio-custom"></span>
                    Fútbol
                </label>
                <label class="estado-label">
                    <input type="radio" name="modalidad" value="2"
                        <?php echo $designacion_editar->modalidad == 2 ? 'checked' : ''; ?>>
                    <span class="radio-custom"></span>
                    Fútbol Sala
                </label>
            </div>
        </div>

        <!-- Espacio en blanco -->
        <div class="partidos__campo"></div>

        <!-- Botones -->
        <div style="display: flex; flex-direction: row;" class="partidos__campo filtros_botones">
            <button type="submit" class="alert partidos__boton-filtro">Guardar</button>
        </div>
    </div>
</form>