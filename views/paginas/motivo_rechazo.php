<h2 class="dashboard__heading"><?php echo $titulo ?></h2>

<form id="motivo_rechazo" class="partidos__partido partidos__filtro partidos__filtro-especifico" method="post">
    <div class="partidos__campo">
        <h4 class="partidos__titulo-filtros">Motivo del rechazo</h4>
        <textarea
            id="motivo"
            name="motivo"
            placeholder="Introduce un motivo justificado"
            rows="4"
            cols="50"><?php echo $partido->observaciones ? $partido->observaciones : ''; ?></textarea>
    </div>

    <button class="partidos__boton-filtro" type="submit">Enviar</button>
</form>