<div class="modal fade" id="nuevaOperacion_modal" tabindex="-1" role="dialog" aria-labelledby="nuevaOperacion" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="nb-id">Nueva Operación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <label for="" class="mb-0">Datos Generales</label>
          <div class="form datos-generales">
            <div class="form-group">
              <select class="custom-select" name="" id="viaje_cliente" required>
                <option value="">Selecciona un cliente</option>
                <?php foreach ($clientes['list'] as $cliente): ?>
                  <option value="<?php echo $cliente['pk_mx_client'] ?>"><?php echo $cliente['client_name'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="popup-list mt-0" style="display:none; z-index: 9999"></div>
            </div>
            <div class="form-inline justify-content-between">
              <select class="custom-select flex-grow-1 mr-1" name="" id="viaje_operador" required>
                <option value="">Selecciona un operador</option>
                <?php foreach ($operadores['list'] as $operador): ?>
                  <option value="<?php echo $operador['pkid_driver'] ?>"><?php echo "$operador[nameFirst] $operador[nameLast]" ?></option>
                <?php endforeach; ?>
              </select>
              <select class="custom-select flex-grow-1 ml-1" name="" id="viaje_tractor" required>
                <option value="">Selecciona un tractor</option>
                <?php foreach ($tractores['list'] as $tractor): ?>
                  <option value="<?php echo $tractor['pkid_truck'] ?>"><?php echo $tractor['truckNumber'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <label for="" class="mb-0 mt-1">Movimientos</label>
          <div class="form-group" id="movements_div">
            <div class="form-inline mb-1 justify-content-end">
              <select class="custom-select mr-1" name="movimiento_tipo" required>
                <option value="">Tipo</option>
                <option value="Viaje">Viaje</option>
                <option value="Arrastre">Arrastre</option>
                <option value="Cruce">Cruce</option>
              </select>
              <select class="custom-select mr-1" data-content="places" name="movimiento_origen" disabled required>
                <option value="">Origenes no Cargados</option>
              </select>
              <select class="custom-select mr-1" data-content="places" name="movimiento_destino" disabled required>
                <option value="">Destinos no Cargados</option>
              </select>
              <select class="custom-select mr-1" name="movimiento_remolque" id="select_remolques">
                <option value="">Selecciona un remolque</option>
                <?php foreach ($remolques['list'] as $remolque): ?>
                  <option value="<?php echo $remolque['pkid_trailer'] ?>"><?php echo $remolque['trailerNumber'] ?></option>
                <?php endforeach; ?>
              </select>
              <select class="custom-select" name="movimiento_clase" required>
                <option value="">Clase</option>
                <option value="Trompo">Trompo</option>
                <option value="Vacio">Vacio</option>
                <option value="Cargado">Cargado</option>
              </select>
              <i class="far fa-times-circle ml-2 text-danger invisible"></i>
            </div>
          </div>
          <button type="button" class="btn btn-outline-success" id="addMovement_btn" name="button"><i class="fas fa-plus"></i></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" id="addOperation_btn" name="button">Agregar Operacion</button>
      </div>
    </div>
  </div>
</div>
