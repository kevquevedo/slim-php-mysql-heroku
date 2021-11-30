<?php

require_once './controllers/MesaController.php';
use Slim\Routing\RouteCollectorProxy;

//ABM + LISTAR
$app->group ('/mesas', function ( RouteCollectorProxy $group ) {
    $group->get('[/]', MesaController::class . '::ReadAll' )->add(\LoggerMW::class . ':EsSocio');
    $group->get( '/{id}', MesaController::class . '::ReadOne' );
    $group->post('[/]', MesaController::class . '::Insert' );
    $group->put('[/]', MesaController::class . '::Update' );
    $group->delete('[/]', MesaController::class . '::Delete' );
});

//CAMBIA EL ESTADO DE LAS MESAS
$app->group ('/mesas', function ( RouteCollectorProxy $group ) {
    $group->post('/{estado}/{id}', \MesaController::class . ':ModifyMesaMozo');
})->add(\LoggerMW::class . ':EsMozo');
/* cliente esperando pedido
cliente comiendo
cliente pagando */

$app->group ('/mesas/estado', function ( RouteCollectorProxy $group ) {
    $group->post('/cerrada/{id}', \MesaController::class . ':ModifyMesaSocio');
})->add(\LoggerMW::class . ':EsSocio');

$app->group ('/mesas/masUsada', function ( RouteCollectorProxy $group ) {
    $group->post('[/]', \MesaController::class . ':TableMasUsada');
})->add(\LoggerMW::class . ':EsSocio');


?>