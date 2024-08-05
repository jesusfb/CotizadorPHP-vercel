<?php

require_once 'app/config.php'; //Se incluye el archivo config.php

try {
    if (!isset($_POST['action']) && !isset($_GET['action'])) { //Si no existe la variable action en POST o GET
        throw new Exception('El acceso no esta autorizado'); //Se lanza una excepcion
    }

    //guardar el valor de action
    $action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];
    $action = str_replace('-', '_', $action); //Se reemplaza el guion por un guion bajo
    $function = sprintf('hook_%s', $action); //Se crea la funcion a ejecutar, donde sprintf es una funcion que permite formatear una cadena

    //Validar la existencia de la función
    if (!function_exists($function)) { //Si la funcion no existe
        throw new Exception('Acceso no autorizado');
    }


    $function(); //Se ejecuta la funcion
} catch (Exception $e) { //Si se lanza una excepcion
    json_output(json_build(403, null, $e->getMessage())); //Se muestra un mensaje de error
}
