<?php
/*********************************************************************************************************************************************
 **********************************************************************************************************************************************
 *	ARCHIVO: bbdd.php
 *	DESCRIPCION: Archivo de configuración de la clase de conexión a la base de datos
 *	FECHA: 15/12/2015
 *	VERSION: 0 [15/12/2015]
 *	REQUISITOS: php-mysql
 *	CONSIDERACIONES:
 *********************************************************************************************************************************************
 ********************************************************************************************************************************************/

class BBDD {
    
    var $CONECTADO = false;
    var $CONEXION = null;
    var $BASE_DATOS="";
    var $RESPONSE = null;
    
    /* Función para la creación de la conexión con la Base de Datos */
    function __construct($DSN, $USUARIO, $PASSWORD){
        
        
        try {
            $this->CONEXION = new PDO($DSN, $USUARIO, $PASSWORD);
            
            #$this->CONEXION->setAttribute (PDO::ATTR_EMULATE_PREPARES, false);
            #$this->CONEXION->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            #$this->CONEXION->setAttribute (PDO::ATTR_PERSISTENT, 1);
            #$this->CONEXION->setAttribute (PDO::ATTR_TIMEOUT, 600);
            
            $this->CONECTADO=true;
            
            /* Ponemos por defecto a UTF8 el sistema*/
	    
	    #·$CONSULTA = $this->CONEXION->prepare("SET NAMES UTF8;");
            #$CONSULTA->execute();
            
            /* Ajustamos el paquete máximo por transacción para evitar problemas con los correos en los dumps a 512MB*/
            //	                $this->EJECUTAR('SET GLOBAL max_allowed_packet=536870912;');
            
        } catch(PDOException $e) {

            $Error="";
            if(isset($this->CONEXION)){
                $Error="<br>";
                foreach ($this->CONEXION->errorInfo() as $linea){
                    $Error.="$linea<br>";
                }
            }

            $payload = json_encode(['CODE' => '999', 'RESULT' => 'NOK', 'DATA' => ['ERROR' => $e->getMessage()], 'REQUEST' => __METHOD__], JSON_PRETTY_PRINT);
            die($payload);
        }
        
    }
    
    /* Función para destruir la conexión con la Base de Datos */
    function __destruct() {
        
        
    }
    
   
    
    
    /* Función para ejecutar una sentencia SQL directamente
    
    */
    function EJECUTAR($SQL, $VARIABLES=null){
        
        try{
            $CONSULTA = $this->CONEXION->prepare($SQL);
            $CONSULTA->execute($VARIABLES);
            $RESULTADO=$CONSULTA->fetchAll();
            return($RESULTADO);
            
        } catch(PDOException $e) {
            
            $payload = json_encode(['CODE' => '999', 'RESULT' => 'NOK', 'DATA' => ['ERROR' => $e->getMessage(), 'SQL' => $SQL, 'VARIABLES' => $VARIABLES], 'REQUEST' => __METHOD__], JSON_PRETTY_PRINT);
            if($this->RESPONSE != null){
                $this->RESPONSE->getBody()->write($payload);
                return $this->RESPONSE->withHeader('Content-Type', 'application/json');
            }else{
                die($payload);
            }
        }
    }
    
}

?>
