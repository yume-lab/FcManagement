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
Router::extensions(['json', 'xml']);
Router::scope('/', function ($routes) {
    $routes->connect('/', ['controller' => 'Index', 'action' => 'index']);

    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    // 認証系
    $routes->connect('/login', ['controller' => 'Users', 'action' => 'login']);
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);

    // パート管理
    $routes->connect('/employee', ['controller' => 'Employees', 'action' => 'index']);

    // シフト作成
    $routes->connect('/shift', ['controller' => 'ShiftTables', 'action' => 'index']);
    $routes->connect('/shift/fixed', ['controller' => 'ShiftTables', 'action' => 'fixed']);
    $routes->connect('/api/shift', ['controller' => 'ShiftTables', 'action' => 'get']);
    $routes->connect('/api/shift/update', ['controller' => 'ShiftTables', 'action' => 'update']);

    // シフト表閲覧
    $routes->connect('/fixed', ['controller' => 'FixedShiftTables', 'action' => 'index']);
    $routes->connect('/fixed/view/*', ['controller' => 'FixedShiftTables', 'action' => 'view']);

    $routes->connect('/dashboard', ['controller' => 'Dashboard', 'action' => 'index']);

    $routes->fallbacks('DashedRoute');
});

Plugin::routes();
