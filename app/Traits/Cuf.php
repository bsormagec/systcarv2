<?php
namespace App\Traits;
//declare(strict_types = 1);

/**
 * Codigo Unico de Factura
 *
 * @author mouse
 */
trait CUF {

    /**
     * @param nit NIT emisor
     * @param fh Fecha y Hora en formato yyyyMMddHHmmssSSS
     * @param sucursal
     * @param mod Modalidad
     * @param temision Tipo de Emision
     * @param cdf Codigo Documento Fiscal
     * @param tds Tipo Documento Sector
     * @param nf Numero de Factura
     * @param pos Punto de Venta
     * @return CUF Codigo Unico de Factura
     */
    public function obtenerCUF(string $nit, string $fh, string $sucursal, string $mod, string $temision, string $cdf, string $tds, string $nf, string $pos) {
        $cadena = "";

        /**
         * PASO 1 y PASO2 Completa con ceros cada campo y concatena todo en una
         * sola cadena
         */
        $cadena .= str_pad($nit, 13, '0', STR_PAD_LEFT);
        $cadena .= $fh;
        $cadena .= str_pad($sucursal, 4, '0', STR_PAD_LEFT);
        $cadena .= $mod;
        $cadena .= $temision;
        $cadena .= $cdf;
        $cadena .= str_pad($tds, 2, '0', STR_PAD_LEFT);
        $cadena .= str_pad($nf, 8, '0', STR_PAD_LEFT);
        $cadena .= str_pad($pos, 4, '0', STR_PAD_LEFT);

        /**
         * Paso 3 Obtiene modulo 11 y adjunta resultado a la cadena
         */        
        $cadena .= $this->calculaDigitoMod11($cadena, 1, 9, false);
        
        /**
         * paso 4 Aplica base16
         */
        return $this->base16($cadena);
    }

    /**
     * @see https://impuestos.gob.bo/ ALGORITMO BASE 11 – MÓDULO 11
     * Original codigo java
     */
    public function calculaDigitoMod11(string $dado, int $numDig, int $limMult, bool $x10): string {
        if (!$x10) {
            $numDig = 1;
        }
        for ($n = 1; $n <= $numDig; $n++) {
            $suma = 0;
            $mult = 2;
            for ($i = strlen($dado) - 1; $i >= 0; $i--) {
                $suma += ($mult * substr($dado, $i, 1));
                if (++$mult > $limMult) {
                    $mult = 2;
                }
            }
            if ($x10) {
                $dig = (($suma * 10) % 11) % 10;
            } else {
                $dig = $suma % 11;
            }

            if ($dig == 10) {
                $dado .= "1";
            }
            if ($dig == 11) {
                $dado .= "0";
            }
            if ($dig < 10) {
                $dado .= $dig;
            }
        }
        return substr($dado, strlen($dado) - $numDig, $numDig);
    }

    /**
     * @param string $number cadena 
     * @param bool $touppercase TRUE: resultado en mayusculas
     *                          FALSE: Resultado en minusculas
     * @return string Numero hexadecimal
     */
    public function base16(string $number, bool $touppercase = true): string {
        $hexvalues = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $hexval = '';
        while ($number != '0') {
            $hexval = $hexvalues[bcmod($number, '16')] . $hexval;            
            $number = bcdiv($number, '16', 0);
        }
        return ($touppercase) ? strtoupper($hexval) : $hexval;
    }

}