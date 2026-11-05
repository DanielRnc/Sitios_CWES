<?php
require_once __DIR__ . '/../interfaces/Enviable.php';
require_once __DIR__ . '/../interfaces/Descargable.php';
require_once __DIR__ . '/../traits/Descontable.php';
require_once __DIR__ . '/Producto.php';

class PackLibro extends Producto implements Descargable, Enviable
{
    use Descontable;

    public const IVA = 0.04;
    
    private $pesoKg;
    private $tamanoMb;

    public function __construct($codigo, $nombre, $precioBase, $tamanoMb, $pesoKg)
    {
        parent::__construct($codigo, $nombre, $precioBase);
        $this->pesoKg = $pesoKg;
        $this->tamanoMb = $tamanoMb;
    }

    public function getPesoKg()
    {
        return $this->pesoKg;
    }

    public function calcularGastosEnvio()
    {
        return 2.50 + (1.00 * $this->pesoKg);
    }

    public function getTamanoMb()
    {
        return $this->tamanoMb;
    }

    public function getUrlDescarga()
    {
        
    }

    public function getTipo()
    {
        return 'PackLibro';
    }
    protected function getIva()
    {
        return self::IVA;
    }
}