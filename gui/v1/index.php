<?php

use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;

use App\Controllers\HomeController;
use App\Controllers\NotesController;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();


$app->get('/', HomeController::class . ":index");


$app->group('/notes', function (RouteCollectorProxy $group) {
    $group->post('/', NotesController::class . ":store");
    $group->post('/{id}', NotesController::class . ":update");
    $group->delete('/{id}', NotesController::class . ":delete");
});


$app->run();
