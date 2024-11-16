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
        $builder->connect('/', ['controller' => 'Users', 'action' => 'login']);

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
    $routes->scope('/api', function (RouteBuilder $builder): void {
        $builder->setExtensions(['json']);
        $builder->post(
            '/users/generateotp',
            ['controller' => 'Users', 'action' => 'generateotp', 'prefix' => 'Api']
        );
        $builder->post(
            '/users/login',
            ['controller' => 'Users', 'action' => 'login', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/overviewdetails/',
            ['controller' => 'Users', 'action' => 'overviewdetails', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/alltoporder/',
            ['controller' => 'Users', 'action' => 'alltoporder', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/allrecentorder/',
            ['controller' => 'Users', 'action' => 'allrecentorder', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/allrecentorder/',
            ['controller' => 'Users', 'action' => 'allrecentorder', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/orderhistory/',
            ['controller' => 'Users', 'action' => 'orderhistory', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/allproducts/',
            ['controller' => 'Users', 'action' => 'allproducts', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/ordersummery/{id}',
            ['controller' => 'Users', 'action' => 'ordersummery', 'prefix' => 'Api']
        )->setPass(['id']);
        $builder->post(
            '/users/repeatorder',
            ['controller' => 'Users', 'action' => 'repeatorder', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/index',
            ['controller' => 'Users', 'action' => 'index', 'prefix' => 'Api']
        );

        $builder->get(
            '/users/test',
            ['controller' => 'Users', 'action' => 'test', 'prefix' => 'Api']
        );

        $builder->get(
            '/users/getuser/',
            ['controller' => 'Users', 'action' => 'getuser', 'prefix' => 'Api']
        );

        $builder->get(
            '/users/getdatafornotify/{id}',
            ['controller' => 'Users', 'action' => 'getdatafornotify', 'prefix' => 'Api']
        )->setPass(['id']);
        
        $builder->post(
            '/users/submitnotify',
            ['controller' => 'Users', 'action' => 'submitnotify', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/getuserdetails/',
            ['controller' => 'Users', 'action' => 'getuserdetails', 'prefix' => 'Api']
        );
        $builder->post(
            '/users/edituserdetails',
            ['controller' => 'Users', 'action' => 'edituserdetails', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/notifyduration/',
            ['controller' => 'Users', 'action' => 'notifyduration', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/logout/',
            ['controller' => 'Users', 'action' => 'logout', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/getalert/',
            ['controller' => 'Users', 'action' => 'getalert', 'prefix' => 'Api']
        );
        $builder->get(
            '/users/expired/',
            ['controller' => 'Users', 'action' => 'expired', 'prefix' => 'Api']
        );
    });
};
