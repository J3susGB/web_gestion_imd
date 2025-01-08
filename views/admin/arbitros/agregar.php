<div class="dashboard__caja">
    <a href="/admin/arbitros" title="Volver a árbitros">
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

<!-- <h3 class="partidos__titulo-filtro">Agregar árbitro/a:</h3> -->
<form action="/admin/arbitros/agregar" method="post" class="partidos__partido partidos__filtro partidos__filtro-especifico">
    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Nombre</h4>
        <input
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Ej: Santiago"
            value="<?php echo $arbitro->nombre ? $arbitro->nombre : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Primer apellido</h4>
        <input
            type="text"
            id="apellido1"
            name="apellido1"
            placeholder="Ej: Santizo"
            value="<?php echo $arbitro->apellido1 ? $arbitro->apellido1 : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Segundo apellido</h4>
        <input
            type="text"
            id="apellido2"
            name="apellido2"
            placeholder="Ej: Álvarez"
            value="<?php echo $arbitro->apellido2 ? $arbitro->apellido2 : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Email</h4>
        <input
            type="text"
            id="email"
            name="email"
            placeholder="Ej: sjsantizo@rfaf.es"
            value="<?php echo $arbitro->email ? $arbitro->email : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Teléfono</h4>
        <input
            type="text"
            id="telefono"
            name="telefono"
            placeholder="Ej: 647654009"
            value="<?php echo $arbitro->telefono ? $arbitro->telefono : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Código postal</h4>
        <input
            type="text"
            id="cp"
            name="cp"
            placeholder="Ej: 41006"
            value="<?php echo $arbitro->codigo_postal ? $arbitro->codigo_postal : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Coche</h4>
        <div class="estado-opciones">
            <label class="estado-label">
                <input <?php echo $arbitro->coche === 1 ? 'checked' : ''; ?> type="radio" name="coche" value="1">
                <span class="radio-custom"></span>
                Si
            </label>
            <label class="estado-label">
                <input <?php echo $arbitro->coche === 0 ? 'checked' : ''; ?> type="radio" name="coche" value="0">
                <span class="radio-custom"></span>
                No
            </label>
        </div>
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Elije modalidad</h4>
        <div class="estado-opciones">
            <label class="estado-label">
                <input <?php echo $arbitro->modalidad === 1 ? 'checked' : ''; ?> type="radio" name="deporte" value="1">
                <span class="radio-custom"></span>
                Fútbol
            </label>
            <label class="estado-label">
                <input <?php echo $arbitro->modalidad === 2 ? 'checked' : ''; ?> type="radio" name="deporte" value="2">
                <span class="radio-custom"></span>
                Fútbol Sala
            </label>
        </div>
    </div>


    <div class="partidos__campo">
        <button class=" alert partidos__boton-filtro" type="submit">Guardar</button>
    </div>
</form>