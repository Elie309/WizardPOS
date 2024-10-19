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
        $routes->get('unauthorized', 'Auth\AuthController::unauthorized');
    });

    //Employee
});
