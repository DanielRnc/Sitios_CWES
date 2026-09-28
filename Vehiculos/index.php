<?php
    require("Parking.php");
?>

<h1>Ejemplo 4: Herencia, Extensión y Polimorfismo</h1>

<?php
    $autobus = new Autobus("Volvo","9800 2017","gris","Mario","Desfufor");
?>

<div>
    ¿Puedo aparcar el coche en la superficie?:
    <strong>
        <?php 
            echo ($autobus->puedeAparcar("subterraneo1")) ? "si" : "no" 
        ?>
    </strong>
</div>