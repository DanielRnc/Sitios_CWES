<?php

trait Descontable{
    private $descuento;

    public function aplicarDescuento($descuento){
        if($descuento >= 0 && $descuento <= 50)
            return $descuento;
        else
            throw new InvalidArgumentException("El porcentaje debe estar entre 0 y 50.");
    }
    
    public function getDescuento(){
        return $this->descuento;
    }
}