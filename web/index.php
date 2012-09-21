<?php
use Acme\UrlService;
require_once dirname(__FILE__).'/../vendor/autoload.php';

$app = new \Silex\Application();

require dirname(__FILE__).'/../config/config.php';

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../log/system.log',
));

$app['url_service'] = function() {
    return new UrlService();
};

$app->get('/{url_slug}',function($url_slug) use($app){
    return $app->redirect($app['url_service']->get($url_slug));
});

$app->get('/',function() use($app){
    return 'Welcome To My First Silex App';
});


//$app->mount('/', new \Acme\DemoController());

$app->run();
