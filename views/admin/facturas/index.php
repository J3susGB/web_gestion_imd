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

        <form id="formJornada" method="POST" action="/admin/facturas/generar_factura">
            <div class="facturas">
                <?php foreach ($jornadas_agrupadas as $jornada => $designaciones) { ?>
                    <div class="facturas__caja">
                        <input type="checkbox" name="seleccionar[]" value="<?php echo $jornada; ?>"> <!-- Solo por jornada -->
                        <div class="facturas__jornada">
                            <h4><?php echo "Jornada " . $jornada; ?></h4> <!-- Muestra el número de la jornada -->
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
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- <div class="partidos__container">
                <button title="Generar facturas seleccionadas" class="partidos__enviar" type="submit" id="submitFactura">Factura</button>
            </div> -->
            <div class="button-container-1 partidos__container" id="button__factura">
                <span class="mas">Enviar</span>
                <button title="Generar facturas seleccionadas" class="partidos__enviar" type="submit" id="submitFactura" name="Hover">Enviar</button>
            </div>
        </form>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ✅ Validación del formulario de envío
        const submitButton = document.getElementById('submitFactura');
        const formPartidos = document.getElementById('formJornada');

        submitButton.addEventListener('click', function(event) {
            const checkboxes = document.querySelectorAll('input[name="seleccionar[]"]:checked');

            if (checkboxes.length === 0) {
                event.preventDefault(); // Evita el envío si no hay partidos seleccionados
                alert('Por favor, seleccione las jornadas para generar la factura.');
            } else {
                console.log('Enviando formulario...');
                formPartidos.submit(); // Envía el formulario manualmente
            }
        });

        // Capturar el evento submit del formulario para depuración
        formPartidos.addEventListener('submit', function(event) {
            console.log('Formulario enviado correctamente.');
        });
    });
</script>