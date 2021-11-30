<?php

require_once './controllers/UsuarioController.php';
require_once './middlewares/LoggerMW.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

//LOGIN
$app->group('/log', function (RouteCollectorProxy $group){
  $group->post('[/]', \LoggerMW::class . '::VerificarCredenciales');
});

//ABM + LISTAR
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':ReadAll');
    $group->get('/{id}', \UsuarioController::class . ':ReadOne');
    $group->post('[/]', \UsuarioController::class . ':Insert');
    $group->put('[/]', \UsuarioController::class . ':Update');
    $group->delete('[/]', \UsuarioController::class . ':Delete');
  });

?>