<?php

class Producto
{
    protected $codigo;
    protected $nombre;
    protected $precioBase;

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
        //HACER LOS CALCULOS DEL DESCUENTO
    }

    public function getPrecioFinal()
    {
        //HACER LOS CALCULOS DEL PRECIO FINAL
    }

    public function getPrecioFinalFormateado()
    {
        //PONE QUE TENGO QUE USAR (FORMATEAPRECIO)
    }
    
}