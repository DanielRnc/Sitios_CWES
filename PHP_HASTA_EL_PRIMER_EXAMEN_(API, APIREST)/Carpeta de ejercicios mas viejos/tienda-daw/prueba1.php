<?php 
require_once __DIR__ . '/src/interfaces/Enviable.php';
require_once __DIR__ . '/src/interfaces/Descargable.php';

require_once __DIR__ . '/src/traits/FormateaPrecio.php';
require_once __DIR__ . '/src/traits/Descontable.php';

require_once __DIR__ . '/src/modelo/Producto.php';
require_once __DIR__ . '/src/modelo/Libro.php';
require_once __DIR__ . '/src/modelo/Ebook.php';
require_once __DIR__ . '/src/modelo/Camiseta.php';
require_once __DIR__ . '/src/modelo/PackLibro.php';

$libro = new Libro('LIBRO', 'libro1', 24.00, 'Laura', 0.8);
$ebook = new Ebook('EBOOK', 'ebook1', 9.99, 'EPUB', 3.2);
$camiseta = new Camiseta('CAMISETA', 'Camiseta1', 15.00, 'M', 0.2);
$pack = new PackLibro('PACKLIBRO', 'packlibroº', 32.00, 1.1, 8.4);

$libro->aplicarDescuento(10); 
$ebook->aplicarDescuento(20); 

$productos = [$libro, $ebook, $camiseta, $pack];

foreach ($productos as $p) {
    echo $p->getTipo() . ": " . $p->getNombre() . "<br>";
    echo "Precio base: " . $p->formatearPrecio($p->getPrecioBase()) . " €<br>";
    echo "Descuento: " . $p->getDescuento() . " %<br>";
    echo "Precio final con IVA: " . $p->getPrecioFinalFormateado() . " €<br>";

    if ($p instanceof Enviable) {
        echo "Es ENVIABLE → peso: " . $p->getPesoKg() . " kg, envio: " . $p->formatearPrecio($p->calcularGastosEnvio()) . " €<br>";
    }

    echo "<br>";
}