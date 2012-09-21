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
$app['key'] = 'my_key';
$app['inifile'] = __DIR__ . '/../resources/urls.ini';

$app['url_service'] = function() use ($app) {
    return new UrlService($app['inifile']);
};

//$app->register(new \Acme\UrlService(), array('inifile' => __DIR__ . '/../resources/urls.ini'));

$app->get('/add/{key}/{url_slug}', function($url_slug, $key) use ($app){
    if($app['key'] != $key){
        throw new Exception('Invalid key');
    }
    $app['url_service']->add($url_slug, $app['request']->get('url'));
    return $app['twig']->render('add.html.twig', array(
        'url_slug'  =>  $url_slug,
        'url'  =>  $app['request']->get('url')));
});


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