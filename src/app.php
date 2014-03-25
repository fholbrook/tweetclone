<?php
/**
 * This file loads the Silex autoloader and registers each servie the app will use.
 * 
 * Registered services include: Twig, Session, Form, Validator, and Doctrine.
 */

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
/**
 * Twig is registered with views in /www/web/views, additional views are located in /vendor/jasongrimes/SimpleUser/src/views for the login and registration functions.
 */
$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../web/views'));

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\FormServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));
/**
 * Doctrine service is registered with mysql database credentials.
 */
$app->register(new Silex\Provider\DoctrineServiceProvider(), array('db.options' => array(
    'driver'   => 'pdo_mysql',
    'dbname' => 'twitclone',
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'z7AT8UEP',
)));
/**
 * Security Provider handles user authentication
 * 
 * Notice that login and register are allowed to anonymous users but nothing else is. This service is then shared with user.manager by Jason Grimes.
 */
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'login_path' => array(
            'pattern' => '^/user/login$',
            'anonymous' => true
        ),
        'registration_path' => array(
            'pattern' => '^/user/register$',
            'anonymous' => true
        ),
        'secured_area' => array(
            'pattern' => '^/.*$',
            'anonymous' => false,
            'form' => array(
                'login_path' => '/user/login',
                'check_path' => '/user/login_check',
            ),
            'logout' => array(
                'logout_path' => '/user/logout',
            ),
            'users' => $app->share(function($app) { return $app['user.manager']; }),
        ),
    ),
));

$app->register(new Silex\Provider\RememberMeServiceProvider());

$app->register(new Silex\Provider\ServiceControllerServiceProvider()); 

$app->register(new Silex\Provider\UrlGeneratorServiceProvider()); 

$app->register($u = new SimpleUser\UserServiceProvider());
/**
 * Route that is used by Jason Grime's User plugin.
 */
$app->mount('/user', $u);


