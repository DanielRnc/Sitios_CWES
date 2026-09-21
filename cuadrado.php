<?php

$lado = $_POST['numero'];
$resultado = $lado*$lado;
print_r ("El area del Cuadrado es: " . $resultado);

if ($lado < 0)
{
    print_r ("EL LADO NO PUEDE SER NEGATIVO")
}elseif ($lado >1)
{
    
}