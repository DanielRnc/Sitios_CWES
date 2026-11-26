<?php
require_once __DIR__ . '/../interfaces/Enviable.php';
require_once __DIR__ . '/../traits/Descontable.php';
require_once __DIR__ . '/Producto.php';

class Libro extends Producto implements Enviable
{
    
    use Descontable;

    public const IVA = 0.04;

    private $autor;
    private $pesoKg;

    public function __construct($codigo, $nombre, $precioBase, $autor, $pesoKg)
    {
        parent::__construct($codigo, $nombre, $precioBase);
        $this->autor = $autor;
        $this->pesoKg = $pesoKg;
    }

    public function getAutor()
    {
        return $this->autor;
    }

    public function getPesoKg()
    {
        return $this->pesoKg;
    }
    public function calcularGastosEnvio()
    {
        return 2.50 + (1.00 * $this->pesoKg);
    }

    public function getTipo()
    {
        return 'Libro';
    }
    protected function getIva()
    {
        return self::IVA;
    }

}
