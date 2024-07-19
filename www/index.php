<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <title>CONSULTAS TRAMITACIÓN</title>

  <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/css/dashboard.css" rel="stylesheet">



  <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/js/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
  <script src="./assets/js/dashboard.js"></script>
  <script src="./assets/js/jquery.min.js"></script>

  <script>
    function funcionConsulta1() {
      $('.consultas').hide();
      $('#consulta1').show();
    }

    function funcionConsulta1Generar() {

        if($('#fechaInicio').val() > $('#fechaFin').val()){

          alert('LA FECHA DE INICIO NO PUEDE SER MAYOR QUE LA FECHA DE FIN');

        }else{

          $('#resultadoConsulta1').html('<div class = "spinner-border text-warning" role = "status" > <span class = "sr-only" ></span> </div>');

          $.post('api.php/consulta1', {
            Usuario: '-',
            Clave: '-',
            entidad: $('#entidad').val(),
            fechaInicio: $('#fechaInicio').val(),
            fechaFin: $('#fechaFin').val(),
          }, function(data) {

            if(data.RESULT == 'OK'){
              $('#resultadoConsulta1').html('<button type="button" class="btn btn-sm btn-outline-secondary" onclick="NUEVA_PESTANHA(\'' + data.data.ARCHIVO + '\');">DESCARGAR EXCEL</button>');
            }else{
              $('#resultadoConsulta1').html(data);
            }
            
          });
        }
    }


    function funcionConsulta2() {
      $('.consultas').hide();
      $('#resultadoConsulta2').html(' ');
      $('#consulta2').show();
    }

    function funcionConsulta2Ejecutar() {
      $('#resultadoConsulta2').html('<div class = "spinner-border text-warning" role = "status" > <span class = "sr-only" ></span> </div>');
      $.post('api.php/consulta2', {
        Usuario: '-',
        Clave: '-',
        Busqueda: $('#buscarConsulta2').val()
      }, function(data) {

        $('#resultadoConsulta2').html(data.data.TABLA);

      });


    }


    $(document).ready(function() {


    });
  </script>

</head>

<body>

  <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">GEROSALUD / GAIAS</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav">
      <div class="nav-item text-nowrap">
        <!--<a class="nav-link px-3" href="#">Sign out</a> -->
      </div>
    </div>
  </header>

  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3 sidebar-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#" onclick="funcionConsulta1();">
                <span data-feather="home" class="align-text-bottom"></span>
                EXCEL COMPAÑIAS
              </a>
            </li>
            
              <!--
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#" onclick="funcionConsulta2();">
                <span data-feather="home" class="align-text-bottom"></span>
                Consulta de Objetos Digitales
              </a>
            </li>
          -->

          </ul>
        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class=" consultas" id='consulta1' style="display: none">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class=" h2">EXCEL COMPAÑIAS ASEGURADORAS</h1>
            <div class="btn-toolbar mb-2 mb-md-0"></div>
          </div>    
          <div class="content pt-3">
            <div class="row pb-2">
                <div class="form-group col-6 ">
                    <label for="entidad">Seleccione una compañía:</label>
                    <select name="entidad" id="entidad">
                      <option value="<?php echo base64_encode('EVEREST INSURANCE (IRELAND)DAC, SUCURSAL EN ESPAÑA'); ?>">EVEREST INSURANCE (IRELAND)DAC, SUCURSAL EN ESPAÑA</option>
                      <?php
                      
                        require('./src/bbdd.php');
                        $GLOBALS['BBDD'] = new BBDD('sqlsrv:server=sql.dominio.local;database=gerosalud;TrustServerCertificate=true', '5vc_reporting2', '@2Dm8zJuYQVXm');
                        $ENTIDADES = $GLOBALS['BBDD']->EJECUTAR('SELECT DISTINCT [ENTIDAD_DESC] FROM [gerosalud].[dbo].[MIGR_NAV_EST_EXPEDIENTES_SQL2017]');
                        if($ENTIDADES){
                          foreach ($ENTIDADES as $entidad){
                            echo '<option value="'.base64_encode($entidad[0]).'">'.$entidad[0].'</option>
                            ';
                          }
                        }

                      ?>
                  </select>
                  <small id="emailHelp" class="form-text text-muted"></small>
              </div>
              <div class="form-group col-3 ">
                  <label for="fechaInicio">Fecha de inicio:</label>
                  <input type="date" name="fechaInicio" id="fechaInicio">
              </div>
              <div class="form-group col-3 ">
                <label for="fechaFin">Fecha de fin:</label>
                <input type="date" name="fechaFin" id="fechaFin" value="<?php echo date("Y-m-d", strtotime("last day of previous month")); ?>">
              </div>
            </div>
            <div class="row pt-2">
              <div class="col pb-2">
                <button  class="btn btn-primary"  onclick="funcionConsulta1Generar();">GENERAR EXCEL</button>
              </div>
          </div>
        </div>

          <div id="resultadoConsulta1" class="resultado">


          </div>
        </div>
        <div class="consultas" id='consulta2' style="display: none">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Consulta de Objetos Digitales</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <!--
          <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar" class="align-text-bottom"></span>
            This week
          </button>
          -->
            </div>
          </div>
          <br>
          <div class="input-group">
            <input type="text" class="form-control" aria-label="Búsqueda de objetos digitales" id="buscarConsulta2">
            <div class="input-group-append">
              <button type="button" class="btn btn-outline-secondary" onclick="funcionConsulta2Ejecutar();">BUSCAR</button>
            </div>
          </div>
          <div id="resultadoConsulta2" class="resultado">


          </div>
        </div>


      </main>
    </div>
  </div>
</body>

</html>