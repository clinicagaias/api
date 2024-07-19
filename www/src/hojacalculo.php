<?php
/*********************************************************************************************************************************************
**********************************************************************************************************************************************
*	ARCHIVO: hojacalculo.php
*	DESCRIPCION: Archivo para las modificaciones y personalizaciones de la librería de trabajo con Hojas de Cálculo
*	REQUISITOS: 
*	CONSIDERACIONES:
*********************************************************************************************************************************************
********************************************************************************************************************************************/

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

use PhpOffice\PhpSpreadsheet\Helper\Sample;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Axis;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\GridLines;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\ChartColor;
use PhpOffice\PhpSpreadsheet\Chart\Properties;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend as ChartLegend;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as SharedDate;


class HOJA_CALCULO EXTENDS Spreadsheet{

    var $NOMBRE_ARCHIVO='';
    var $NUMERO_COLUMNAS = 1;
    var $NUMERO_FILAS = 0;
    var $PRIMERA_FILA_CABECERA=false;
    var $CONTIENE_GRAFICAS = false;

    var $LECTOR=null;
    var $CONTENIDO = null;
    var $NUMERO_HOJAS=0;
    var $HOJAS=null;
    var $DATOS =null;
    var $DATOS_HOJA = null;

    var $ARCHIVO_SALIDA = '';

	function CARGAR($ARCHIVO=''){

        \PhpOffice\PhpSpreadsheet\Settings::setLocale('es_ES');
        $this->NOMBRE_ARCHIVO = $ARCHIVO;
        $this->LECTOR = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

	}

    function PROCESAR(){

        $this->CONTENIDO = $this->LECTOR->load($this->NOMBRE_ARCHIVO);
        $this->NUMERO_HOJAS = $this->CONTENIDO->getSheetCount();
        $this->HOJAS =  $this->CONTENIDO->getSheetNames();
        if ($this->HOJAS) {
            foreach ($this->HOJAS as $HOJA) {
                $this->DATOS_HOJA = $this->CONTENIDO->getSheetByName($HOJA);
                $FILAS = $this->DATOS_HOJA->toArray();
                $NOMBRES_COLUMNAS=null;
                $PRIMERA_FILA=0;
                for ($columna = 0; $columna<$this->NUMERO_COLUMNAS; $columna++){
                    if($this->PRIMERA_FILA_CABECERA){
                        $NOMBRES_COLUMNAS[$columna]= $FILAS[0][$columna];
                        $PRIMERA_FILA=1;
                    }else{
                        $NOMBRES_COLUMNAS[$columna]=$columna;
                    }
                }
                for($fila= $PRIMERA_FILA; $fila< count($FILAS); $fila++){
                    for ($columna = 0; $columna < $this->NUMERO_COLUMNAS; $columna++) {
                        $this->DATOS[$HOJA][$fila - $PRIMERA_FILA][$NOMBRES_COLUMNAS[$columna]]=$FILAS[$fila][$columna];
                    }
                }
            }
        }

    }

	

    /****************************************************************************
	
     ******** FUNCIONES PARA REALIZAR FUNCIONES PREDEFINIDAS
	
     ****************************************************************************/



    function GURDAR_XLS()
    {
        $writer = new Xlsx($this);
        if($this->CONTIENE_GRAFICAS){
            $writer->setIncludeCharts(true);
        }
        $writer->save($this->ARCHIVO_SALIDA);
    }

    function GURDAR_ODS()
    {
        $writer = new Ods($this);
        if ($this->CONTIENE_GRAFICAS) {
            $writer->setIncludeCharts(true);
        }
        $writer->save($this->ARCHIVO_SALIDA);
    }


    function IMPRESION_HORIZONTAL(){
        $this->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $this->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
    }



}
