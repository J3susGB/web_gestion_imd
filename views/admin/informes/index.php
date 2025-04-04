<div class="dashboard__caja">
    <a href="/admin/dashboard" title="Volver a atrás">
        <pictures class="dashboard__caja--icono">
            <source srcset="/build/img/arrow.avif" type="image/avif">
            <source srcset="/build/img/arrow.webp" type="image/webp">
            <img loading="lazy" width="20" height="20" src="/build/img/arrow.png" alt="botón para ir a carga masiva de árbitros">
        </pictures>
    </a>
</div>

<h2 class="dashboard__heading"><?php echo $titulo ?></h2>

<div class="grid-container">
    <!-- <a href="#" class="grid-item home">HOME</a> -->
    <a href="/admin/informes/informes_partidos" class="grid-item">INFORMES DE PARTIDOS</a>
    <a href="/admin/informes/informes_distritos" class="grid-item">INFORMES DE DISTRITOS</a>
    <a href="/admin/informes/informes_arbitros" class="grid-item">INFORMES DE ÁRBITROS/AS</a>
    <a href="/admin/informes/informes_economicos" class="grid-item">INFORMES ECONÓMICOS</a>
    <a href="/admin/informes/informes_usuarios" class="grid-item">INFORMES DE USUARIOS</a>
    <a href="#" class="grid-item start-project">DESCARGAR INFORME ECONÓMICO</a>
</div>