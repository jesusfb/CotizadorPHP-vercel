<?php

session_start(); //Inicia la sesion
//Esta linea permite que la aplicacion sepa si esta en local o en produccion por medio de la ip
define('IS_LOCAL', in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']));

$web_url = IS_LOCAL ? 'http://localhost:3000/xampp/htdocs/Cotizador_PHP/' : 'La url de su servidor esta en producción'; //Ruta de la aplicacion
define('WEB_URL', $web_url);

//Rutas para las carpetas
define('DS', DIRECTORY_SEPARATOR); //Separador de directorios
define('ROOT', getcwd() . DS); //Obtiene la ruta del directorio actual
define('APP', ROOT . 'app' . DS); //Ruta de la carpeta app
define('ASSETS', ROOT . 'assets' . DS); //Ruta de la carpeta classes
define('TEMPLATES', ROOT . 'templates' . DS); //Ruta de la carpeta templates
define('INCLUDES', TEMPLATES . 'includes' . DS); //Ruta de la carpeta includes
define('MODULES', TEMPLATES . 'modules' . DS); //Ruta de la carpeta modules
define('VIEWS',  TEMPLATES . 'views' . DS); //Ruta de la carpeta views
define('UPLOADS', 'assets/uploads/'); //Ruta de la carpeta uploads

//Para archivos que vayamos a incluir en header o footer (css o js)
define('CSS', WEB_URL . 'assets/css/'); //Ruta de la carpeta css
define('IMG', WEB_URL . 'assets/img/'); //Ruta de la carpeta img
define('JS', WEB_URL . 'assets/js/'); //Ruta de la carpeta js

//Personalización
define('APP_NAME', 'Cotizador App'); //Nombre de la aplicacion
define('TAXES_RATE', 19); //Tasa de impuestos
define('SHIPPING', 99.50); //Costo de envio

//Autoload Composer
require_once ROOT . 'vendor/autoload.php'; //Se incluye el autoload de composer

//Cargar funciones
require_once APP . 'functions.php'; //Se incluye el archivo functions.php