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

<div class="ajustes">
    <div class="ajustes__cajas">

        <!-- Fútbol -->
        <form class="ajustes__caja" method="post" action="/admin/acciones/guardar_futbol?id=<?php echo $p->id; ?>">
            <div class="ajustes__caja-titulo">
                <a href="#">Fútbol</a>
            </div>
            <div class="ajustes__caja-texto">
                <p class="ajustes__caja-texto--parrafo">Máximo de partidos:
                    <button class="accion" id="menos-futbol-partidos">-</button>
                    <span class="numero" id="futbol-partidos">
                        <?php
                        $numero_partidos = '-'; // Valor por defecto
                        if (!empty($restricciones)) {
                            foreach ($restricciones as $restriccion) {
                                if ($restriccion->modalidad == 1) {
                                    $numero_partidos = ($restriccion->activo == 1) ? $restriccion->numero_partidos : '-';
                                    break; // Detener el bucle una vez encontrada la restricción
                                }
                            }
                        }
                        echo $numero_partidos;
                        ?>
                    </span>
                    <button class="accion" id="mas-futbol-partidos">+</button>
                </p>
                <p class="ajustes__caja-texto--parrafo">Máximo de SX/JX:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="accion" id="menos-futbol-sxjx">-</button>
                    <span id="futbol-sxjx">
                        <?php
                        $numero_metalico = '-'; // Valor por defecto
                        if (!empty($restricciones)) {
                            foreach ($restricciones as $restriccion) {
                                if ($restriccion->modalidad == 1) {
                                    $numero_metalico = ($restriccion->activo == 1) ? $restriccion->numero_metalico : '-';
                                    break;
                                }
                            }
                        }
                        echo $numero_metalico;
                        ?>
                    </span>
                    <button class="accion" id="mas-futbol-sxjx">+</button>
                </p>
                <button id="guardar-futbol" class="arbitros__card-acciones--eliminar" type="button">
                    <picture>
                        <source srcset="/build/img/save.avif" type="image/avif">
                        <source srcset="/build/img/save.webp" type="image/webp">
                        <img loading="lazy" width="20" height="20" src="/build/img/save.png" alt="botón para resetear">
                    </picture>
                </button>
            </div>
        </form>


        <!-- Sala -->
        <form class="ajustes__caja" method="post" action="/admin/acciones/guardar_sala?id=<?php echo $p->id; ?>">
            <div class="ajustes__caja-titulo">
                <a href="#">Sala</a>
            </div>
            <div class="ajustes__caja-texto">
                <p class="ajustes__caja-texto--parrafo">Máximo de partidos:
                    <button class="accion" id="menos-sala-partidos">-</button>
                    <span id="sala-partidos">
                        <?php
                        $numero_partidos = '-'; // Valor por defecto
                        if (!empty($restricciones)) {
                            foreach ($restricciones as $restriccion) {
                                if ($restriccion->modalidad == 2) {
                                    $numero_partidos = ($restriccion->activo == 1) ? $restriccion->numero_partidos : '-';
                                    break; // Detener el bucle al encontrar la restricción correcta
                                }
                            }
                        }
                        echo $numero_partidos;
                        ?>
                    </span>
                    <button class="accion" id="mas-sala-partidos">+</button>
                </p>
                <p class="ajustes__caja-texto--parrafo">Máximo de SX/JX:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="accion" id="menos-sala-sxjx">-</button>
                    <span id="sala-sxjx">
                        <?php
                        $numero_metalico = '-'; // Valor por defecto
                        if (!empty($restricciones)) {
                            foreach ($restricciones as $restriccion) {
                                if ($restriccion->modalidad == 2) {
                                    $numero_metalico = ($restriccion->activo == 1) ? $restriccion->numero_metalico : '-';
                                    break;
                                }
                            }
                        }
                        echo $numero_metalico;
                        ?>
                    </span>
                    <button class="accion" id="mas-sala-sxjx">+</button>
                </p>
                <button id="guardar-sala" class="arbitros__card-acciones--eliminar" type="button">
                    <picture>
                        <source srcset="/build/img/save.avif" type="image/avif">
                        <source srcset="/build/img/save.webp" type="image/webp">
                        <img loading="lazy" width="20" height="20" src="/build/img/save.png" alt="botón para resetear">
                    </picture>
                </button>
            </div>
        </form>

    </div>