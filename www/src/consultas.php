<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

    $app->post('/consulta1', function (Request $request, Response $response) {

        conectarMsSql();

        $MOTIVOS_NO_COBERTURA=[];
        $MOTIVOS_NO_COBERTURA_SQL = $GLOBALS['BBDD2017']->EJECUTAR('SELECT LISTA.[code_element], TEXTO.[text] FROM [SDIC].[imp].[sdicd_systemlist] LISTA,  [SDIC].[imp].[sdics_m_texts] TEXTO WHERE TEXTO.identifier = LISTA.value_element AND  TEXTO.idioma = 8 AND LISTA.list_name = \'his_tip_no_cob\';');
        if($MOTIVOS_NO_COBERTURA_SQL){
            foreach($MOTIVOS_NO_COBERTURA_SQL as $motivo){
                $MOTIVOS_NO_COBERTURA[$motivo[0]]=$motivo[1];
            }
        }
        
        $datos = $request->getParsedBody();
        
        $nombreNuevaHoja = 'ACTOS ';
        
        $FILTRO ='[ENTIDAD_DESC] LIKE \''.base64_decode($_REQUEST['entidad']).'%\'';
        $FILTRO_FECHAS = '';
        if(isset($_REQUEST['fechaInicio']) AND $_REQUEST['fechaInicio']!=''){
            $FILTRO .=' AND [FECHA_ENCARGO] >= CONVERT(DATE, \''.$_REQUEST['fechaInicio'].'\')';
            $FILTRO_FECHAS .= ' AND [xfecha_cita] >= CONVERT(DATE, \''.$_REQUEST['fechaInicio'].'\')';
            $nombreNuevaHoja .= $_REQUEST['fechaInicio'].' ';
        
        }
        if(isset($_REQUEST['fechaFin']) AND $_REQUEST['fechaFin']!=''){
            $FILTRO .=' AND [FECHA_ENCARGO] <=  CONVERT(DATE, \''.$_REQUEST['fechaFin'].'\')';
            $FILTRO_FECHAS .= ' AND [xfecha_cita] <= CONVERT(DATE, \''.$_REQUEST['fechaFin'].'\')';
            $nombreNuevaHoja .= 'A '.$_REQUEST['fechaFin'].' ';
        }
        
        $RESULTADO=[];
        $RESULTADO['ARCHIVO']= './temp/BDX_TEMPLATE_GEROSALUD_'.time().'.xlsx';
        
                /* $ORIGENES = $GLOBALS['BBDD']->EJECUTAR('SELECT [PROVINCIA], [CODIGO_COBERTURA], [DESC_COBERTURA], [NIF_COBERTURA], [CONDICION_LESIONADO], [FECHA_ENCARGO], [FECHA_DERIVACION], [FECHA_ACCIDENTE], [FECHA_PRIMERA_CITA], [FECHA_CIERRE], [NHC], [NOMBRE], [APELLIDO1], [APELLIDO2], [FNACIMIENTO], [TELEFONO_MOVIL], [EMAILPAC], [NUMERO_EXPEDIENTE_INTERNO], [NUMERO_SINIESTRO], [NUMERO_ENCARGO], [TIPO_ENCARGO], [TIPO_SINIESTRO], [ORIGEN_ENCARGO], [MEDICO_VALORADOR], [VIGENTE], [IMPORTE_ABONADO], [VALORACION_ATENCION_TELEFONICA], [VALORACION_REHABILITACION], [RECOMENDARIA_SERVICIO], [OBSERVACIONES_ENCARGO], [HECHOS_ENCARGO], [CENTRO], [TRAMITADOR_COD], [TRAMITADOR_DESC], [ESTADO_ENCARGO_COD], [ESTADO_ENCARGO_DESC], [ESTADO_ENCARGO_DP_COD], [ESTADO_ENCARGO_DP_DESC], [CALIFICACION_COD], [ENTIDAD_COD], [ENTIDAD_DESC], [SITUACION_EN_VEHICULO], [ACUDIO_A_URGENCIAS], [CENTRO_PRIMERA_ASISTENCIA], [FECHA_ATENCION], [TRASLADO_EN_AMBULANCIA], [SITUACION_LABORAL], [MUTUA_LABORAL], [IN_ITINERE], [PORTA_COLLARIN], [TOMA_MEDICACION], [BAJA_LABORAL], [OTROS_LESIONADOS], [MATRICULA], [RESCATADO_CENTRO_CONVENIO], [TIPO_PROCESO], [GRUPO_PATOLOGIA_1], [GRUPO_PATOLOGIA_2], [DIAGNOSTICO_1], [DIAGNOSTICO_2], [ALTA_VOLUNTARIA], [ALTA_MEDICA], [RECOBRABLE], [CERTIFICACION], [FECHA_ALTA], [CIRCUNSTANCIA], [TIPO_FEDERADO], [TIPO_FEDERADO_OTRO], [COBERTURA], [SEGURIDAD_SOCIAL], [ESPECIALIDAD_XOGADE], [PROGRAMA_DEPORTIVO], [xacudio_urg], [xambulancia_sn], [xaplicable_fran_sn], [xbaja_lab_sn], [xcalificacion], [xcentro_id], [xcentro_prim_asis], [xcircunstancia], [xcobertura_id], [xcondicion_id], [xdeporte_sn], [xdiagnostico_id], [xdiagnostico2_id], [xencuesta_1], [xencuesta_2], [xencuesta_3], [xencuesta_sn], [xentidad_id], [xestado_encargo_dp], [xestado_encargo_id], [xfecha_atencion], [xfecha_cierre], [xfecha_cita_1], [xfecha_encargo], [xfecha_siniestro], [xgolpe_fuerte_sn], [xgrup_pat_id], [xgrup_pat2_id], [xhechos], [xhora_siniesto], [xid], [ximpmax_sin], [ximp_reserva], [xinitinere_sn], [xmarca_compania], [xmatricula_1], [xmutua_laboral_sn], [xnotas_internas], [xnum_encargo_comp], [xnum_siniestro], [xobservaciones], [xorigen_encargo], [xotros_les], [xpaciente_id], [xplan_cober_id], [xpoblacion_id], [xprefijo_num_sin], [xprofesional_tram], [xprovincia_id], [xrescatado_sn], [xseg_soc_sn], [xsituacion_lab], [xsituacion_veh], [xtipo_encargo_id], [xtipo_federado_id], [xtipo_federado_otr], [xtipo_id], [xtipo_proceso_id], [xtram_codigo], [xtram_email], [xtram_nombre], [xtram_telefono], [xusuario_tram], [xval_email], [xval_nombre], [xval_telefono], [POBLACION_COD], [IMPORTE_RESERVA], [POBLACION_DESC]  FROM [gerosalud].[dbo].[MIGR_NAV_EST_EXPEDIENTES_SQL2017]  WHERE [DESC_COBERTURA] LIKE \'EVE%\'');*/
        
        $ORIGENES = $GLOBALS['BBDD']->EJECUTAR('SELECT [NUMERO_SINIESTRO], [ENTIDAD_DESC], [FECHA_ENCARGO], [FECHA_ACCIDENTE], [ESTADO_ENCARGO_DP_DESC], [COBERTURA], [PROVINCIA], [POBLACION_DESC], [HECHOS_ENCARGO], [DIAGNOSTICO_1], [DIAGNOSTICO_2], [IMPORTE_RESERVA], [IMPORTE_ABONADO], [DESC_COBERTURA], [FECHA_ALTA]  FROM [gerosalud].[dbo].[MIGR_NAV_EST_EXPEDIENTES_SQL2017]  WHERE '.$FILTRO.'   ORDER BY [FECHA_ENCARGO] ASC');
        
        
        exec("find ./temp/ -name 'BDX_TEMPLATE_GEROSALUD*.xlsx' -mmin +3600 -delete > /dev/null");
        $HOJACALCULO = new HOJA_CALCULO( $RESULTADO['ARCHIVO']);
        $HOJACALCULO->ARCHIVO_SALIDA =  $RESULTADO['ARCHIVO'];
        
        
        $CONTENIDO = null;
        $CONTENIDO[] = ['Ref Stro', 'Póliza', 'Fecha apertura', 'Fecha siniestro', 'Estado', 'Cía. Aseguradora', 'Provincia', 'Lugar accidente', 'Especialidad médica', 'Descripción accidente', 'Centro médico', 'Diagnóstico', 'Actos médicos', 'Facturas Seg. Social S/N', 'Cirugía S/N', 'Coste estimado total', 'Reservas', 'Pagos', 'Total incurrido'];
        $ULTIMA_LETRA = 'T';
        
        $CONTENIDO2 = null;
        $CONTENIDO2[] = ['Nº siniestro', 'Agenda', 'Servicio', 'Nº SESIONES', 'Fecha cita', 'Coste'];
        $ULTIMA_LETRA2 = 'F';
        
        $HOJACALCULO->getActiveSheet()->getStyle('A1:'. $ULTIMA_LETRA.'1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ]
            ]
        );
        
        $fila_inicial = 2;
        $fila2_inicial = 2;
        
        if($ORIGENES){
            $fila=$fila_inicial;
            $fila2 = $fila2_inicial;
            foreach($ORIGENES as $ORIGEN){
        
                if($ORIGEN[4] === null){
                    $ORIGEN[4] = '';
                }
        
                switch(strtolower(trim($ORIGEN[4]))){
                    case ('en curso'):
                        $ESTADO = 'ABIERTO';
                        break;
                    case ('positivo en curso'):
                        break;
                    case ('nuevo (pendiente de primera cita)'):
                        $ESTADO = 'ABIERTO';
                        break;
                    case ('cerrado'):
                        $ESTADO = 'CERRADO';
                        if(strtoupper(trim($ORIGEN[5])) == 'NO'){
        
                            $MOTIVO = $GLOBALS['BBDD2017']->EJECUTAR('SELECT [his_tip_no_cob], [his_tip_no_cob_d]  FROM [DATA].[imp].[ge_expedientes] WHERE xnum_siniestro = \''. $ORIGEN[0].'\'');
                            if(isset( $MOTIVOS_NO_COBERTURA[$MOTIVO[0][0]])){
                                $ESTADO .= '. '. $MOTIVOS_NO_COBERTURA[$MOTIVO[0][0]];
                            }else{
                                $ESTADO .= '. '.$MOTIVO[0][0];
                            }
                            if($MOTIVO[0][1] != ''){
                                $ESTADO .= '. '.$MOTIVO[0][1];
                            }
                        }
                        break;
                    case ('paralizado'):
                        $ESTADO = 'CERRADO';
                        break;
                    default:
                        $ESTADO = $ORIGEN[4];
                        break;
                }
        
                $SEG_SOCIAL = $GLOBALS['BBDD']->EJECUTAR('SELECT count(*) FROM [gerosalud].[dbo].[MIGR_NAV_PRODUCCION_AGP_SQL2017] WHERE DESC_PROVEEDOR = \'ASISTENCIAS SEGURIDAD SOCIAL\' AND SINIESTRO = \''. $ORIGEN[0].'\'');
                if($SEG_SOCIAL[0][0]>0){
                    $FAC_SEG_SOCIAL = 'SÍ';
                }else{
                    $FAC_SEG_SOCIAL = 'NO';
                }
                
        
                $N_ACTO = $GLOBALS['BBDD']->EJECUTAR('SELECT count(*) FROM [gerosalud].[dbo].[MIGR_NAV_PRODUCCION_AGP_SQL2017] WHERE ACTO = \'40IQGH\' AND SINIESTRO = \''. $ORIGEN[0].'\'');
                if($N_ACTO[0][0]>0){
                    $CIRUGIA = 'SÍ';
                }else{
                    $CIRUGIA = 'NO';
                }
        
                $COSTE_ESTIMADO = $ORIGEN[11];
        
                $PAGOS=0;
                $LISTA_PAGOS = $GLOBALS['BBDD']->EJECUTAR('SELECT [Coste total], [Centro], [Importe], [Desc_ grupo de actos], [Fecha cita] FROM [gerosalud].[dbo].[Navision Producción Detallada] WHERE [Nº siniestro] LIKE \''. $ORIGEN[0].'\'  ORDER BY [Fecha cita] ASC');
                if($LISTA_PAGOS){
                    foreach ($LISTA_PAGOS as $pago){
        
                        if($pago[1] == 'SU'){
                            
                            $PAGOS += $pago[0] * 1.15;
                        // echo $pago[3]. ' - '.$pago[0] * 1.15. "\n";
        
                        }else{
        
                            $PAGOS += $pago[2];
                            // echo $pago[3]. ' - '.$pago[2]."\n";
                        }
                        if($ESTADO == 'ABIERTO' AND $ORIGEN[14]!=null AND ( strtotime($pago[2]) > (strtotime($ORIGEN[14]) + 60*60*24*15))){
                            $ESTADO = 'REAPERTURADO';
                        }
                    }
                }
        
                if($PAGOS > $COSTE_ESTIMADO){
                    $COSTE_ESTIMADO = $PAGOS * 1.3;
                }
                $RESERVAS = "= P{$fila}-R{$fila}"; //$COSTE_ESTIMADO - $PAGOS;
        
                
                $TOTAL = "= R{$fila} + Q{$fila}"; //$PAGOS + $RESERVAS;
        
        
            $CONTENIDO[$fila] = [$ORIGEN[0], str_replace('EVEREST ', '', $ORIGEN[13]), date("d/m/Y", strtotime(substr($ORIGEN[2], 0, 10))),  date("d/m/Y", strtotime(substr($ORIGEN[3], 0, 10))), $ESTADO,  $ORIGEN[1], $ORIGEN[6], $ORIGEN[7], 'Traumatología/Rehabilitación', $ORIGEN[8], 'VARIOS', $ORIGEN[9].$ORIGEN[10], 'VARIOS', $FAC_SEG_SOCIAL, $CIRUGIA,  $COSTE_ESTIMADO, $RESERVAS, $PAGOS, $TOTAL];
        
        
            $ACTOS = $GLOBALS['BBDD2017']->EJECUTAR('SELECT [xnum_siniestro],[xagenda_desc],[xprestacion_desc],[xfecha_cita],[xprecio] FROM [DATA].[imp].[ge_produccion] WHERE xacudio_sn LIKE \'Si\' AND  xnum_siniestro LIKE \''. $ORIGEN[0].'\' '. $FILTRO_FECHAS.' ORDER BY [xfecha_cita] ASC');
            if($ACTOS){
                    foreach($ACTOS as $acto){
        
                        preg_match('/(\d+)\s*sesiones/i', strtolower($acto[2]), $numero);
                        if (isset($numero[1])) {
                            $sesiones = $numero[1];
                        } else {
                            $sesiones='';
                        }
        
                        $CONTENIDO2[$fila2]=[$acto[0], $acto[1], $acto[2], $sesiones, $acto[3], $acto[4]];
                        $fila2++;
                    }
        
            }
        
            $fila++;
            }
        }
        
        $HOJACALCULO->setActiveSheetIndex(0);
        $HOJACALCULO->getActiveSheet()->setTitle('BDX  CAPITA');
        $HOJACALCULO->getActiveSheet()->fromArray($CONTENIDO, null, 'A1');
        for ($i = 'A'; $i <=  $ULTIMA_LETRA; $i++) {
            $HOJACALCULO->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
            $HOJACALCULO->getActiveSheet()->getStyle("{$i}1")->getFont()->setBold(true); 
        }
        $HOJACALCULO->getActiveSheet()->getStyle('P'.$fila_inicial.':S'.($fila-1))->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_EUR);
        $HOJACALCULO->getActiveSheet()->setCellValue('S'.$fila, "= SUM(S".$fila_inicial.":S".($fila-1).")");
        $HOJACALCULO->getActiveSheet()->getStyle('S'.$fila)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_EUR);
        $HOJACALCULO->getActiveSheet()->getStyle('S'.$fila)->getFont()->setBold(true); 
        $HOJACALCULO->getActiveSheet()->getStyle('S'.$fila)->getFont()->setSize(16); 
        
        
        $HOJACALCULO->createSheet(1);
        $HOJACALCULO->setActiveSheetIndex(1);
        $HOJACALCULO->getActiveSheet()->setTitle(trim($nombreNuevaHoja));
        $HOJACALCULO->getActiveSheet()->fromArray($CONTENIDO2, null, 'A1');
        for ($i = 'A'; $i <=  $ULTIMA_LETRA2; $i++) {
            $HOJACALCULO->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
            $HOJACALCULO->getActiveSheet()->getStyle("{$i}1")->getFont()->setBold(true); 
        }
        $HOJACALCULO->getActiveSheet()->getStyle('F'.$fila2_inicial.':F'.($fila2-1))->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_EUR);
        $HOJACALCULO->getActiveSheet()->setCellValue('F'.$fila2, "= SUM(F".$fila2_inicial.":F".($fila2-1).")");
        $HOJACALCULO->getActiveSheet()->getStyle('F'.$fila2)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_EUR);
        $HOJACALCULO->getActiveSheet()->getStyle('F'.$fila2)->getFont()->setBold(true); 
        $HOJACALCULO->getActiveSheet()->getStyle('F'.$fila2)->getFont()->setSize(16); 
        
        
        
        $HOJACALCULO->IMPRESION_HORIZONTAL();
        
        $HOJACALCULO->setActiveSheetIndex(0);
        
        $HOJACALCULO->GURDAR_XLS();

        $payload = json_encode(['CODE' => '000', 'RESULT' => 'OK', 'data' => $RESULTADO, 'REQUEST' => serialize($datos)], JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });

?>