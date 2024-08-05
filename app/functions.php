<?php

use Dompdf\Dompdf;

function get_view($view_name)
{
    $view = VIEWS .  $view_name . 'View.php'; //Se obtiene la vista
    if (!file_exists($view)) { //Si la vista no existe se muestra un mensaje de error
        die('La vista ' . $view . ' no existe');
    }

    //Existe la vista
    require_once $view; //Se incluye la vista
}

function get_quote()
{
    if (!isset($_SESSION['quote'])) { //Si no existe la variable de sesion quote
        return $_SESSION['quote'] =
            [
                'number' => rand(111111, 999999),
                'name' => '',
                'company' => '',
                'email' => '',
                'items' => [],
                'subtotal' => 0,
                'taxes' => 0,
                'shipping' => 0,
                'total' => 0
            ];  //Se crea la variable de sesion quote
    }

    recalculate_quote(); //Se recalcula la cotizacion

    return $_SESSION['quote']; //Se retorna la cotizacion
}

function set_client($client)
{
    $_SESSION['quote']['name'] = trim($client['nombre']); //Se actualiza el nombre del cliente
    $_SESSION['quote']['company'] = trim($client['empresa']); //Se actualiza la empresa del cliente
    $_SESSION['quote']['email'] = trim($client['email']); //Se actualiza el email del cliente
    return true;
}

function recalculate_quote()
{
    $items = [];
    $subtotal = 0;
    $taxes = 0;
    $shipping = 0;
    $total = 0;

    if (!isset($_SESSION['quote'])) { //Si no existe la variable de sesion quote
        return false;
    }

    $items = $_SESSION['quote']['items']; //Se obtienen los items de la cotizacion

    if (!empty($items)) { //Si hay items en la cotizacion
        foreach ($items as $item) { //Se recorren los items
            $subtotal += $item['total']; //Se suma el total de los items al subtotal
            $taxes += $item['taxes']; //Se suma los impuestos al total de impuestos
        }
    }

    $shipping = $_SESSION['quote']['shipping']; //Se obtiene el costo de envio
    $total = $subtotal + $taxes + $shipping; //Se suma el subtotal, los impuestos y el costo de envio

    $_SESSION['quote']['subtotal'] = $subtotal; //Se actualiza el subtotal
    $_SESSION['quote']['taxes'] = $taxes; //Se actualizan los impuestos
    $_SESSION['quote']['shipping'] = $shipping; //Se actualiza el costo de envio
    $_SESSION['quote']['total'] = $total; //Se actualiza el total
    return true;
}

function restart()
{
    $_SESSION['quote'] =
        [
            'number' => rand(111111, 999999),
            'name' => '',
            'company' => '',
            'email' => '',
            'items' => [],
            'subtotal' => 0,
            'taxes' => 0,
            'shipping' => 0,
            'total' => 0
        ];

    return true;
}


function get_items()
{
    $items = [];

    if (!isset($_SESSION['quote']['items'])) { //Si no existe la variable de sesion items
        return $items;
    }

    $items = $_SESSION['quote']['items']; //Se obtienen los items de la cotizacion
    return $items; //Se retornan los items
}

function get_item($id)
{
    $items = get_items(); //Se obtienen los items de la cotizacion

    if (empty($items)) { //Si no existe el item con el id pasado por parametro
        return false;
    }

    foreach ($items as $item) { //Se recorren los items
        if ($item["id"] === $id) { //Si el id del item es igual al id pasado por parametro
            return $item; //Se retorna el item
        }
    }

    return false; //Si no se encuentra el item se retorna false
}

function delete_items()
{
    $_SESSION['quote']['items'] = []; //Se eliminan los items de la cotizacion
    recalculate_quote(); //Se recalcula la cotizacion
    return true;
}

function delete_item($id)
{
    $items = get_items(); //Se obtienen los items de la cotizacion

    if (empty($items)) { //Si no hay items en la cotizacion
        return false;
    }

    foreach ($items as $index => $item) { // Se recorren los items donde index es el indice del item
        if ($item["id"] === $id) { //Si el index del item es igual al id pasado por parametro
            unset($_SESSION['quote']['items'][$index]); //Se elimina el item de la cotizacion
            return true; //Se retorna el item
        }
    }

    return false; //Si no se encuentra el item se retorna false
}

