<div class="header">
    
    <div class="header__texto">
        <h6>
            <?php 
                setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp'); // Configura el idioma a español
                echo strftime("%d de %B de %Y"); // Ejemplo: 15 de diciembre de 2024
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