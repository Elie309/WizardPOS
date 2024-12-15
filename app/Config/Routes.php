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
        $routes->get('(:segment)', 'Products\CategoriesController::show/$1');

        //Need authorization
        $routes->post('/', 'Products\CategoriesController::create');
        $routes->post('(:segment)', 'Products\CategoriesController::update/$1');
        $routes->delete('(:segment)', 'Products\CategoriesController::delete/$1');
    });

    //Uploads image
    $routes->group('uploads', function ($routes) {
        $routes->post('image', 'Uploads\UploadsController::upload');
    });

    // Clients
    $routes->group('clients', function ($routes) {
        $routes->get('/', 'Clients\ClientController::index');
        $routes->get('(:num)', 'Clients\ClientController::show/$1');
        $routes->post('/', 'Clients\ClientController::create');
        $routes->post('(:num)', 'Clients\ClientController::update/$1');

        // Need authorization
        $routes->delete('(:num)', 'Clients\ClientController::delete/$1');
    });

    //Tables
    $routes->group('tables', function ($routes) {
        $routes->get('/', 'Tables\TableController::index');
        $routes->get('active', 'Tables\TableController::activeTables');
        $routes->get('(:num)', 'Tables\TableController::show/$1');

        $routes->post('/', 'Tables\TableController::create');
        $routes->post('(:num)', 'Tables\TableController::update/$1');


        // Need authorization
        $routes->delete('(:num)', 'Tables\TableController::delete/$1');
    });

    //Reservations
    $routes->group('reservations', function ($routes) {
        $routes->get('/', 'Reservations\ReservationController::index');
        $routes->get('(:num)', 'Reservations\ReservationController::show/$1');

        $routes->post('/', 'Reservations\ReservationController::create');
        $routes->post('(:num)', 'Reservations\ReservationController::update/$1');

        // Need authorization
        $routes->delete('(:num)', 'Reservations\ReservationController::delete/$1');

        //Get Statuses
        $routes->get('statuses', 'Reservations\ReservationController::statuses');

    });

    // Orders
    $routes->group('orders', function ($routes) {
        $routes->get('/', 'Orders\OrderController::index');
        $routes->get('(:num)', 'Orders\OrderController::show/$1');
            
        $routes->post('/', 'Orders\OrderController::create');
        $routes->post('(:num)', 'Orders\OrderController::update/$1');
        $routes->delete('(:num)', 'Orders\OrderController::delete/$1');



        $routes->get('(:num)/items', 'Orders\OrderItemController::list/$1');
        $routes->post('(:num)/items', 'Orders\OrderItemController::add/$1');
        $routes->delete('(:num)/items/(:num)', 'Orders\OrderItemController::delete/$1/$2');

        //bulk add
        $routes->post('(:num)/items/bulk', 'Orders\OrderItemController::bulkAdd/$1');
        //bulk delete
        $routes->delete('(:num)/items/bulk', 'Orders\OrderItemController::bulkDelete/$1');

    

        $routes->get('statuses', 'Orders\OrderController::statuses');
        $routes->get('types', 'Orders\OrderController::types');

    });

    //Reports
    $routes->group('reports', function ($routes) {
        $routes->get('/', 'Orders\ReportController::byDate');
    });


    //Employees
    $routes->group('employees', function ($routes) {
        $routes->get('/', 'Auth\AuthController::getAll');
        $routes->get('(:num)', 'Auth\AuthController::getById/$1');
        $routes->post('/', 'Auth\AuthController::register');
        $routes->post('(:num)', 'Auth\AuthController::update/$1');
        $routes->delete('(:num)', 'Auth\AuthController::delete/$1');
    });

});