function add_item($item)
{
    $items = get_items(); //Se obtienen los items de la cotizacion
    if (get_item($item['id']) !== false) { //Si el item ya existe en la cotizacion 
        foreach ($items as $index => $e_item) {
            if ($item['id'] === $e_item['id']) {
                $_SESSION['quote']['items'][$index] = $item;
                return true;
            }
        }
        return false;
    }

    $_SESSION['quote']['items'][] = $item; //Se actualizan los items de la cotizacion
    return true;
}

function json_build($status = 200, $data = null, $message = '')
{
    if (empty($message) || $message === '') {
        switch ($status) {
            case 200:
                $message = 'OK';
                break;
            case 201:
                $message = 'Created';
                break;
            case 400:
                $message = 'Invalid Request';
                break;
            case 401:
                $message = 'Unauthorized';
                break;
            case 403:
                $message = 'Forbidden - Access Denied';
                break;
            case 404:
                $message = 'Not Found';
                break;
            case 500:
                $message = 'Internal Server Error';
                break;
            case 550:
                $message = 'Permission Denied';
                break;
            default:
                $message = 'OK';
                break;
        }
    }

    $json = [
        'status' => $status,
        'message' => $message,
        'data' => $data
    ];

    return json_encode($json); //json_encode convierte un array en un string en formato json
}

function json_output($json)
{
    header('Access Control -Allow-Origin: *'); //Permite el acceso a la API desde cualquier origen
    header('Content-Type: application/json;charset=utf-8'); //Se establece el tipo de contenido como json

    if (is_array($json)) {
        $json = json_encode($json);
    }

    echo $json; //Se imprime el json
    exit(); //Se finaliza la ejecucion del script
}

function get_module($view, $data = [])
{
    $view = $view . '.php'; //Se obtiene la vista
    if (!is_file($view)) { //Si no existe el archivo de la vista
        return false;
    }

    $d = $data = json_decode(json_encode($data)); //Se convierte el array de datos en un objeto
    ob_start(); //Se inicia el buffer de salida
    require_once $view; //Se incluye la vista
    $output = ob_get_clean(); //Captura la salida del buffer y la almacena en la variable output
    return $output; //Se retorna la salida
}

function hook_get_quote_res()
{
    $quote = get_quote();
    $html = get_module(MODULES . 'quote_table', $quote); // Se obtiene el modulo quote_table y se le pasa la cotizacion
    return json_output(json_build(200, ['quote' => $quote, 'html' => $html])); //Se retorna la cotizacion y el html
}

function hook_add_to_quote()
{
    if (!isset($_POST['concepto'], $_POST['tipo'], $_POST['precio_unitario'], $_POST['cantidad'])) {
        return json_output(json_build(400, null, 'Parametros incompletos.')); //Si no se envian los datos necesarios se retorna un error
    }

    $concept = trim($_POST['concepto']); //Se obtiene el concepto
    $type = trim($_POST['tipo']); //Se obtiene el tipo
    $quantity = (int) trim($_POST['cantidad']); //Se obtiene la cantidad
    $price = (float) str_replace([',', '$'], '', $_POST['precio_unitario']); //Se obtiene el precio unitario
    $subtotal = (float) $price * $quantity;
    $taxes = (float) $subtotal * (TAXES_RATE / 100);

    $item = [
        'id' => rand(111111, 999999),
        'concept' => $concept,
        'type' => $type,
        'quantity' => $quantity,
        'price' => $price,
        'taxes' => $taxes,
        'total' => $subtotal
    ];

    if (!add_item($item)) { //Si no se agrega el item a la cotizacion
        json_output(json_build(400, null, 'Error al agregar el item a la cotizacion.')); //Se retorna un error
    }

    json_output(json_build(201, get_item($item["id"]), 'Concepto agregado con éxito.')); //Se retorna un mensaje de exito

}

function hook_restart_quote()
{
    $items = get_items(); //Se obtienen los items de la cotizacion
    if (empty($items)) { //Si no hay items en la cotizacion
        return json_output(json_build(400, null, 'La cotizacion esta vacía.')); //Se retorna un error
    }
    if (!restart()) { //Si no se reinicia la cotizacion
        return json_output(json_build(400, null, 'Error al reiniciar la cotizacion.')); //Se retorna un error
    }

    return json_output(json_build(200, get_quote(), 'Cotizacion reiniciada con éxito.')); //Se retorna un mensaje de exito
}


function hook_delete_concept()
{
    if (!isset($_POST['id'])) {
        return json_output(json_build(403, null, 'Parametros incompletos.')); //Si no se envian los datos necesarios se retorna un error
    }

    if (!delete_item((int) $_POST['id'])) { //Si no se elimina el item
        return json_output(json_build(400, null, 'Error al eliminar el concepto de la cotizacion.')); //Se retorna un error
    }

    return json_output(json_build(200, get_quote(), 'Concepto eliminado con éxito.')); //Se retorna un mensaje de exito
}

