<?php

declare(strict_types=1);

// Inclure le fichier autoload.php de Composer
require_once __DIR__ . '/../vendor/autoload.php';
// En-têtes
define('ACCESS_CONTROL_ALLOW_ORIGIN', 'Access-Control-Allow-Origin');
define('CONTENT_TYPE', 'Content-Type');
define('ACCESS_CONTROL_ALLOW_METHOD', 'Access-Control-Allow-Method');
define('ACCESS_CONTROL_ALLOW_HEADERS', 'Access-Control-Allow-Headers');
define('BASE_PATH', '/test_api/Public');
define('NOT_FOUND_MESSAGE', 'Route not found');

use Oscar\TestApi\Databases\Database;
use Oscar\TestApi\ErrorHandler\ErrorHandler;
use Oscar\TestApi\Main_Apis\ProductGateway\ProductGateway;
use Oscar\TestApi\Controllers\ProductsControllers\ProductController;

// Configuration des en-têtes
header(ACCESS_CONTROL_ALLOW_ORIGIN . ': *');
header(CONTENT_TYPE . ': application/json');
header(ACCESS_CONTROL_ALLOW_METHOD . ': GET');
header(ACCESS_CONTROL_ALLOW_HEADERS . ': Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

/**
 * Fonction pour valider et traiter l'URI de la requête.
 *
 * @param string $requestUri L'URI de la requête.
 * @return array Tableau contenant la route et l'ID extraits de l'URI.
 */
function processRequestUri(string $requestUri): array {
    // Suppression du chemin de base de l'URI s'il est présent
    $basePath = '/test_api';
    if (strpos($requestUri, $basePath) === 0) {
        $requestUri = substr($requestUri, strlen($basePath));
    }
    // Découpage de l'URI en parties
    $parts = explode("/", $requestUri);
    // Récupération de la route et de l'ID
    $route = $parts[1] ?? 'product';
    $id = $parts[2] ?? null;
    return ['route' => $route, 'id' => $id];
}

// Récupération de l'URI de la requête
$requestUri = $_SERVER["REQUEST_URI"] ?? '';

// Traitement et validation de l'URI de la requête
$requestData = processRequestUri($requestUri);
$route = $requestData['route'];

// Vérification de la route
if ($route !== 'product') {
    // Gestion de la route incorrecte
    http_response_code(404);
    echo json_encode(["message" => NOT_FOUND_MESSAGE]);
    return;
}

// Initialisation de la base de données
$database = new Database("localhost", "product_db", "root", "<@>maiga<@>");
// Initialisation de la passerelle des produits
$gateway = new ProductGateway($database);
// Initialisation du contrôleur des produits
$controller = new ProductController($gateway);

// Récupération de la méthode HTTP de la requête
$method = $_SERVER["REQUEST_METHOD"] ?? '';

// Vérification si la méthode est vide
if (empty($method)) {
    http_response_code(405);
    echo json_encode(["error" => "Request method not provided"]);
    return;
}

// Récupération de l'ID de l'URI
$id = $requestData['id'];

// Si $id est présent dans l'URI
if ($id !== null) {
    // Traitement de la ressource
    $controller->processRequest($method, $id);
} else {
    // Traitement de la collection
    $controller->processRequest($method, null);
}
