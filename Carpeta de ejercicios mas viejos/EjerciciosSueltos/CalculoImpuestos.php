<?php

/**
 * Clase que contiene utilidades estáticas para el comercio electrónico.
 * No es necesario instanciar esta clase para usar sus funciones.
 */
class CalculadoraImpuestos
{
    // PROPIEDAD ESTÁTICA: La tasa de impuesto.
    // Pertenece a la clase, no a un producto específico.
    public static $TASA_IGV = 0.18;

    /**
     * MÉTODO ESTÁTICO: Calcula el precio total de un producto con impuestos.
     * Es una función de utilidad que no depende de propiedades de un objeto.
     */
    public static function calcularPrecioFinal($precioBase)
    {
        // Para acceder a la propiedad estática desde dentro de la clase, usamos 'self::'.
        $impuesto = $precioBase * self::$TASA_IGV;
        $precioFinal = $precioBase + $impuesto;

        return $precioFinal;
    }

    /**
     * MÉTODO ESTÁTICO: Una función de validación simple.
     * Los métodos estáticos pueden usarse para comprobar que un valor está bien definido.
     */
    public static function esPrecioValido($precio)
    {
        return is_numeric($precio) && $precio > 0;
    }
}