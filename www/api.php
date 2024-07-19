<?php


//error_reporting(0); //PRODUCCIÓN
error_reporting(E_ALL ^ E_NOTICE); //DESARROLLO

ini_set('display_errors', '0');

ini_set('max_execution_time', 6000);  
ini_set("default_socket_timeout", 6000);  
ini_set('memory_limit','256M');   
ini_set('sqlsrv.connect_timeout', 6000);  
ini_set('user_ini.cache_ttl', 6000);  
ini_set("log_errors", 1);
ini_set("error_log", "../log/php-error.log");


require __DIR__ . '/vendor/autoload.php';
require('./src/funciones.php');
require('./src/bbdd.php');
require('./src/hojacalculo.php');

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Factory\RequestFactory;


$app = AppFactory::create();

$app->setBasePath('/api.php');

// Add Routing Middleware
$app->addRoutingMiddleware();

$customErrorHandler = function (
    Request $request,
    Throwable $exception
    
) use ($app) {
    $response = $app->getResponseFactory()->createResponse();
    $payload = json_encode(['CODE' => '999', 'RESULT'=> 'NOK', 'DATA' => ['ERROR' => $exception->getMessage()], 'REQUEST' => $request->getUri()->getPath()], JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
};

// Add Error Middleware
$app->addErrorMiddleware(true, true, true);



/* Realizamos la importación de la configuración 
$ARCHIVO_CONFIGURACION='../config/config.php';
if (file_exists($ARCHIVO_CONFIGURACION)){
	$CONFIGURACION = include($ARCHIVO_CONFIGURACION);
}else{

    $payload = json_encode(['CODE' => '999', 'RESULT'=> 'NOK', 'DATA' => ['ERROR' => 'NO SE PUEDE ACCEDER AL ARCHIVO DE CONFIGURACION'], 'REQUEST' => ''], JSON_PRETTY_PRINT);
    die($payload);

}
*/



$app->any('/', function (Request $request, Response $response, array $args) {
    $payload = json_encode(['CODE' => '000', 'RESULT'=> 'OK', 'REQUEST' => $request->getUri()->getPath()], JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
})->setName('root');

require('../datos/sistema.php');
require('./src/consultas.php');
require('./src/centroatencionpaciente.php');

$app->run();


?>
