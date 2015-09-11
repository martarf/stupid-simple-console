<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function() use ($app) {

    return $app['twig']->render('index.html', array(
        'error' => $app['security.last_error']($app['request']),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})
->bind('homepage')
;

$app->get('/logout', function() use ($app) {
    $app['session']->clear();
    return $app->redirect('/');
})
->bind('logout')
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

$app->get('/{project_id}/servers', function($project_id) use ($app) {
    $servers  = $app['AWSFetcher']->getServerListForProject($project_id);
    $user = $app['security.token_storage']->getToken()->getUser();
    $projects = $app['UserService']->getProjectsForUser($user);
    return $app['twig']->render('serverlist.html', ['servers' => $servers, 'projects' => $projects]);
});
