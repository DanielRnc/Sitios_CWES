<?php
require_once __DIR__ . '/../interfaces/Enviable.php';
require_once __DIR__ . '/Producto.php';

class Camiseta extends Producto implements Enviable
{

    public const IVA = 0.21;

    private $talla;
    private $pesoKg;

    public function __construct($codigo, $nombre, $precioBase, $talla, $pesoKg)
    {
        parent::__construct($codigo, $nombre, $precioBase);
        $this->talla = $talla;
        $this->pesoKg = $pesoKg;
    }

    public function getPesoKg()
    {
        return $this->pesoKg;
    }
    public function calcularGastosEnvio()
    {
        return 1.95;
    }

    public function getTipo()
    {
        return 'Camiseta';
    }
    protected function getIva()
    {
        return self::IVA;
    }

}