function hook_edit_concept()
{
    if (!isset($_POST['id'])) {
        return json_output(json_build(403, null, 'Parametros incompletos.')); //Si no se envian los datos necesarios se retorna un error
    }

    $item = get_item((int) $_POST['id']); //Se obtiene el item

    if (!$item) { //Si no se encuentra el item
        return json_output(json_build(400, null, 'Concepto no encontrado.')); //Se retorna un error
    }

    return json_output(json_build(200, $item, 'Concepto cargado con éxito.')); //Se retorna el item
}

function hook_save_concept()
{
    if (!isset($_POST['id_concepto'], $_POST['concepto'], $_POST['tipo'], $_POST['precio_unitario'], $_POST['cantidad'])) {
        return json_output(json_build(403, null, 'Parametros incompletos.')); //Si no se envian los datos necesarios se retorna un error
    }

    $id = (int) $_POST['id_concepto']; //Se obtiene el id
    $concept = trim($_POST['concepto']); //Se obtiene el concepto
    $type = trim($_POST['tipo']); //Se obtiene el tipo
    $price = (float) str_replace([',', '$'], '', $_POST['precio_unitario']); //Se obtiene el precio unitario
    $quantity = (int) trim($_POST['cantidad']); //Se obtiene la cantidad
    $subtotal = (float) $price * $quantity;
    $taxes = (float) $subtotal * (TAXES_RATE / 100);

    $item = [
        'id' => $id,
        'concept' => $concept,
        'type' => $type,
        'quantity' => $quantity,
        'price' => $price,
        'taxes' => $taxes,
        'total' => $subtotal
    ];

    if (!add_item($item)) { //Si no se agrega el item a la cotizacion
        return json_output(json_build(400, null, 'Error al guardar el concepto en la cotizacion.')); //Se retorna un error
    }

    return json_output(json_build(200, get_item($id), 'Concepto actualizado con éxito.')); //Se retorna un mensaje de exito
}


function generate_pdf($filename = null, $html, $save_to_file = true)
{
    $filename = $filename === null ? time() . '.pdf' : $filename . '.pdf'; //Se obtiene el nombre del archivo
    $pdf = new Dompdf(); //Se crea una instancia de Dompdf
    $pdf->getOptions()->setChroot(getcwd() . '/assets'); //Se establece la ruta de la carpeta raiz
    $pdf->setPaper('A4', 'portrait'); //Se establece el tamaño de la hoja
    $pdf->loadHtml($html);
    $pdf->render(); //Se renderiza el pdf

    if ($save_to_file) {
        $output = $pdf->output(); //Se obtiene el pdf
        file_put_contents($filename, $output); //Se guarda el pdf
        return true;
    }

    $pdf->stream($filename); //Se muestra el pdf
    return true;
}

function hook_generate_quote()
{
    if (!isset($_POST['nombre'], $_POST['empresa'], $_POST['email'])) {
        return json_output(json_build(400, null, 'Parametros incompletos.')); //Si no se envian los datos necesarios se retorna un error
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        return json_output(json_build(400, null, 'Email invalido.')); //Si el email no es valido se retorna un error
    }

    $client = [
        'nombre' => trim($_POST['nombre']),
        'empresa' => trim($_POST['empresa']),
        'email' => trim($_POST['email'])
    ];

    set_client($client); //Se actualiza el cliente

    $quote = get_quote(); //Se obtiene la cotizacion

    if (empty($quote['items'])) { //Si no hay items en la cotizacion
        return json_output(json_build(400, null, 'La cotizacion esta vacía.')); //Se retorna un error
    }

    $module = MODULES . 'pdf_template'; //Se obtiene el modulo pdf_template
    $html = get_module($module, $quote); //Se obtiene el modulo pdf_template y se le pasa la cotizacion
    $filename = 'coty_' . $quote['number']; //Se establece el nombre del archivo
    $download = WEB_URL . UPLOADS . $filename . '.pdf'; //Se establece la ruta de descarga
    $quote['url'] = $download; //Se actualiza la cotizacion con la ruta de descarga

    if (!generate_pdf(UPLOADS . $filename, $html)) { //Si no se genera el pdf
        return json_output(json_build(400, null, 'Error al generar el PDF.')); //Se retorna un error
    }

    return json_output(json_build(200, $quote, 'PDF generado con éxito.')); //Se retorna un mensaje de exito
}
