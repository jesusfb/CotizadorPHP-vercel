<?php

require_once 'app/config.php'; //Se incluye el archivo config.php

// $_SESSION["quote"]["items"] =
//     [
//         [
//             'id' => 1234,
//             'concept' => 'Playera negra',
//             'type' => 'producto',
//             'quantity' => 3,
//             'price' => 100.55,
//             'taxes' => (TAXES_RATE / 100) * (100.55 * 3),
//             'total' => (100.55 * 3) + ((TAXES_RATE / 100) * (100.55 * 3))

//         ],

//         [
//             'id' => 1235,
//             'concept' => 'Control XBOX',
//             'type' => 'producto',
//             'quantity' => 3,
//             'price' => 750.99,
//             'taxes' => (TAXES_RATE / 100) * (750.99 * 3),
//             'total' => (750.99 * 3) + ((TAXES_RATE / 100) * (750.99 * 3))

//         ],
//     ];


get_view('index'); //Se llama a la vista index.php