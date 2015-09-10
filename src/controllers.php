<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html', array());
})
->bind('homepage')
;

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});

$app->get('/{project}/servers', function($project) use ($app) {
    //$servers = $app['AWSFetcher']->getServerListForProject($project);
    $server1 = new \PNWPHP\SSC\Data\ServerStatus("server1",true,true,1);
    $server2 = new \PNWPHP\SSC\Data\ServerStatus("server2",true,true,2);
    $server3 = new \PNWPHP\SSC\Data\ServerStatus("server3",true,true,3);
    $server4 = new \PNWPHP\SSC\Data\ServerStatus("server4",true,true,4);
    $servers = [$server1,$server2,$server3,$server4];
    return $app['twig']->render('serverlist.html', ['servers' => $servers]);
});
