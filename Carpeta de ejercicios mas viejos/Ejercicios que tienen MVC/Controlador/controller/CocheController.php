<?php

class CocheController
{
    public $coches;

    public function __construct()
    {
        $this->coches = [
            1 => new Coche("Volkswagen", "Polo", "negro", "Rebeca"),
            2 => new Coche("Toyota", "Corolla", "verde", "Marcos"),
            3 => new Coche("Skoda", "Octavia", "gris", "Mario"),
            4 => new Coche("Kia", "Niro", "azul", "Jairo"),
            //BLANCO
            5 => new Coche("Skoda", "Octavia", "blanco", "Mario"),
            6 => new Coche("Kia", "Niro", "blanco", "Jairo")
        ];
    }

    public function ver($id)
    {
        if (array_key_exists($id, $this->coches)) {
            // Si el elemento está en el array, lo muestro
            $row = $this->coches[$id];
            require("view/ver.php");
        } else {
            // Llamo al método por defecto del controlador
            $this->index();
        }
    }

    public function baja($id)
    {
        if (array_key_exists($id, $this->coches)) {
            unset($this->coches[$id]);
        }
            $this->index();
    }

    public function index()
    {
        // Asigno los coches a una variable que estará esperando la vista
        $rowset = $this->coches;
        
        // Le paso los datos a la vista
        require("view/index.php");
    }
}