<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('customers/new', 'CustomerAccounts::new');
$routes->post('customers', 'CustomerAccounts::create');
$routes->get('customers/(:num)/edit', 'CustomerAccounts::edit/$1');
$routes->post('customers/(:num)', 'CustomerAccounts::update/$1');

$routes->get('users/new', 'UserAccounts::new');
$routes->post('users', 'UserAccounts::create');
$routes->get('users/(:num)/edit', 'UserAccounts::edit/$1');
$routes->post('users/(:num)', 'UserAccounts::update/$1');