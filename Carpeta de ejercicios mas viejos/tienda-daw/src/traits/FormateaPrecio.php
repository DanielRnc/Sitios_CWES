<?php
trait FormateaPrecio{
    public function formatearPrecio($importe){
        return number_format($importe, 2, ',', '.');
    }
    
}