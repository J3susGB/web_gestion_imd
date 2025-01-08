<div class="header">
    <div class="header__texto">
        <h6>
            <?php 
                // Asegúrate de que la zona horaria está configurada
                date_default_timezone_set('Europe/Madrid');

                // Usar IntlDateFormatter para mostrar la fecha en español
                $formatter = new IntlDateFormatter(
                    'es_ES', // Locale en español
                    IntlDateFormatter::FULL, // Formato de fecha completo
                    IntlDateFormatter::NONE, // Sin hora
                    'Europe/Madrid', // Zona horaria
                    IntlDateFormatter::GREGORIAN, // Calendario Gregoriano
                    'dd de MMMM de yyyy' // Formato de salida de la fecha
                );

                // Mostrar la fecha actual formateada
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

