<div class="dashboard__caja">
    <a href="/admin/perfiles" title="Volver a partidos">
        <pictures class="dashboard__caja--icono">
            <source srcset="/build/img/arrow.avif" type="image/avif">
            <source srcset="/build/img/arrow.webp" type="image/webp">
            <img loading="lazy" width="20" height="20" src="/build/img/arrow.png" alt="botón para ir a carga masiva de árbitros">
        </pictures>
    </a>
</div>
<h2 class="dashboard__heading"><?php echo $titulo ?></h2>

<?php
require_once __DIR__ . '/../../templates/alertas.php';
?>

<form action="/admin/perfiles/agregar" method="post" class="partidos__partido partidos__filtro partidos__filtro-especifico">
    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Nombre</h4>
        <input
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Ej: Santiago"
            value="<?php echo $user->nombre ? $user->nombre : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Primer apellido</h4>
        <input
            type="text"
            id="apellido1"
            name="apellido1"
            placeholder="Ej: Santizo"
            value="<?php echo $user->apellido1 ? $user->apellido1 : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Segundo apellido</h4>
        <input
            type="text"
            id="apellido2"
            name="apellido2"
            placeholder="Ej: Álvarez"
            value="<?php echo $user->apellido2 ? $user->apellido2 : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Usuario</h4>
        <input
            type="text"
            id="usuario"
            name="usuario"
            placeholder="Ej: sjsantizo"
            value="<?php echo $user->usuario ? $user->usuario : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Email</h4>
        <input
            type="text"
            id="email"
            name="email"
            placeholder="Ej: sjsantizo@rfaf.es"
            value="<?php echo $user->email ? $user->email : ''; ?>" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Contraseña</h4>
        <input
            class="input_password1"
            type="text"
            id="password"
            name="password"
            placeholder="Mínimo 6 caracteres"
            value="<?php echo $user->password ? $user->password : ''; ?>" />
    </div>


    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Administrador</h4>
        <div class="estado-opciones">
            <label class="estado-label">
                <input <?php echo $user->admin === 1 ? 'checked' : ''; ?> type="radio" name="admin" value="1">
                <span class="radio-custom"></span>
                Si
            </label>
            <label class="estado-label">
                <input <?php echo $user->admin === 0 ? 'checked' : ''; ?> type="radio" name="admin" value="0">
                <span class="radio-custom"></span>
                No
            </label>
        </div>
    </div>

    <div class="partidos__campo">
        <button class="alert partidos__boton-filtro" type="submit">Guardar</button>
    </div>
</form>