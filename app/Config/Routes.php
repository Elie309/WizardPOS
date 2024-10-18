<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Auth
$routes->group('auth', function ($routes) {
    $routes->post('login', 'Auth\AuthController::login');
    $routes->post('logout', 'Auth\AuthController::logout');
});
