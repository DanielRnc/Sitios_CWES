<?php

class Coche
{
    // Variables o atributos
    public $marca;
    public $modelo;
    public $color;
    public $propietario;

    public function __construct($miMarca, $miModelo, $miColor, $miPropietario)
    {
        $this->marca = $miMarca;
        $this->modelo = $miModelo;
        $this->color = $miColor;
        $this->propietario = $miPropietario;
    }

    // Funciones o métodos
    public function setMarca($miMarca)
    {
        $this->marca = $miMarca;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function setModelo($miModelo)
    {
        $this->modelo = $miModelo;
    }

    public function getModelo()
    {
        return $this->modelo;
    }

    public function setColor($miColor)
    {
        $this->color = $miColor;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setPropietario($miPropietario)
    {
        $this->propietario = $miPropietario;
    }

    public function getPropietario()
    {
        return $this->propietario;
    }
}