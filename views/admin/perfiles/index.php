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
            <a href="/admin/perfiles/agregar" title="Agregar usuario">
                <pictures class="dashboard__caja--icono agregar">
                    <source srcset="/build/img/perfiles.avif" type="image/avif">
                    <source srcset="/build/img/perfiles.webp" type="image/webp">
                    <img loading="lazy" width="20" height="20" src="/build/img/perfiles.png" alt="botón para añadir árbitro">
                </pictures>
            </a>
        </div>
    </div>
    <h2 class="dashboard__heading"><?php echo $titulo ?></h2>
</div>

<?php if (empty($perfiles)) { ?>
    <div class="partidos__partido-1">
        <p>Esperando carga de perfiles</p>
        <div class="partidos__loader"></div>
    </div>


<?php } else { ?>

    <!-- <h3 class="partidos__titulo-filtro">Realice su búsqueda:</h3>
    <div class="partidos__partido partidos__filtro partidos__filtro-especifico">
        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por nombre y apellidos</h4>
            <input
                type="text"
                id="nombre"
                name="nombre"
                placeholder="Ej: Santizo Álvarez"
                value="" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Búsqueda por usuario</h4>
            <input
                type="text"
                id="usuario"
                name="usuario"
                placeholder="Ej: sjsantizo"
                value="" />
        </div>

        <div class="partidos__campo">
            <h4 class="partidos__titulo-filtros">Administrador</h4>
            <div class="estado-opciones">
                <label class="estado-label">
                    <input type="radio" name="admin" value="1">
                    <span class="radio-custom"></span>
                    Si
                </label>
                <label class="estado-label">
                    <input type="radio" name="admin" value="0">
                    <span class="radio-custom"></span>
                    No
                </label>
            </div>
        </div>

        <div class="partidos__campo">
            <button class="partidos__boton-filtro" type="button">Borrar filtros</button>
        </div>
    </div> -->

    <div class="arbitros">
        <?php foreach ($perfiles as $a) { ?>
            <div class="arbitros__card">
                <?php if ($a->admin == "0") { ?>
                    <div class="arbitros_card-top">
                        <div class="arbitros__card-titulo">
                            <p class="arbitros__card--titulo1"><?php echo $a->nombre . " " . $a->apellido1 . " " . $a->apellido2; ?></p>
                            <p class="arbitros__card--titulo"><?php echo $a->admin === "1" ? 'Administrador' : 'Usuario'; ?></p>
                        </div>
                        <div class="toggles">
                            <div class="toggle-border">
                                <input class="toogle_check"
                                    type="checkbox"
                                    id="toggle-<?php echo $a->id; ?>"
                                    <?php echo $a->activo == "1" ? 'checked' : ''; ?>
                                    data-id="<?php echo $a->id; ?>" />
                                <label class="toogle_label" for="toggle-<?php echo $a->id; ?>">
                                    <div class="handle"></div>
                                </label>
                            </div>
                        </div>

                    </div>
                <?php } else if ($a->admin == "1") { ?>
                    <div class="arbitros_card-top">
                        <div class="arbitros__card-titulo">
                            <p class="arbitros__card--titulo1-rojo"><?php echo $a->nombre . " " . $a->apellido1 . " " . $a->apellido2; ?></p>
                            <p class="arbitros__card--titulo-rojo"><?php echo $a->admin === "1" ? 'Administrador' : 'Usuario'; ?></p>
                        </div>
                        <div class="toggles">
                            <div class="toggle-border">
                                <input class="toogle_check"
                                    type="checkbox"
                                    id="toggle-<?php echo $a->id; ?>"
                                    <?php echo $a->activo == "1" ? 'checked' : ''; ?>
                                    data-id="<?php echo $a->id; ?>" />
                                <label class="toogle_label" for="toggle-<?php echo $a->id; ?>">
                                    <div class="handle"></div>
                                </label>
                            </div>
                        </div>

                    </div>
                <?php } ?>
                <div class="arbitros__card-texto">
                    <p class="arbitros__card--telefono">Usuario: <?php echo $a->usuario; ?></p>
                </div>
                <div class="arbitros__card-acciones">
                    <a title="Editar" href="/admin/perfiles/editar?id=<?php echo $a->id; ?>" class="arbitros__card-acciones--editar">
                        <pictures>
                            <source srcset="/build/img/edit.avif" type="image/avif">
                            <source srcset="/build/img/edit.webp" type="image/webp">
                            <img loading="lazy" width="20" height="20" src="/build/img/edit.png" alt="botón para resetear">
                        </pictures>
                    </a>
                    <form method="POST" class="arbitros__card-acciones--restablecer">
                        <input type="hidden" name="id" value="<?php echo $a->id ?>">
                        <button class="alert_restablecer arbitros__card-acciones--eliminar" type="button">
                            <pictures>
                                <source srcset="/build/img/reset_clave.avif" type="image/avif">
                                <source srcset="/build/img/reset_clave.webp" type="image/webp">
                                <img loading="lazy" width="20" height="20" src="/build/img/reset_clave.png" alt="botón para resetear">
                            </pictures>
                        </button>
                    </form>
                    <form method="POST" action="/admin/perfiles/eliminar?id=<?php echo $a->id; ?>" class="arbitros__card-acciones--eliminar">
                        <input type="hidden" name="id" value="<?php echo $a->id ?>">

                        <button class="alert2 arbitros__card-acciones--eliminar" type="button">
                            <pictures>
                                <source srcset="/build/img/envase.avif" type="image/avif">
                                <source srcset="/build/img/envase.webp" type="image/webp">
                                <img loading="lazy" width="20" height="20" src="/build/img/envase.png" alt="botón para resetear">
                            </pictures>
                        </button>
                    </form>
                </div>
            </div>
        <?php } ?>

    </div>

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
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.alert_restablecer');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const form = button.closest('form'); // Obtiene el formulario más cercano al botón
                const formData = new FormData(form); // Crea un objeto FormData con los datos del formulario

                // URL específica para el envío
                const url = '/admin/perfiles/restablecer_password';

                fetch(url, {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la solicitud'); // Manejo de errores si la respuesta no es OK
                        }
                        return response.json(); // Procesa la respuesta como JSON
                    })
                    .then(data => {
                        if (data.success) {
                            mostrar_alerta(data.message); // Muestra alerta de éxito
                        } else {
                            mostrar_error(data.message); // Muestra alerta de error
                        }
                    })
                    .catch(error => {
                        console.error('Hubo un problema con la solicitud:', error); // Muestra el error en la consola
                        mostrar_error('Hubo un problema inesperado.');
                    });
            });
        });

        function mostrar_alerta(message) {
            Swal.fire({
                icon: "success", // Ícono de éxito
                title: message, // Mensaje dinámico
                background: "#4e4d4b", // Fondo personalizado de la alerta
                color: "#DDDDDD", // Color del texto
                customClass: {
                    popup: 'custom-popup', // Clase personalizada para el popup
                    title: 'custom-title', // Clase personalizada para el título
                    confirmButton: 'custom-button' // Clase personalizada para el botón de confirmación
                },
                showConfirmButton: true, // Mostrar botón de confirmación
                confirmButtonColor: "#71B100", // Color para el botón de confirmación
                timer: 3000, // Tiempo automático de cierre (opcional)
            });
        }

        function mostrar_error(message) {
            Swal.fire({
                icon: "error", // Ícono de error
                title: message, // Mensaje dinámico
                background: "#4e4d4b", // Fondo personalizado
                color: "#DDDDDD", // Color del texto
                showConfirmButton: true, // Mostrar botón de confirmación
                confirmButtonColor: "#FF0000", // Color para el botón de confirmación
            });
        }
    });
</script>