<?php
    $alertas = $alertas ?? [];
    foreach($alertas as $key => $alerta) {
        foreach($alerta as $mensaje) {
?>
    <div class="alerta alerta__<?php echo $key; ?>">
        <i class="fa-solid fa-circle-exclamation"></i>
        <?php echo $mensaje; ?>
    </div>
<?php 
        }
    }
?>
