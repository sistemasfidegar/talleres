<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function misql($date) {

    $date = str_replace('/', '-', $date);
    $lista = explode('-', $date);
    if (count($lista) == 3) {
        $string = $lista[2] . "/" . $lista[1] . "/" . $lista[0];
        return ($string);
    } else {
        return null;
    }
    /* else {
      $fecha = new DateTime();
      return $fecha->format('Y-m-d');
      } */
}

function sqlDateFormat($date) {
    if ($date != null) {
        $date = str_replace('/', '-', $date);
        list ($year, $month, $day) = explode('-', $date);
        $string = $day . "/" . $month . "/" . $year;
        return ($string);
    } else
        return $date;
}

function valid_date($date) {
    $lista = explode('-', $date);
    if (count($lista) == 3) {
        return checkdate($lista[1], $lista[0], $lista[2]);
    } else
        return false;
}

function valid_date2($date) {
    $lista = explode('-', $date);
    if (count($lista) == 3) {
        return checkdate($lista[1], $lista[2], $lista[0]);
    } else
        return false;
}

function date_to_array($date) {

    return explode('-', $date);
}

function fecha_actual() {
    $time = time();
    $datestring = "%d-%m-%Y";
   // $datestring = "%Y-%m-%d";
    return mdate($datestring, $time);
}

function fecha_con_letra($date) {
    if ($date != null) {
        $lista = explode('-', $date);
        $mes = mesLetra((int) $lista[1]);
        return $lista[0] . ' de ' . $mes . ' de ' . $lista[2];
    } else {
        return "Fecha vacia";
    }
}

function mesLetra($mes) {

    switch ($mes) {
        case 1:
            $mes = 'enero';
            break;
        case 2:
            $mes = 'febrero';
            break;
        case 3:
            $mes = 'marzo';
            break;
        case 4:
            $mes = 'abril';
            break;
        case 5:
            $mes = 'mayo';
            break;
        case 6:
            $mes = 'junio';
            break;
        case 7:
            $mes = 'julio';
            break;
        case 8:
            $mes = 'agosto';
            break;
        case 9:
            $mes = 'septiembre';
            break;
        case 10:
            $mes = 'octubre';
            break;
        case 11:
            $mes = 'noviembre';
            break;
        case 12:
            $mes = 'diciembre';
            break;
    }
    return $mes;
}

function formatTime($fecha, $format = 'd-m-Y H:i:s') {
    if ($fecha != null)
        return date($format, strtotime($fecha));
    else
        return $fecha;
}

?>