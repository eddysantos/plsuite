<div class="modal fade" id="agregarCliente_modal" tabindex="-1" role="dialog" aria-labelledby="agregarCliente" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="nb-id">Agregar Nuevo Cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="cliente_razonsocial">Razón Social<sup>*</sup></label>
            <input type="text" class="form-control" id="client_razonsocial" required>
          </div>
          <div class="form-group">
            <label for="cliente_rfc">RFC<sup>*</sup></label>
            <input type="text" class="form-control" id="client_rfc" required>
          </div>
          <div class="form-group">
            <label for="cliente_alias">Alias<sup>*</sup></label>
            <input type="text" class="form-control" id="client_alias" required>
          </div>
          <div class="form-group">
            <label for="">Dirección<sup>*</sup></label>
            <div class="form-inline align-items-baseline mb-2">
              <input type="text" class="form-control flex-grow-1" id="client_street_name" placeholder="Calle" name="" value="" autocomplete="new-password" required>
              <div class="pl-5">
                <input type="text" class="form-control d-block mb-1" id="client_street_ext_number" placeholder="Numero Interior" name="" value="" autocomplete="new-password" required>
                <input type="text" class="form-control d-block" id="client_street_int_number" placeholder="Numero Exterior" name="" value="" autocomplete="new-password">
              </div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" id="client_locality" placeholder="Colonia" name="" value="" required>
            </div>
            <div class="form-inline justify-content-between">
              <input type="text" class="form-control" id="client_city" placeholder="Ciudad" name="" value="" autocomplete="new-password" required>
              <input type="text" class="form-control" id="client_state" placeholder="Estado" name="" value="" autocomplete="new-password" required>
              <input type="text" class="form-control" id="client_zip_code" placeholder="Codigo Postal" name="" value="" autocomplete="new-password" required>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" id="addClient_btn" name="button">Agregar Cliente</button>
      </div>
    </div>
  </div>
</div>
