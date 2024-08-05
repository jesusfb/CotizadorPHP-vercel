<?php
require_once 'app/config.php'; //Se incluye el archivo config.php
generate_pdf("cotización_" . time(), get_module(MODULES . 'pdf_template')); //Se llama a la funcion generate_pdf));