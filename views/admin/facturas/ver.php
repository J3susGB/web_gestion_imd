<div class="dashboard__caja">
    <a href="/admin/facturas" title="Volver a atrás">
        <picture class="dashboard__caja--icono">
            <source srcset="/build/img/arrow.avif" type="image/avif">
            <source srcset="/build/img/arrow.webp" type="image/webp">
            <img loading="lazy" width="20" height="20" src="/build/img/arrow.png" alt="botón para ir a carga masiva de árbitros">
        </picture>
    </a>
</div>

<h2 class="dashboard__heading"><?php echo $titulo ?></h2>

<?php foreach ($designaciones as $d) { ?>
    <div id="<?php echo $d->id_partido; ?>" class="partidos__partido" data-partido-id="<?php echo $d->id_partido; ?>">

        <div class="partidos__partido--id1">
            <div class="partidos__partido--logos">
                <?php foreach ($modalidades as $m) { ?>
                    <p>
                        <?php echo $m->id == $d->modalidad ? $m->nombre . "<strong style='color: #DDDDDD;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|| &nbsp;&nbsp;</strong> " : ''; ?>
                    </p>
                <?php } ?>
                <p>
                    <?php echo $d->id_partido . " - " . $d->categoria; ?>
                </p>
                <?php foreach ($arbitros as $p) { ?>
                    <?php if ($p->id == $d->id_arbitro) { ?>
                        <p>
                            <?php
                            echo "<strong style='color: #DDDDDD;'>&nbsp;&nbsp;&nbsp;|| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong> " . $p->apellido1 . " " . $p->apellido2 . ", " . $p->nombre;
                            ?>
                        </p>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

        <div class="partidos__partido--texto">
            <div class="partidos__partido--fecha">
                <p><?php echo $d->fecha; ?></p>
                <p><?php echo $d->hora; ?></p>
            </div>
            <p><?php echo $d->terreno; ?></p>
            <p><?php echo $d->local . " - " . $d->visitante; ?></p>
        </div>

        <?php
        // Valores predeterminados
        $tarifaEncontrada = 'Error';
        $facturarEncontrado = 'Error';
        $pagoArbitroEncontrado = 'Error';
        $oaEncontrado = 'Error';

        foreach ($categorias as $c) {
            $categoria = trim((string)$d->categoria);
            $nombre2 = trim((string)$c->nombre2);
            $modalidad = (int)$d->modalidad;
            $id_modalidad = (int)$c->id_modalidad;

            if ($categoria === $nombre2 && $modalidad === $id_modalidad) {
                $tarifaEncontrada = htmlspecialchars($c->tarifa);
                $facturarEncontrado = htmlspecialchars($c->facturar);
                $pagoArbitroEncontrado = htmlspecialchars($c->pago_arbitro);
                $oaEncontrado = htmlspecialchars($c->oa);
                break; // Salir del bucle tras encontrar coincidencia
            }
        }
        ?>

        <div class="partidos__partido--facturacion">
            <div class="partidos__partido__unidad">
                <p class="partidos__partido__unidad--label">Unidad</p>
                <p><?php echo $d->unidad ." €"; ?></p>
            </div>

            <div class="partidos__partido__unidad">
                <p class="partidos__partido__unidad--label">Tarifa</p>
                <p><?php echo $tarifaEncontrada ." €"; ?></p>
            </div>

            <div class="partidos__partido__unidad">
                <p class="partidos__partido__unidad--label">Facturar</p>
                <p><?php echo $facturarEncontrado ." €"; ?></p>
            </div>

            <div class="partidos__partido__unidad">
                <p class="partidos__partido__unidad--label">Pago árbitr@</p>
                <p><?php echo $pagoArbitroEncontrado ." €"; ?></p>
            </div>

            <div class="partidos__partido__unidad">
                <p class="partidos__partido__unidad--label">OA</p>
                <p><?php echo $oaEncontrado." €"; ?></p>
            </div>
        </div>

        <div class="partidos__acciones">
            <a title="Editar" class="arbitros__card-acciones--editar" href="/admin/facturas/editar?id=<?php echo $d->id; ?>">
                <picture>
                    <source srcset="/build/img/editar_factura.avif" type="image/avif">
                    <source srcset="/build/img/editar_factura.webp" type="image/webp">
                    <img loading="lazy" width="20" height="20" src="/build/img/editar_factura.png" alt="botón para resetear">
                </picture>
            </a>
        </div>
    </div>
<?php } ?>