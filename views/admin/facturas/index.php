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

<?php if (empty($jornada)) { ?>
    <div class="partidos__partido-1">
        <p>Esperando carga de designaciones</p>
        <div class="partidos__loader"></div>
    </div>
<?php } else { ?>
    <?php if (!$jornada_generada) { ?>

        <div class="partidos__reset partidos__subir">
            <form>
                <p>Pulsa para generar la jornada</p>
                <a href="/admin/facturas/generar_jornada" class="reset-button pulse">
                    <picture class="pulse">
                        <source srcset="/build/img/generar_jornada.avif" type="image/avif">
                        <source srcset="/build/img/generar_jornada.webp" type="image/webp">
                        <img loading="lazy" width="20" height="20" src="/build/img/generar_jornada.png" alt="botón para resetear">
                    </picture>
                </a>
            </form>
        </div>
    <?php } else { ?>
        <div class="facturas">
            <?php foreach ($jornadas_agrupadas as $jornada => $designaciones) { ?>
                <div class="facturas__caja">
                    <div class="facturas__jornada">
                        <h4><?php echo "Jornada " . $jornada; ?></h4>
                    </div>
                    <div class="facturas__acciones">
                        <!-- Botón para Editar Jornada -->
                        <a title="ver Jornada" class="arbitros__card-acciones--editar" href="/admin/facturas/ver?jornada_editada=<?php echo $jornada; ?>">
                            <picture>
                                <source srcset="/build/img/editar_jornada.avif" type="image/avif">
                                <source srcset="/build/img/editar_jornada.webp" type="image/webp">
                                <img loading="lazy" width="20" height="20" src="/build/img/editar_jornada.png" alt="botón para editar jornada">
                            </picture>
                        </a>
                        <!-- Botón para Generar Factura -->
                        <form class="partidos__acciones--enviar" method="post" action="/admin/facturas/generar_factura?jornada=<?php echo $jornada; ?>">
                            <button class="arbitros__card-acciones--eliminar" type="submit">
                                <picture>
                                    <source srcset="/build/img/generar_factura.avif" type="image/avif">
                                    <source srcset="/build/img/generar_factura.webp" type="image/webp">
                                    <img id="generar_factura" class="generar_factura" loading="lazy" width="20" height="20" src="/build/img/generar_factura.png" alt="botón para generar factura">
                                </picture>
                            </button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>







    <?php } ?>

<?php } ?>