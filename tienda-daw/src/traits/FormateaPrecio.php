<?php
trait formatearPrecio($importe){
    return number_format($importe, 2, ',', '.')
}