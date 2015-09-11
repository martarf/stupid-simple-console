<?php

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

$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
}));

$app['AWSFetcher'] = $app->share(function() use ($app) {
    return new PNWPHP\SSC\Service\AWSFetcher($app['pdo'], new \PNWPHP\SSC\Service\EC2Service());
});

return $app;
