<h2 class="dashboard__heading"><?php echo $titulo ?></h2>

<?php
require_once __DIR__ . '/../../templates/alertas.php';
?>

<form method="post" class="partidos__partido partidos__filtro partidos__filtro-especifico">

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Usuario</h4>
        <input
            type="text"
            id="user"
            name="user"
            placeholder="Ej: sjsantizo"
            value="" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Nueva contraseña</h4>
        <input
            class="input_password1"
            type="text"
            id="password"
            name="password"
            placeholder="Mínimo 6 caracteres"
            value="" />
    </div>

    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Repite contraseña</h4>
        <input
            class="input_password1"
            type="text"
            id="password2"
            name="password2"
            placeholder="Introduce de nuevo la contraseña"
            value="" />
    </div>

    <div class="partidos__campo">
        <button class="alert partidos__boton-filtro" type="submit">Guardar</button>
    </div>
</form>