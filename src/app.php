<?php
use Acme\UrlService;
require_once dirname(__FILE__).'/../vendor/autoload.php';
$app = new \Silex\Application();
require dirname(__FILE__).'/../config/config.php';


$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../log/system.log',
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/templates',
    'twig.options' => array('cache' => __DIR__.'/../cache'),

));


$app['url_service'] = function() {
    return new UrlService();
};

$app->get('/{url_slug}',function($url_slug) use($app){
    return $app->redirect($app['url_service']->get($url_slug));
});

$app->get('/', function() use ($app){
    return $app['twig']->render('index.html.twig');
});

$app->get('/view/list', function() use($app){
    return $app['twig']->render('list.html.twig', array('list' => $app['url_service']->getAll()));
});

return $app;


//$app->mount('/', new \Acme\DemoController());