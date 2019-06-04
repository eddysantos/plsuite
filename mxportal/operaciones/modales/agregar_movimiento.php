<div class="modal fade" id="agregarMovimiento_modal" tabindex="-1" role="dialog" aria-labelledby="agregarMovimiento" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="nb-id">Agregar Registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="movimiento_tipo">Tipo Movimiento</label>
            <select class="custom-select custom-input" id="movement_type" required>
              <option value="">Tipo</option>
              <option value="Viaje">Viaje</option>
              <option value="Arrastre">Arrastre</option>
              <option value="Cruce">Cruce</option>
            </select>
          </div>
          <div class="form-group">
            <label for="movimiento_origen">Origen</label>
            <select class="custom-select custom-input" data-content="places" id="fk_mx_place_origin" required>
              <option value="">Origenes no Cargados</option>
            </select>
          </div>
          <div class="form-group">
            <label for="movimiento_origen">Destino</label>
            <select class="custom-select custom-input" data-content="places" id="fk_mx_place_destination" required>
              <option value="">Origenes no Cargados</option>
            </select>
          </div>
          <div class="form-group">
            <label for="movimiento_remolque">Remolque</label>
            <select class="custom-select custom-input mr-1" name="movimiento_remolque" id="fk_trailer" required>
              <option value="">Selecciona un remolque</option>
              <?php foreach ($remolques['list'] as $remolque): ?>
                <option value="<?php echo $remolque['pkid_trailer'] ?>"><?php echo $remolque['trailerNumber'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="movimiento_clase">Clase</label>
            <select class="custom-select custom-input" id="movement_class" required>
              <option value="">Clase</option>
              <option value="Trompo">Trompo</option>
              <option value="Vacio">Vacio</option>
              <option value="Cargado">Cargado</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" id="addNewMov_btn" name="button">Agregar</button>
      </div>
    </div>
  </div>
</div>
