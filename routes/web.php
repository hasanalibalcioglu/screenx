<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('/', function () use ($router) {
    return "CDN SERVER BY H3X";
});

$router->post('/test', 'ScreenshotController@upload');

$router->post('/upload', 'ScreenshotController@upload');

$router->get('/i/{name}', [
    'as' => 'show', 'uses' => 'ScreenshotController@show'
]);

$router->get('/i/{name}/logs', [
    'as' => 'logs', 'uses' => 'ScreenshotController@logs'
]);
