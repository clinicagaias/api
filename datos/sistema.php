<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


define("archivoClaves", '../datos/claves.json');

    $app->post('/generarClaves', function (Request $request, Response $response) {

        $datos = $request->getParsedBody();

        if(isset($datos['token'])){
            $clave = $datos['token'];
        }else{
            $clave = '-';
        }
        $nombre='-';

        if(comprobarClave($clave, $nombre)){
            generarClaves();
            $nuevaClave = claveUsuario($nombre);
            $payload = json_encode(['CODE' => '000', 'RESULT' => 'OK', 'data' => ['MENSAJE' => $nombre.' Su nuevo token es: '.$nuevaClave], 'REQUEST' => $request->getUri()->getPath()], JSON_PRETTY_PRINT);
        }else{
            $payload = json_encode(['CODE' => '000', 'RESULT' => 'NOK', 'data' => ['MENSAJE' => 'NO ESTÁ AUTORIZADO PARA ESTA FUNCIÓN'], 'REQUEST' => $request->getUri()->getPath()], JSON_PRETTY_PRINT);
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });

    function generarClaves(){

        $CLAVES = null;
        $CLAVES[adaptarClaves(base64_encode(random_bytes(160 / 8)))] = 'Miguel';
        $CLAVES[adaptarClaves(base64_encode(random_bytes(160 / 8)))] = 'Iago';
        $CLAVES[adaptarClaves(base64_encode(random_bytes(160 / 8)))] = 'Centro de atención al paciente';

        $almacen = fopen(archivoClaves, 'w');
        fwrite($almacen, json_encode($CLAVES, JSON_PRETTY_PRINT));
        fclose($almacen);

    }

    function comprobarClave($clave, &$nombre){
        $claves = json_decode(file_get_contents(archivoClaves), true);
        if(is_array($claves)){
            if(isset($claves[$clave])){
                $nombre= $claves[$clave];
                return(true);
            }
        }
        $nombre='-';
        return(false);
    }

    function claveUsuario($nombre){
        $claves = json_decode(file_get_contents(archivoClaves), true);
        if(is_array($claves)){
            foreach($claves as $clave => $usuario){
                if($nombre == $usuario){
                    return($clave);
                }
            }
        }
        return(null);
    }




?>

