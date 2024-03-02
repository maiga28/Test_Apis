<?php

declare(strict_types=1);
// Inclusion de l'autoloader de Composer
require_once __DIR__ . '/vendor/autoload.php';

// Utilisation des classes nécessaires
use Oscar\TestApi\Databases\Database;
use Oscar\TestApi\ErrorHandler\ErrorHandler;
use Oscar\TestApi\Main_Apis\ProductGateway\ProductGateway;
use Oscar\TestApi\Controllers\ProductsControllers\ProductController;

// En-tête JSON
header("Content-type: application/json; charset=UTF-8");
// Récupération de l'URI
$requestUri = $_SERVER["REQUEST_URI"] ?? '';
// Suppression du chemin de base de l'URI s'il est présent
$basePath = '/test_api';
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}
// Découpage de l'URI en parties
$parts = explode("/", $requestUri);
// Vérification de la route
$route = $parts[1] ?? 'product';
// Gestion des erreurs

if ($route !== 'product') 

{
    http_response_code(404);
    echo json_encode(["message" => "Route not found"]);
    return;
}

// Récupération de l'ID du produit (s'il existe)
$id = $parts[2] ?? null;
// Initialisation de la base de données
$database = new Database("localhost", "product_db", "root", "<@>maiga<@>");
// Initialisation de la passerelle des produits
$gateway = new ProductGateway($database);
// Initialisation du contrôleur des produits
$controller = new ProductController($gateway);
// Traitement de la requête
$method = $_SERVER["REQUEST_METHOD"];

// Si $id est présent dans l'URI
if ($id !== null) {
    // Traitement de la ressource
    $controller->processRequest($method, $id);
} else {
    // Traitement de la collection
    $controller->processRequest($method, null);
}



