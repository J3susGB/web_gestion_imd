<div class="dashboard__caja">
    <a href="/admin/categorias" title="Volver a categorias">
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

<form  method="post" class="partidos__partido partidos__filtro partidos__filtro-especifico">
    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Nombre</h4>
        <input
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Ej: FUTBOL7_PQ_ARBITRO"
            value="<?php echo $categoria->nombre ? $categoria->nombre : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Nombre corto</h4>
        <input
            type="text"
            id="nombre2"
            name="nombre2"
            placeholder="Ej: PQ"
            value="<?php echo $categoria->nombre2 ? $categoria->nombre2 : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Tarifa</h4>
        <input
            type="text"
            id="tarifa"
            name="tarifa"
            placeholder="Ej: 17.00"
            value="<?php echo $categoria->tarifa ? $categoria->tarifa : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Facturar</h4>
        <input
            type="text"
            id="facturar"
            name="facturar"
            placeholder="Ej: 17.00"
            value="<?php echo $categoria->facturar ? $categoria->facturar : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Pago árbitr@</h4>
        <input
            type="text"
            id="pago_arbitro"
            name="pago_arbitro"
            placeholder="Ej: 15.40"
            value="<?php echo $categoria->pago_arbitro ? $categoria->pago_arbitro : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Organización arbitral</h4>
        <input
            type="text"
            id="oa"
            name="oa"
            placeholder="Ej: 1.60"
            value="<?php echo $categoria->oa ? $categoria->oa : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Elije modalidad</h4>
        <div class="estado-opciones">
            <label class="estado-label">
                <input <?php echo $categoria->id_modalidad == 1 ? 'checked' : ''; ?> type="radio" name="id_modalidad" value="1">
                <span class="radio-custom"></span>
                Fútbol
            </label>
            <label class="estado-label">
                <input <?php echo $categoria->id_modalidad == 2 ? 'checked' : ''; ?> type="radio" name="id_modalidad" value="2">
                <span class="radio-custom"></span>
                Fútbol Sala
            </label>
        </div>
    </div>

    <div class="partidos__campo">
        <button class=" alert partidos__boton-filtro" type="submit">Guardar</button>
    </div>
</form>