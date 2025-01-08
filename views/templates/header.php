<div class="header">
    <div class="header__texto">
        <h6>
            <?php 
                // Configurar la zona horaria
                date_default_timezone_set('Europe/Madrid');

                // Crear un formateador de fechas para español
                $formatter = new IntlDateFormatter(
                    'es_ES', // Idioma español
                    IntlDateFormatter::NONE, // Sin formato de fecha predefinido
                    IntlDateFormatter::NONE, // Sin formato de hora
                    'Europe/Madrid', // Zona horaria
                    IntlDateFormatter::GREGORIAN, // Calendario gregoriano
                    "dd 'de' MMMM 'de' yyyy" // Formato personalizado
                );

                // Mostrar la fecha actual
                echo $formatter->format(new DateTime());
            ?>
        </h6>
    </div>

    <div class="header__logo">
        <picture>
            <source srcset="/build/img/logo_fondo.webp">
            <source srcset="/build/img/logo_fondo.avif">
            <img src="/build/img/logo_fondo.jpg" alt="Logo de la página">
        </picture>
    </div>
</div>
