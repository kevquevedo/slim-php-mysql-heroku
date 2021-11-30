<?php

require_once './controllers/PedidoController.php';

use Slim\Routing\RouteCollectorProxy;

//ABM + LISTAR
$app->group( '/pedido', function ( RouteCollectorProxy $group ) {
    $group->get('[/]', \PedidoController::class . ':ReadAll')->add(\LoggerMW::class . ':EsSocio');
    $group->get('/{id}', \PedidoController::class . ':ReadOne');
    $group->post('[/]', \PedidoController::class . ':Insert')->add(\LoggerMW::class . ':EsMozo');
    $group->put('[/]', \PedidoController::class . ':Update')->add(\LoggerMW::class . ':EsMozo');
    $group->delete('[/]', \PedidoController::class . ':Delete')->add(\LoggerMW::class . ':EsMozo');
});

$app->group( '/pedido', function ( RouteCollectorProxy $group ) {
    $group->post('/cargarFoto', \PedidoController::class . ':LoadPhoto');
});

$app->group( '/pedido/detalle', function ( RouteCollectorProxy $group ) {
    $group->get('/candy/{estado}', \PedidoController::class . ':LoadCandy')->add(\LoggerMW::class . ':EsPastelero');
    $group->get('/cocina/{estado}', \PedidoController::class . ':LoadCocina')->add(\LoggerMW::class . ':EsCocinero');
    $group->get('/barraTragos/{estado}', \PedidoController::class . ':LoadTragos')->add(\LoggerMW::class . ':EsBartender');
    $group->get('/barraCerveza/{estado}', \PedidoController::class . ':LoadCerveza')->add(\LoggerMW::class . ':EsCervecero');
});

$app->group( '/pedido/cambiarEstado', function ( RouteCollectorProxy $group ) {
    $group->post('/candy/{estado}', \PedidoController::class . ':ModifyCandy')->add(\LoggerMW::class . ':EsPastelero');
    $group->post('/cocina/{estado}', \PedidoController::class . ':ModifyCocina')->add(\LoggerMW::class . ':EsCocinero');
    $group->post('/barraTragos/{estado}', \PedidoController::class . ':ModifyTragos')->add(\LoggerMW::class . ':EsBartender');
    $group->post('/barraCerveza/{estado}', \PedidoController::class . ':ModifyCerveza')->add(\LoggerMW::class . ':EsCervecero');
});

$app->group( '/pedido', function ( RouteCollectorProxy $group ) {
    $group->post('/espera/cliente', \PedidoController::class . ':LoadEspera');
});



?>