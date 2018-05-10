<?php
session_start();
date_default_timezone_set('America/Monterrey');
 ?>

 <!DOCTYPE html>
 <html lang="en">
   <head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="../../Resources/Bootstrap/css/bootstrap.min.css">
     <link rel="stylesheet" href="../../Resources/Bootstrap/FontAwesome/css/font-awesome.min.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="../../Resources/CSS/main.css">
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="../../Resources/CSS/mainMobile.css">
     <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet">
   </head>
   <body>

      <nav class="navbar navbar-toggleable-md">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#menuNavBar" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
         <div class="navbar-brand text-primary">
           Rutas Registradas
         </div>
         <div class="collapse navbar-collapse text-center" id="menuNavBar">
           <form class="form-inline" action="" method="GET">
             <label class="ml-5 mr-2" for="buscarLugar">Buscar</label>
             <input class="form-control mb-2 mr-sm-2 mb-sm-0" type="text" name="buscarLugar" id="buscarLugar" placeholder="Ciudad o Estado" value="">
             <input class="btn btn-secondary form-control mr-sm-2" type="submit" name="Buscar" value="Buscar" role="button">
           </form>
        </div>
        <ul class="navbar-nav">
          <li class="nav-item dropdown mr-2">
            <a class="nav-link btn btn-secondary" role="button" data-toggle="modal" data-target="#nuevaRutaModal">Nueva Ruta</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link btn btn-secondary" role="button" href="/timetracker/Ubicaciones/Administracion/dashboard.php">Regresar</a>
          </li>
        </ul>
      </nav>

      <div class="container-fluid">
        <div class="row mh-100 text-center">
          <div class="col-6">
            <table class="table table-hover text-center">
              <thead>
                <tr>
                  <th class="text-center">Id</th>
                  <th class="text-center">Origen</th>
                  <th class="text-center">Destino</th>
                  <th class="text-center"></th>
                </tr>
              </thead>
              <tbody id="dumpRoutes">
              </tbody>
            </table>
          </div>
          <div class="col-6">
            <h6 class="m-0 clearfix">
              <span class="ml-5">Report Builder</span>
              <span class="d-inline">
                <button id="lastResults" class="btn btn-outline-secondary btn-sm float-right ml-2" type="button" role="button" data-toggle="modal" data-target="#resultadosReporteModal" style="display: none">Ultimos Resultados</button>
                <button id="buildReport" class="btn btn-outline-secondary btn-sm float-right" type="button" name="button" role="button">Hacer Reporte</button>
              </span>
            </h6>
            <div class="" id="reportElements">

            </div>
          </div>

        </div>
      </div>

      <div class="modal fade" id="nuevaRutaModal" tabindex="-1" role="dialog" aria-labelledby="nuevaRutaModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="labelNuevaRuta">Agregar Ruta Nueva</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="form-group" action="" method="post" id="newRouteForm">
                <h4>Origen</h4>
                <div class="row">
                  <div class="col-3">
                    <label for="nuevoOrigenRutaEstado">Estado</label>
                  </div>
                  <div class="col">
                    <label for="nuevoOrigenRutaCiudad">Origen Ciudad</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3 mb-3">
                    <select class="custom-select" id="estado_origen" name="origenEstado">
                      <option value="TX">TX</option>
                      <option value="OK">OK</option>
                      <option value="AR">AR</option>
                      <option value="WI">WI</option>
                      <option value="OH">OH</option>
                      <option value="KS">KS</option>
                      <option value="NE">NE</option>
                      <option value="ND">ND</option>
                      <option value="SD">SD</option>
                      <option value="MN">MN</option>
                      <option value="IA">IA</option>
                      <option value="MO">MO</option>
                      <option value="LA">LA</option>
                      <option value="MS">MS</option>
                      <option value="TN">TN</option>
                      <option value="IN">IN</option>
                      <option value="MI">MI</option>
                    </select>
                  </div>
                  <div class="col">
                    <input class="form-control" type="text" name="nuevoOrigenRutaCiudad" id="inputCiudadOrigen" state="estado_origen" resultsto="#ciudades_origen" for="nuevoOrigenRutaCiudad" value="" autocomplete="off">
                  </div>
                  <div class="col">
                    <label class="custom-control custom-checkbox float-right">
                      <input type="checkbox" class="custom-control-input toggleDirDetalle" target="#rowDetalleOrigen" id="checkOrigenDetalle">
                      <span class="custom-control-indicator"></span>
                      <span class="custom-control-description">Dirección Completa</span>
                    </label>
                  </div>
                </div>
                <div class="row" id="rowDetalleOrigen" style="display: none">
                  <div class="col">
                    <input class="form-control" type="text" name="" value="" placeholder="Solo incluir calle y número.." id="detalleOrigen">
                  </div>
                </div>
                <div class="row">
                  <div class="result-list offset-3 col-8">
                    <ul class="list-unstyled" id="ciudades_origen">
                    </ul>
                  </div>
                </div>
                <h4>Destino</h4>
                <div class="row">
                  <div class="col-3">
                    <label for="nuevoDestinoRutaEstado">Estado</label>
                  </div>
                  <div class="col">
                    <label for="nuevoDestinoRutaCiudad">Ciudad</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3 mb-3">
                    <select class="custom-select" id="estado_destino" name="nuevoDestinoEstado">
                      <option value="TX">TX</option>
                      <option value="OK">OK</option>
                      <option value="AR">AR</option>
                      <option value="WI">WI</option>
                      <option value="OH">OH</option>
                      <option value="KS">KS</option>
                      <option value="NE">NE</option>
                      <option value="ND">ND</option>
                      <option value="SD">SD</option>
                      <option value="MN">MN</option>
                      <option value="IA">IA</option>
                      <option value="MO">MO</option>
                      <option value="LA">LA</option>
                      <option value="MS">MS</option>
                      <option value="TN">TN</option>
                      <option value="IN">IN</option>
                      <option value="MI">MI</option>
                    </select>
                  </div>
                  <div class="col">
                    <input class="form-control" type="text" name="nuevoDestinoRutaCiudad" id="inputCiudadDestino" state="estado_destino" resultsto="#ciudades_destino" for="nuevoDestinoRutaCiudad" value="" autocomplete="off">
                  </div>
                  <div class="col">
                    <label class="custom-control custom-checkbox float-right">
                      <input type="checkbox" class="custom-control-input toggleDirDetalle" target="#rowDetalleDestino" id="checkDestinoDetalle">
                      <span class="custom-control-indicator"></span>
                      <span class="custom-control-description">Dirección Completa</span>
                    </label>
                  </div>
                  </div>
                  <div class="row" id="rowDetalleDestino" style="display: none">
                    <div class="col">
                      <input class="form-control" type="text" name="" value="" placeholder="Solo incluir calle y número.." id="detalleDestino">
                    </div>
                </div>
                <div class="row">
                  <div class="result-list offset-3 col-8">
                    <ul class="list-unstyled" id="ciudades_destino">
                    </ul>
                  </div>
                </div>
              </form>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-primary" id="generarRuta">Generar Ruta</button>
            </div>
          </div>
        </div>
      </div>
    </div>

      <div class="modal fade" id="detallesRutaModal" tabindex="-1" role="dialog" aria-labelledby="detallesRutaModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="labelDetallesRuta">Detalles de Ruta</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Estado</th>
                    <th>Millas</th>
                    <th>Kilómetros</th>
                  </tr>
                </thead>
                <tbody id="dumpRouteDetails">

                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="resultadosReporteModal" tabindex="-1" role="dialog" aria-labelledby="resultadosReporteModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="labelResutadosReporte">Reporte de Millaje por Estado</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="" id="resultRoutes">

              </div>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Estado</th>
                    <th>Viajes</th>
                    <th>Millas</th>
                    <th>Kilómetros</th>
                  </tr>
                </thead>
                <tbody id="insertReportResults">
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="deleteRouteModal" tabindex="-1" role="dialog" aria-labelledby="deleteRouteModal" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title" id="labelDeleteRoute">¿Seguro que desea eliminar la ruta <span id="rutaAEliminar"></span>?</h6>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col text-center">
                  <button type="button" class="btn btn-outline-danger deleteRoute" name="button" role="button">Si</button>
                </div>
                <div class="col text-center">
                  <button type="button" class="btn btn-outline-success deleteRoute" name="button" role="button">No</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>






     <!-- jQuery first, then Tether, then Bootstrap JS. -->
     <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
     <script src="../../Resources/Bootstrap/js/bootstrap.min.js"></script>
     <script src="../../Resources/JS/functions.js" charset="utf-8"></script>
     <script src="../../Resources/JS/cityList.js" charset="utf-8"></script>
     <script src="../../Resources/JS/dashRutas.js" charset="utf-8"></script>
   </body>
 </html>
