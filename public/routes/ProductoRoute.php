<?php

require_once './controllers/ProductoController.php';
use Slim\Routing\RouteCollectorProxy;

//ABM + LISTAR
$app->group( '/productos', function ( RouteCollectorProxy $group ) {
    $group->get('[/]', ProductoController::class . '::ReadAll' );
    $group->get( '/{id}', ProductoController::class . '::ReadOne' );
    $group->post('[/]', ProductoController::class . '::Insert' );
    $group->put('[/]', ProductoController::class . '::Update' );
    $group->delete('[/]', ProductoController::class . '::Delete' );
});


?>