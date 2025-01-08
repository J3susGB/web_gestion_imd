

<div class="header">

    <div class="header__logo">
        <picture>
            <source srcset="/build/img/logo_fondo.webp">
            <source srcset="/build/img/logo_fondo.avif">
            <img src="/build/img/logo_fondo.jpg" alt="Logo de la página">
        </picture>
    </div>

    <div class="header__texto">
        <h6>
            <?php 

                echo $usuario->nombre . " " . $usuario->apellido1 . " " . $usuario->apellido2;
            ?>
        </h6>
        <form action="/logout" method="post"> 
            <button type="submit">
                <!--<i class="fa-solid fa-power-off"></i>-->
                <pictures>
                    <source srcset="/build/img/power.avif" type="image/avif">
                    <source srcset="/build/img/power.webp" type="image/webp">
                    <img loading="lazy" width="20" height="20" src="/build/img/power.png" alt="botón para resetear">
                </pictures>
            </button>
        </form>
    </div>
</div>
