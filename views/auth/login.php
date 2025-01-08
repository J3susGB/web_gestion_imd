<?php
require_once __DIR__ . '/../templates/alertas.php';
?>
<div class="contenedor-app">
    <div class="imagen"></div>
    <div class="app">
        <h2 class="auth__heading"><?php echo $titulo; ?></h2>
        <form class="formulario" method="POST" action="/">
            <div class="formulario__campo">
                <label class="formulario__label" for="usuario">Usuario</label>
                <input class="formulario__input"
                    type="text"
                    id="usuario"
                    placeholder="Introduce tu usuario"
                    name="usuario" />
            </div>
            <div class="formulario__campo">
                <label class="formulario__label" for="password">Contraseña</label>
                <input class="formulario__input"
                    type="password"
                    id="password"
                    placeholder="Introduce tu constraseña"
                    name="password" />
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/olvide">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>