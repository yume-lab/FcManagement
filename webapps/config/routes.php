<?php
/**
 * ルーティング設定
 */

use Cake\Core\Plugin;
use Cake\Routing\Router;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass('DashedRoute');

Router::scope('/', function ($routes) {
    $routes->connect('/', ['controller' => 'Index', 'action' => 'index']);

    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    $routes->connect('/login', ['controller' => 'Login', 'action' => 'index']);
    $routes->connect('/login/auth', ['controller' => 'Login', 'action' => 'auth']);

    $routes->connect('/dashboard', ['controller' => 'Dashboard', 'action' => 'index']);

    $routes->fallbacks('DashedRoute');
});

Plugin::routes();
