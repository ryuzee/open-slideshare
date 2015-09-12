<?php

/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 0.2.9
 */

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */

// Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
Router::connect('/', array('controller' => 'slides', 'action' => 'index'));

/*
 * ...and connect the rest of 'Pages' controller's URLs.
 */
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * API routing
 */
Router::connect('/api/v1/slides/*', array('controller' => 'api_v1', 'action' => 'get_slides'));
Router::connect('/api/v1/slide/:id', array('controller' => 'api_v1', 'action' => 'get_slide_by_id'), array('id' => '[0-9]+'));
Router::connect('/api/v1/slide/:id/transcript', array('controller' => 'api_v1', 'action' => 'get_transcript_by_id'), array('id' => '[0-9]+'));
Router::connect('/api/v1/user/:id', array('controller' => 'api_v1', 'action' => 'get_user_by_user_id'), array('id' => '[0-9]+'));
Router::connect('/api/v1/user/:id/slides', array('controller' => 'api_v1', 'action' => 'get_slides_by_user_id'), array('id' => '[0-9]+'));
// Router::connect('/api/v1/:action/*', array('controller' => 'api_v1'));

/*
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/*
 * Activate rss
 */
Router::parseExtensions('rss');

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
