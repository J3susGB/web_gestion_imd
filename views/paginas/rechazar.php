<h2 class="dashboard__heading"><?php echo $titulo ?></h2>

<div class="aceptar">
    <div class="aceptar__box-pagina">
        <div class="aceptar__icono">
            <picture>
                <source srcset="/build/img/rechazado.avif" type="image/avif">
                <source srcset="/build/img/rechazado.webp" type="image/webp">
                <img id="rechazado" loading="lazy" width="20" height="20" src="/build/img/rechazado.png" alt="botón para resetear">
            </picture>
        </div>
        <div class="aceptar__titulo">
            <h4>ID partido: <?php echo $id_partido; ?></h4>
        </div>
        <div class="aceptar__texto">
            <p>El partido ha sido rechazado con éxito</p>
        </div>
    </div>
</div>