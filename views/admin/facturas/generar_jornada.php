<div class="dashboard__caja">
    <a href="/admin/facturas" title="Volver a facturas">
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

<form action="/admin/facturas/generar_jornada" method="post" class="partidos__partido partidos__filtro partidos__filtro-especifico">
    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Número de jornada</h4>
        <input
            type="text"
            id="jornada"
            name="jornada"
            placeholder="Ej: 9"
            value=""/>
    </div>
    <div class="partidos__campo">
        <button class=" alert partidos__boton-filtro" type="submit">Guardar</button>
    </div>
</form>