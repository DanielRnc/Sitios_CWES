<?php

$numA = $_GET['A'];
$numB = $_GET['B'];
$numC = $_GET['C'];

$raiz = $numB**2-4*$numA*$numC;

$resultadoMas = (-$numB + sqrt($raiz))/(2*$numA);
$resultadoMenos = (-$numB - sqrt($raiz))/(2*$numA);


echo "Resultado mas " . $resultadoMas;
echo "<br>";
echo "Resultado menos " . $resultadoMenos;
