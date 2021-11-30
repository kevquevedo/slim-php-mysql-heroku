<?php

require_once './controllers/EncuestaController.php';
use Slim\Routing\RouteCollectorProxy;

$app->group( '/pedido', function ( RouteCollectorProxy $group ) {
    $group->post('/encuesta', \EncuestaController::class . ':LoadEncuesta');
    $group->get('/encuesta/mejoresComentarios', \EncuestaController::class . ':BestComentario');
})->add(\LoggerMW::class . ':EsSocio');


?>