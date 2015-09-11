<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Aws\Silex\AwsServiceProvider;
use Herrera\Pdo\PdoServiceProvider;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Application();

// Defaults
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new Silex\Provider\HttpFragmentServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

// DB
$dbPath = dirname(__DIR__) . '/db/db.sqlite';
$app->register(new PdoServiceProvider(), [
    'pdo.dsn' => 'sqlite:' . $dbPath,
]);

// AWS
$app->register(new AwsServiceProvider(), [
    'aws.config' => [
        'version' => 'latest',
        'region' => 'us-west-2',
    ]
]);

$app->register(new Silex\Provider\SessionServiceProvider());

//@todo define DoctrineServiceProvider
$app['db'] = null;

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'login_path' => array(
            'pattern' => '^/$',
            'anonymous' => true
        ),
        'default' => array(
            'pattern' => '^/.*$',
            'anonymous' => false,
            'form' => array(
                'login_path' => '/',
                'check_path' => '/login_check',
            ),
            'logout' => array(
                'logout_path' => '/logout',
                'invalidate_session' => false
            ),
            'users' => $app->share(function($app) {
                return new PNWPHP\SSC\Service\UserService($app);
            }),
        )
    ),
    'security.access_rules' => array(
        array('^/$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
        array('^/.+$', 'ROLE_USER')
    )
));

$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
}));

$app['AWSFetcher'] = $app->share(function() use ($app) {
    return new PNWPHP\SSC\Service\AWSFetcher();
});

return $app;
