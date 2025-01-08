<div class="dashboard__caja">
    <a href="/admin/partidos" title="Volver a partidos">
        <pictures class="dashboard__caja--icono">
            <source srcset="/build/img/arrow.avif" type="image/avif">
            <source srcset="/build/img/arrow.webp" type="image/webp">
            <img loading="lazy" width="20" height="20" src="/build/img/arrow.png" alt="botón para ir a carga masiva de árbitros">
        </pictures>
    </a>
</div>

<h2 class="dashboard__heading"><?php echo $titulo ?></h2>

<?php
require_once __DIR__ . '/../../templates/alertas.php';
?>

<!-- <h3 class="partidos__titulo-filtro">Agregar partido:</h3> -->
    <form method="post" action="/admin/partidos/agregar" class="partidos__partido partidos__filtro partidos__filtro-especifico">
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">ID partido</h4>
            <input
                type="text"
                id="id_partido"
                name="id_partido"
                placeholder="Ej: 405688"
                value="<?php echo $partido->id_partido ? $partido->id_partido : ''; ?>" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Categoria</h4>
            <input
                type="text"
                id="categoria"
                name="categoria"
                placeholder="Ej: SX"
                value="<?php echo $partido->categoria ? $partido->categoria : ''; ?>" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Grupo</h4>
            <input
                type="text"
                id="grupo"
                name="grupo"
                placeholder="Ej: N-01"
                value="<?php echo $partido->grupo ? $partido->grupo : ''; ?>" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Fecha</h4>
            <input
                type="text"
                id="fecha"
                name="fecha"
                placeholder="Ej: 12/02/2024"
                value="<?php echo $partido->fecha ? $partido->fecha : ''; ?>" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Hora</h4>
            <input
                type="text"
                id="hora"
                name="hora"
                placeholder="Ej: 18:00"
                value="<?php echo $partido->hora ? $partido->hora : ''; ?>" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Terreno</h4>
            <input
                type="text"
                id="terreno"
                name="terreno"
                placeholder="Ej: CD. ALCOSA F7 CESPED PISTA 2"
                value="<?php echo $partido->terreno ? $partido->terreno : ''; ?>" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Distrito</h4>
            <input
                type="text"
                id="distrito"
                name="distrito"
                placeholder="Ej: SUR"
                value="<?php echo $partido->distrito ? $partido->distrito : ''; ?>" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Jornada</h4>
            <input
                type="text"
                id="jornada"
                name="jornada"
                placeholder="Ej: 10"
                value="<?php echo $partido->jornada ? $partido->jornada : ''; ?>" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Equipo local</h4>
            <input
                type="text"
                id="local"
                name="local"
                placeholder="Ej: C.D TRIANA"
                value="<?php echo $partido->local ? $partido->local : ''; ?>" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Equipo visitante</h4>
            <input
                type="text"
                id="visitante"
                name="visitante"
                placeholder="Ej: PINO MONTANO B"
                value="<?php echo $partido->visitante ? $partido->visitante : ''; ?>" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Observaciones</h4>
            <input
                type="text"
                id="observaciones"
                name="observaciones"
                placeholder="Particularidades del partido"
                value="<?php echo $partido->observaciones ? $partido->observaciones : ''; ?>" />
        </div>
        
        
        
        <!-- <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Designado</h4>
            <div class="estado-opciones">
                <label class="estado-label">
                    <input <?php echo $partido->designado == "1" ? 'checked' : ''; ?> type="radio" name="designado" value="1">
                    <span class="radio-custom"></span>
                    Si
                </label>
                <label class="estado-label">
                    <input <?php echo $partido->designado == "0" ? 'checked' : ''; ?> type="radio" name="designado" value="0">
                    <span class="radio-custom"></span>
                    No
                </label>
            </div>
        </div> -->

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Elije modalidad</h4>
            <div class="estado-opciones">
                <label class="estado-label">
                    <input <?php echo $partido->modalidad == "1" ? 'checked' : ''; ?> type="radio" name="modalidad" value="1">
                    <span class="radio-custom"></span>
                    Fútbol
                </label>
                <label class="estado-label">
                    <input <?php echo $partido->modalidad == "2" ? 'checked' : ''; ?> type="radio" name="modalidad" value="2">
                    <span class="radio-custom"></span>
                    Fútbol Sala
                </label>
            </div>
        </div>
        

        <div class="alert partidos__campo">
            <button class="partidos__boton-filtro" type="submit">Guardar</button>
        </div>
    </form>