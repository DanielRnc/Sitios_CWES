<?php
class Perro
{
    private $raza;
    private $color;
    private $peso;
    private $edad;
    private $sexo;

    public function __construct($raza, $color, $peso, $edad, $sexo)
    {
        $this->raza = $raza;
        $this->color = $color;
        $this->peso = $peso;
        $this->edad = $edad;
        $this->sexo = $sexo;
    }
        //GETTERS
    public function getRaza()
    {
        $this->raza;
    }

    public function getColor()
    {
        $this->color;
    }

    public function getPeso()
    {
        $this->peso;
    }

    public function getEdad()
    {
        $this->edad;
    }

    public function getSexo()
    {
        $this->sexo;
    }
        //SETTERS
    public function setRaza($raza)
    {
        $this->raza = $raza;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function setPeso($peso)
    {
        $this->peso = $peso;
    }

    public function setEdad($edad)
    {
        $this->edad = $edad;
    }

    public function setSexo($sexo)
    {
        $sex = strtolower($sexo);
    }
}   