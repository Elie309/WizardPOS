<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//Add the api routes

$routes->group('api', function ($routes) {

    $routes->get('/', 'MainController::index');

    //Auth
    $routes->group('auth', function ($routes) {
        $routes->post('login', 'Auth\AuthController::login');
        $routes->post('logout', 'Auth\AuthController::logout');
        $routes->get('getAuthenticatedUser', 'Auth\AuthController::getAuthenticatedUser');
        $routes->get('unauthorized', 'Auth\AuthController::unauthorized');
    });

    //Products
    $routes->group('products', function ($routes) {
        $routes->get('menu', 'Products\ProductsController::getMenuProducts');
        $routes->get('search', 'Products\ProductsController::search');
        $routes->get('sku/(:alphanum)', 'Products\ProductsController::getWithSKU/$1');
        $routes->get('/', 'Products\ProductsController::index');

        $routes->post('/', 'Products\ProductsController::create');
        $routes->post('(:alphanum)', 'Products\ProductsController::update/$1');
        $routes->delete('(:alphanum)', 'Products\ProductsController::delete/$1');


    });

    //Categories
    $routes->group('categories', function ($routes) {
        $routes->get('/', 'Products\CategoriesController::index');
        $routes->get('(:alphanum)', 'Products\CategoriesController::show/$1');

        //Need authorization
        $routes->post('/', 'Products\CategoriesController::create');
        $routes->post('(:alphanum)', 'Products\CategoriesController::update/$1');
        $routes->delete('(:alphanum)', 'Products\CategoriesController::delete/$1');
    });
});
