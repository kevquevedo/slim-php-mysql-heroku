<?php

require_once './controllers/PdfController.php';
use Slim\Routing\RouteCollectorProxy;

$app->group('/pdf', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PdfController::class . ':Down');
});

?>