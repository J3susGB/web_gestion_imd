<h2 class="dashboard__heading"><?php echo $titulo ?></h2>

<div class="aceptar">
    <div class="aceptar__box-pagina">
        <div class="aceptar__icono">
            <picture>
                <source srcset="/build/img/enviado.avif" type="image/avif">
                <source srcset="/build/img/enviado.webp" type="image/webp">
                <img id="aceptado" loading="lazy" width="20" height="20" src="/build/img/enviado.png" alt="botón para resetear">
            </picture>
        </div>
        <div class="aceptar__titulo">
            <h4>ID partido: <?php echo $partido->id_partido; ?></h4>
        </div>
        <div class="aceptar__texto">
            <p>El partido ha sido aceptado con éxito</p>
        </div>
    </div>
</div>