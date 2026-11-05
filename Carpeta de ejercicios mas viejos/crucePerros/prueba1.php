<?php
require_once 'Perro.php';

$Perro = new Perro("Pastor Alemán", "Negro y Marron", 18.5, 3, "Macho");
$Perra = new Perro("Pastor Alemán", "Negro ", 28.0, 5, "hembra");

$hijo = $Perro->cruzar($Perra);

print($hijo);


