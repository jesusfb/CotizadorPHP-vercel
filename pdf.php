<?php
require_once 'app/config.php'; //Se incluye el archivo config.php

//Validar que existan las coizaciones
if (!isset($_GET['number'])) {
    redirect('index.php?error=invalid_number');
}

$quotes = get_all_quotes(); //Se obtienen todas las cotizaciones
if (empty($quotes)) { //Si no hay cotizaciones
    redirect('index.php?error=no_quotes');
}

$number = trim($_GET['number']); //Se obtiene el número de la cotización
$file = sprintf(UPLOADS . 'coty_%s.pdf', $number); //Se establece la ruta del archivo
if (!is_file($file)) {
    redirect('index.php?error=not_found'); //Si no se encuentra la cotización se redirige a index.php con un error
}
header('Content-Type: application/pdf'); //Se establece el tipo de contenido
header((sprintf('Content-Disposition: attachment; filename=%s', pathinfo($file, PATHINFO_BASENAME)))); //Se establece el nombre del archivo a descargar
readfile($file); //Se lee el archivo