<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

    $app->post('/cap/expedientes', function (Request $request, Response $response) {

        $datos = $request->getParsedBody();

        if(isset($datos['token'])){
            $clave = $datos['token'];
        }else{
            $clave = '-';
        }
        $nombre='-';

        if(comprobarClave($clave, $nombre)){

            $numeroExpediente = $datos['numeroExpediente'];
            $datos = $request->getParsedBody();

            conectarMsSql();

            $MOTIVOS_NO_COBERTURA=[];
            $MOTIVOS_NO_COBERTURA_SQL = $GLOBALS['BBDD2017']->EJECUTAR('SELECT LISTA.[code_element], TEXTO.[text] FROM [SDIC].[imp].[sdicd_systemlist] LISTA,  [SDIC].[imp].[sdics_m_texts] TEXTO WHERE TEXTO.identifier = LISTA.value_element AND  TEXTO.idioma = 8 AND LISTA.list_name = \'his_tip_no_cob\';');
            if($MOTIVOS_NO_COBERTURA_SQL){
                foreach($MOTIVOS_NO_COBERTURA_SQL as $motivo){
                    $MOTIVOS_NO_COBERTURA[$motivo[0]]=$motivo[1];
                }
            }
            

            /* $ORIGENES = $GLOBALS['BBDD']->EJECUTAR('SELECT [PROVINCIA], [CODIGO_COBERTURA], [DESC_COBERTURA], [NIF_COBERTURA], [CONDICION_LESIONADO], [FECHA_ENCARGO], [FECHA_DERIVACION], [FECHA_ACCIDENTE], [FECHA_PRIMERA_CITA], [FECHA_CIERRE], [NHC], [NOMBRE], [APELLIDO1], [APELLIDO2], [FNACIMIENTO], [TELEFONO_MOVIL], [EMAILPAC], [NUMERO_EXPEDIENTE_INTERNO], [NUMERO_SINIESTRO], [NUMERO_ENCARGO], [TIPO_ENCARGO], [TIPO_SINIESTRO], [ORIGEN_ENCARGO], [MEDICO_VALORADOR], [VIGENTE], [IMPORTE_ABONADO], [VALORACION_ATENCION_TELEFONICA], [VALORACION_REHABILITACION], [RECOMENDARIA_SERVICIO], [OBSERVACIONES_ENCARGO], [HECHOS_ENCARGO], [CENTRO], [TRAMITADOR_COD], [TRAMITADOR_DESC], [ESTADO_ENCARGO_COD], [ESTADO_ENCARGO_DESC], [ESTADO_ENCARGO_DP_COD], [ESTADO_ENCARGO_DP_DESC], [CALIFICACION_COD], [ENTIDAD_COD], [ENTIDAD_DESC], [SITUACION_EN_VEHICULO], [ACUDIO_A_URGENCIAS], [CENTRO_PRIMERA_ASISTENCIA], [FECHA_ATENCION], [TRASLADO_EN_AMBULANCIA], [SITUACION_LABORAL], [MUTUA_LABORAL], [IN_ITINERE], [PORTA_COLLARIN], [TOMA_MEDICACION], [BAJA_LABORAL], [OTROS_LESIONADOS], [MATRICULA], [RESCATADO_CENTRO_CONVENIO], [TIPO_PROCESO], [GRUPO_PATOLOGIA_1], [GRUPO_PATOLOGIA_2], [DIAGNOSTICO_1], [DIAGNOSTICO_2], [ALTA_VOLUNTARIA], [ALTA_MEDICA], [RECOBRABLE], [CERTIFICACION], [FECHA_ALTA], [CIRCUNSTANCIA], [TIPO_FEDERADO], [TIPO_FEDERADO_OTRO], [COBERTURA], [SEGURIDAD_SOCIAL], [ESPECIALIDAD_XOGADE], [PROGRAMA_DEPORTIVO], [xacudio_urg], [xambulancia_sn], [xaplicable_fran_sn], [xbaja_lab_sn], [xcalificacion], [xcentro_id], [xcentro_prim_asis], [xcircunstancia], [xcobertura_id], [xcondicion_id], [xdeporte_sn], [xdiagnostico_id], [xdiagnostico2_id], [xencuesta_1], [xencuesta_2], [xencuesta_3], [xencuesta_sn], [xentidad_id], [xestado_encargo_dp], [xestado_encargo_id], [xfecha_atencion], [xfecha_cierre], [xfecha_cita_1], [xfecha_encargo], [xfecha_siniestro], [xgolpe_fuerte_sn], [xgrup_pat_id], [xgrup_pat2_id], [xhechos], [xhora_siniesto], [xid], [ximpmax_sin], [ximp_reserva], [xinitinere_sn], [xmarca_compania], [xmatricula_1], [xmutua_laboral_sn], [xnotas_internas], [xnum_encargo_comp], [xnum_siniestro], [xobservaciones], [xorigen_encargo], [xotros_les], [xpaciente_id], [xplan_cober_id], [xpoblacion_id], [xprefijo_num_sin], [xprofesional_tram], [xprovincia_id], [xrescatado_sn], [xseg_soc_sn], [xsituacion_lab], [xsituacion_veh], [xtipo_encargo_id], [xtipo_federado_id], [xtipo_federado_otr], [xtipo_id], [xtipo_proceso_id], [xtram_codigo], [xtram_email], [xtram_nombre], [xtram_telefono], [xusuario_tram], [xval_email], [xval_nombre], [xval_telefono], [POBLACION_COD], [IMPORTE_RESERVA], [POBLACION_DESC]  FROM [gerosalud].[dbo].[MIGR_NAV_EST_EXPEDIENTES_SQL2017]  WHERE [DESC_COBERTURA] LIKE \'EVE%\'');*/
            
            $datosExpediente = $GLOBALS['BBDD']->EJECUTAR('SELECT [NUMERO_SINIESTRO], [ENTIDAD_DESC], [FECHA_ENCARGO], [FECHA_ACCIDENTE], [ESTADO_ENCARGO_DP_DESC], [COBERTURA], [PROVINCIA], [POBLACION_DESC], [HECHOS_ENCARGO], [DIAGNOSTICO_1], [DIAGNOSTICO_2], [IMPORTE_RESERVA], [IMPORTE_ABONADO], [DESC_COBERTURA], [FECHA_ALTA]  FROM [gerosalud].[dbo].[MIGR_NAV_EST_EXPEDIENTES_SQL2017]  WHERE [NUMERO_SINIESTRO] LIKE \''.$numeroExpediente.'\'');
            if($datosExpediente){
                
                $campos = ['NUMERO_SINIESTRO', 'ENTIDAD_DESC', 'FECHA_ENCARGO', 'FECHA_ACCIDENTE', 'ESTADO_ENCARGO_DP_DESC', 'COBERTURA', 'PROVINCIA', 'POBLACION_DESC', 'HECHOS_ENCARGO', 'DIAGNOSTICO_1', 'DIAGNOSTICO_2', 'IMPORTE_RESERVA', 'IMPORTE_ABONADO', 'DESC_COBERTURA', 'FECHA_ALTA'];
                $EXPEDIENTE = null;
                $pos=0;
                foreach($campos as $campo){
                    $EXPEDIENTE[$campo] = $datosExpediente[0][$pos];
                    $pos++;
                }


                switch(strtolower(trim($EXPEDIENTE['ESTADO_ENCARGO_DP_DESC']))){
                    case ('en curso'):
                        $EXPEDIENTE['ESTADO'] = 'ABIERTO';
                        break;
                    case ('positivo en curso'):
                        break;
                    case ('nuevo (pendiente de primera cita)'):
                        $EXPEDIENTE['ESTADO'] = 'ABIERTO';
                        break;
                    case ('cerrado'):
                        $EXPEDIENTE['ESTADO'] = 'CERRADO';
                        break;
                    case ('paralizado'):
                        $EXPEDIENTE['ESTADO'] = 'CERRADO';
                        break;
                    default:
                    $EXPEDIENTE['ESTADO'] = $EXPEDIENTE['ESTADO_ENCARGO_DP_DESC'];
                        break;
                }

                if(strtoupper(trim($EXPEDIENTE['COBERTURA'])) == 'NO'){
                        
                    $MOTIVO = $GLOBALS['BBDD2017']->EJECUTAR('SELECT [his_tip_no_cob], [his_tip_no_cob_d]  FROM [DATA].[imp].[ge_expedientes] WHERE xnum_siniestro = \''. $EXPEDIENTE['NUMERO_SINIESTRO'].'\'');
                    if(isset( $MOTIVOS_NO_COBERTURA[$MOTIVO[0][0]])){
                        $EXPEDIENTE['MOTIVOS_NO_COBERTURA'] = $MOTIVOS_NO_COBERTURA[$MOTIVO[0][0]];
                    }else{
                        $EXPEDIENTE['MOTIVOS_NO_COBERTURA'] .= $MOTIVO[0][0];
                    }
                    if($MOTIVO[0][1] != ''){
                        $EXPEDIENTE['MOTIVOS_NO_COBERTURA'] = $MOTIVO[0][1];
                    }
                }
            
                    $SEG_SOCIAL = $GLOBALS['BBDD']->EJECUTAR('SELECT count(*) FROM [gerosalud].[dbo].[MIGR_NAV_PRODUCCION_AGP_SQL2017] WHERE DESC_PROVEEDOR = \'ASISTENCIAS SEGURIDAD SOCIAL\' AND SINIESTRO = \''. $EXPEDIENTE['NUMERO_SINIESTRO'].'\'');
                    if($SEG_SOCIAL[0][0]>0){
                        $EXPEDIENTE['FAC_SEG_SOCIAL'] = 'SÍ';
                    }else{
                        $EXPEDIENTE['FAC_SEG_SOCIAL'] = 'NO';
                    }
                    
            
                    $N_ACTO = $GLOBALS['BBDD']->EJECUTAR('SELECT count(*) FROM [gerosalud].[dbo].[MIGR_NAV_PRODUCCION_AGP_SQL2017] WHERE ACTO = \'40IQGH\' AND SINIESTRO = \''. $EXPEDIENTE['NUMERO_SINIESTRO'].'\'');
                    if($N_ACTO[0][0]>0){
                        $EXPEDIENTE['CIRUGIA'] = 'SÍ';
                    }else{
                        $EXPEDIENTE['CIRUGIA'] = 'NO';
                    }
            
                    $EXPEDIENTE['COSTE_ESTIMADO'] =  $EXPEDIENTE['IMPORTE_RESERVA'];
            
                    $EXPEDIENTE['PAGOS']=0;
                    $LISTA_PAGOS = $GLOBALS['BBDD']->EJECUTAR('SELECT [Coste total], [Centro], [Importe], [Desc_ grupo de actos], [Fecha cita] FROM [gerosalud].[dbo].[Navision Producción Detallada] WHERE [Nº siniestro] LIKE \''. $EXPEDIENTE['NUMERO_SINIESTRO'].'\'  ORDER BY [Fecha cita] ASC');
                    if($LISTA_PAGOS){
                        foreach ($LISTA_PAGOS as $pago){
            
                            if($pago[1] == 'SU'){
                                
                                $EXPEDIENTE['PAGOS'] += $pago[0] * 1.15;
                            // echo $pago[3]. ' - '.$pago[0] * 1.15. "\n";
            
                            }else{
            
                                $EXPEDIENTE['PAGOS'] += $pago[2];
                                // echo $pago[3]. ' - '.$pago[2]."\n";
                            }
                            if($EXPEDIENTE['ESTADO'] == 'ABIERTO' AND $EXPEDIENTE['FECHA_ALTA']!=null AND ( strtotime($pago[2]) > (strtotime($EXPEDIENTE['FECHA_ALTA']) + 60*60*24*15))){
                                $EXPEDIENTE['ESTADO'] = 'REAPERTURADO';
                            }
                        }
                    }
            
                    if($EXPEDIENTE['PAGOS'] > $EXPEDIENTE['COSTE_ESTIMADO']){
                        $EXPEDIENTE['COSTE_ESTIMADO'] = $PAGOS * 1.3;
                    }
                    $EXPEDIENTE['RESERVAS'] = $EXPEDIENTE['COSTE_ESTIMADO'] - $EXPEDIENTE['PAGOS']; //$COSTE_ESTIMADO - $PAGOS;
            
                    
                    $EXPEDIENTE['TOTAL'] =  $EXPEDIENTE['PAGOS'] + $EXPEDIENTE['IMPORTE_RESERVA']; //$PAGOS + $RESERVAS;
            
            
            
                $EXPEDIENTE['ACTOS']=null;
                $ACTOS = $GLOBALS['BBDD2017']->EJECUTAR('SELECT [xnum_siniestro],[xagenda_desc],[xprestacion_desc],[xfecha_cita],[xprecio] FROM [DATA].[imp].[ge_produccion] WHERE xacudio_sn LIKE \'Si\' AND  xnum_siniestro LIKE \''. $EXPEDIENTE['NUMERO_SINIESTRO'].'\' '. $FILTRO_FECHAS.' ORDER BY [xfecha_cita] ASC');
                if($ACTOS){
                    $nActo=0;
                    foreach($ACTOS as $acto){

                        $campos = ['xnum_siniestro', 'xagenda_desc', 'xprestacion_desc', 'xfecha_cita', 'xprecio'];
                        $EXPEDIENTE['ACTOS'][$nActo] = null;
                        $pos=0;
                        foreach($campos as $campo){
                            $EXPEDIENTE['ACTOS'][$nActo][$campo] = $acto[$pos];
                            $pos++;
                        }
                        preg_match('/(\d+)\s*sesiones/i', strtolower($acto[2]), $numero);
                        if (isset($numero[1])) {
                            $EXPEDIENTE['ACTOS'][$nActo]['sesiones'] = $numero[1];
                        } else {
                            $EXPEDIENTE['ACTOS'][$nActo]['sesiones']='';
                        }
                        $nActo++;
                    }
                }
            
            }
            
            $payload = json_encode(['CODE' => '000', 'RESULT' => 'OK', 'data' => $EXPEDIENTE, 'REQUEST' => serialize($datos)], JSON_PRETTY_PRINT);
        }else{
            $payload = json_encode(['CODE' => '000', 'RESULT' => 'NOK', 'data' => ['MENSAJE' => 'NO ESTÁ AUTORIZADO PARA ESTA FUNCIÓN'], 'REQUEST' => $request->getUri()->getPath()], JSON_PRETTY_PRINT);
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });

?>