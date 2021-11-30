<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware(); 
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//Hago los diferentes require.
require_once './routes/UsuarioRoute.php';
require_once './routes/MesaRoute.php';
require_once './routes/ProductoRoute.php';
require_once './routes/PedidoRoute.php';
require_once './routes/EncuestaRoute.php';

$app->run();

?>
