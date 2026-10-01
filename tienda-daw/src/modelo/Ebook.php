<?php
require_once __DIR__ . '/../interfaces/Descargable.php';
require_once __DIR__ . '/../traits/Descontable.php';
require_once __DIR__ . '/Producto.php';
class Ebook extends Producto implements Descargable
{
    use Descontable;

    public const IVA = 0.04;

    private $formato;
    private $tamanoMb;

    public function __construct($codigo, $nombre, $precioBase, $tamanoMb, $formato)
    {
        parent::__construct($codigo, $nombre, $precioBase);
        $this->formato = $formato;
        $this->tamanoMb = $tamanoMb;
    }

    public function getFormato(): string
    {
        return $this->formato;
    }

    public function getUrlDescarga()
    {

    }
    public function getTamanoMb()
    {
        return $this->tamanoMb;
    }

    public function getTipo(): string
    {
        return 'Ebook';
    }

    protected function getIva(): float
    {
        return self::IVA;
    }

}