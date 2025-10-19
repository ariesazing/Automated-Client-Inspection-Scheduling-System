<?php

/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * This file is loaded in the context of the `Application` class.
  * So you can use  `$this` to reference the application class instance
  * if required.
 */

return function (RouteBuilder $routes): void {
    /*
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
     * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
     * `{action}` markers.
     */
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        /*
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, templates/Pages/home.php)...
         */
        $builder->connect('/', ['controller' => 'Users', 'action' => 'index']);

        /*
         * ...and connect the rest of 'Pages' controller's URLs.
         */
        $builder->connect('/pages/*', 'Pages::display');

        /*
         * Connect catchall routes for all controllers.
         *
         * The `fallbacks` method is a shortcut for
         *
         * ```
         * $builder->connect('/{controller}', ['action' => 'index']);
         * $builder->connect('/{controller}/{action}/*', []);
         * ```
         *
         * You can remove these routes once you've connected the
         * routes you want in your application.
         */
        $builder->fallbacks();
    });
    $routes->prefix('Api', function (RouteBuilder $builder) {
        $builder->scope('/Users', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Users', 'action' => 'index']);
            $builder->connect('/getUsers', ['controller' => 'Users', 'action' => 'getUsers']);
            $builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
            $builder->connect('/add', ['controller' => 'Users', 'action' => 'add']);
            $builder->connect('/edit/{id}', ['controller' => 'Users', 'action' => 'edit'], [
                'pass' => ['id']
            ]);
            $builder->connect('/delete/{id}', ['controller' => 'Users', 'action' => 'delete'], [
                'pass' => ['id']
            ]);
        });

        $builder->scope('/Availabilities', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Availabilities', 'action' => 'index']);
            $builder->connect('/index/{inspector_id}', [
                'controller' => 'Availabilities',
                'action' => 'index'
            ], [
                'pass' => ['inspector_id']
            ]);
            $builder->connect('/getAvailabilities', ['controller' => 'Availabilities', 'action' => 'getAvailabilities']);
            $builder->connect('/getInspectorAvailabilities/{inspector_id}', [
                'controller' => 'Availabilities',
                'action' => 'getInspectorAvailabilities'
            ], [
                'pass' => ['inspector_id']
            ]);
            $builder->connect('/add', ['controller' => 'Availabilities', 'action' => 'add']);
            $builder->connect('/editAvailabilities/{id}', [
                'controller' => 'Availabilities',
                'action' => 'editAvailabilities'
            ], [
                'pass' => ['id']
            ]);
            $builder->connect('/delete/{id}', [
                'controller' => 'Availabilities',
                'action' => 'delete'
            ], [
                'pass' => ['id']
            ]);
            $builder->connect('/toggle', ['controller' => 'Availabilities', 'action' => 'toggle']);
        });

        $builder->scope('/Inspectors', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Inspectors', 'action' => 'index']);
            $builder->connect('/getInspectors', ['controller' => 'Inspectors', 'action' => 'getInspectors']);
            $builder->connect('/add', ['controller' => 'Inspectors', 'action' => 'add']);
            $builder->connect('/edit/{id}', ['controller' => 'Inspectors', 'action' => 'edit'], [
                'pass' => ['id']
            ]);
            $builder->connect('/delete/{id}', ['controller' => 'Inspectors', 'action' => 'delete'], [
                'pass' => ['id']
            ]);
        });
        $builder->scope('/Inspections', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Inspections', 'action' => 'index']);
            $builder->connect('/getInspections', ['controller' => 'Inspections', 'action' => 'getInspections']);
            $builder->connect('/add', ['controller' => 'Inspections', 'action' => 'add']);
            $builder->connect('/edit/{id}', ['controller' => 'Inspections', 'action' => 'edit'], [
                'pass' => ['id']
            ]);
            $builder->connect('/delete/{id}', ['controller' => 'Inspections', 'action' => 'delete'], [
                'pass' => ['id']
            ]);
        });
        $builder->connect('/SchedulingLogs/getSchedulingLogs', [
            'controller' => 'SchedulingLogs',
            'action' => 'getSchedulingLogs'
        ]);

        $builder->scope('/Clients', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Clients', 'action' => 'index']);
            $builder->connect('/getClients', ['controller' => 'Clients', 'action' => 'getClients']);
            $builder->connect('/add', ['controller' => 'Clients', 'action' => 'add']);
            $builder->connect('/edit/{id}', ['controller' => 'Clients', 'action' => 'edit'], [
                'pass' => ['id']
            ]);
            $builder->connect('/delete/{id}', ['controller' => 'Clients', 'action' => 'delete'], [
                'pass' => ['id']
            ]);
        });
    });
    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder): void {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
};
