<?php
$db_1 = [
    "username"=> "username",
    "email"=> "email",
    "test_num"=> "1",
    "test_slug"=> "test_slug",
];

$db_2 = [
    "username"=> "Username",
    "email"=> "email",
    "test_num"=> "2",
    "test_slug"=> "test_slug",
];

// Encodage en JSON
$json_data = json_encode($db_1, JSON_PRETTY_PRINT);
$json_data0 = json_encode($db_2, JSON_PRETTY_PRINT);

// // Boucle pour afficher les données
// foreach ($db_1 as $key => $value) {
//     echo "$key: $value\n";
// }

// foreach ($db_2 as $key => $value) {
//     echo "$key: $value\n";
// }

// Définir les en-têtes HTTP pour indiquer que le contenu est JSON
header('Content-Type: application/json');

// Retourner les données JSON
echo $json_data;