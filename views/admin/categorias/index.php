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
            <a href="/admin/categorias/agregar" title="Agregar">
                <pictures class="dashboard__caja--icono categorias">
                    <source srcset="/build/img/categorias.avif" type="image/avif">
                    <source srcset="/build/partido.webp" type="image/webp">
                    <img style="width: 30px;" loading="lazy" width="20" height="20" src="/build/img/categorias.png" alt="botón para añadir árbitro">
                </pictures>
            </a>
        </div>
    </div>
    <h2 class="dashboard__heading"><?php echo $titulo ?></h2>
</div>

<?php if (empty($categorias)) { ?>
    <div class="partidos__partido-1">
        <p>Esperando carga de categorías</p>
        <div class="partidos__loader"></div>
    </div>
<?php } else { ?>

    <?php foreach ($categorias as $d) { ?>
        <div id="<?php echo $d->nombre; ?>" class="partidos__partido" data-partido-id="<?php echo $d->id; ?>">

            <div class="partidos__partido--id1">
                <div class="partidos__partido--logos">
                    <?php foreach ($modalidades as $m) { ?>
                        <p>
                            <?php echo ($m->id == $d->id_modalidad ? $d->nombre . " - " . $m->nombre : ''); ?>
                        </p>

                    <?php } ?>
                </div>
            </div>

            <div class="partidos__partido--facturacion">

                <div class="partidos__partido__unidad">
                    <p class="partidos__partido__unidad--label">Tarifa</p>
                    <p><?php echo $d->tarifa . " €"; ?></p>
                </div>

                <div class="partidos__partido__unidad">
                    <p class="partidos__partido__unidad--label">Pago árbitr@</p>
                    <p><?php echo $d->pago_arbitro . " €"; ?></p>
                </div>

                <div class="partidos__partido__unidad">
                    <p class="partidos__partido__unidad--label">OA</p>
                    <p><?php echo $d->oa . " €"; ?></p>
                </div>
            </div>

            <div class="partidos__acciones">
                <a title="Editar" class="arbitros__card-acciones--editar" href="/admin/categorias/editar?id=<?php echo $d->id; ?>">
                    <picture>
                        <source srcset="/build/img/edit.avif" type="image/avif">
                        <source srcset="/build/img/edit.webp" type="image/webp">
                        <img loading="lazy" width="20" height="20" src="/build/img/edit.png" alt="botón para resetear">
                    </picture>
                </a>
                <form class="partidos__acciones--eliminar" method="post" action="/admin/categorias/eliminar?id=<?php echo $d->id; ?>">
                    <button class="alert5 arbitros__card-acciones--eliminar" type="button">
                        <picture>
                            <source srcset="/build/img/envase.avif" type="image/avif">
                            <source srcset="/build/img/envase.webp" type="image/webp">
                            <img loading="lazy" width="20" height="20" src="/build/img/envase.png" alt="botón para resetear">
                        </picture>
                    </button>
                </form>
            </div>
        </div>
    <?php } ?>
<?php } ?>

<div class="botones__scroll">
    <div class="botones__scroll--boton">
        <picture>
            <source srcset="/build/img/scroll.avif" type="image/avif">
            <source srcset="/build/img/scroll.webp" type="image/webp">
            <img id="arriba" loading="lazy" width="20" height="20" src="/build/img/scroll.png" alt="botón para aceptar">
        </picture>
    </div>
    <div class="botones__scroll--boton">
        <picture>
            <source srcset="/build/img/scroll.avif" type="image/avif">
            <source srcset="/build/img/scroll.webp" type="image/webp">
            <img id="abajo" loading="lazy" width="20" height="20" src="/build/img/scroll.png" alt="botón para aceptar">
        </picture>
    </div>
</div>