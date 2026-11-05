<?php
require_once __DIR__ . '/../traits/FormateaPrecio.php';
abstract class Producto
{
    use FormateaPrecio;
    protected $codigo;
    protected $nombre;
    protected $precioBase;

    public function __construct(string $codigo, string $nombre, float $precioBase)
    {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->precioBase = $precioBase;
    }

    //Metodos abstractos
    abstract public function getTipo();
    abstract protected function getIva();

    //GETTERS CLASE PRODUCTO (PADRE)
    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getPrecioBase()
    {
        return $this->precioBase;
    }

    // OTROS GETTERS
    public function getDescuento()
    {
        return 0;
    }

    public function getPrecioFinal()
    {
        $precioConDescuento = $this->precioBase * (1 - ($this->getDescuento() / 100));
        $precioConIva = $precioConDescuento * (1 + $this->getIva());

        return round($precioConIva, 2);
    }

    public function getPrecioFinalFormateado()
    {
        return $this->formatearPrecio($this->getPrecioFinal());
    }
    
}