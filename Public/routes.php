<?php

use FastRoute\RouteCollector;

// Cette fonction est une closure qui configure les routes pour FastRoute
return function (RouteCollector $r) {
    // Définit la route pour afficher un produit spécifique
    $r->addRoute('GET', '/product/{id:\d+}', 'ProductController@show');
    
    // Définit la route pour afficher tous les produits
    $r->addRoute('GET', '/product', 'ProductController@index');
};
