<div class="modal fade" id="agregarDestino_modal" tabindex="-1" role="dialog" aria-labelledby="agregarDestino" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="nb-id">Agregar Nuevo Destino</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" role="button">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="cliente_razonsocial">Nombre<sup>*</sup></label>
            <input type="text" class="form-control" id="place_name" required>
          </div>
          <div class="form-group">
            <label for="cliente_alias">Alias</label>
            <input type="text" class="form-control" id="place_alias" required>
          </div>
          <div class="form-group">
            <label for="">Dirección<sup>*</sup></label>
            <div class="form-inline align-items-baseline mb-2">
              <input type="text" class="form-control flex-grow-1" id="place_street_name" placeholder="Calle" name="" value="" autocomplete="new-password" required>
              <div class="pl-5">
                <input type="text" class="form-control d-block mb-1" id="place_street_ext_number" placeholder="Numero Interior" name="" value="" autocomplete="new-password" required>
                <input type="text" class="form-control d-block" id="place_street_int_number" placeholder="Numero Exterior" name="" value="" autocomplete="new-password">
              </div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" id="place_locality" placeholder="Colonia" name="" value="" required>
            </div>
            <div class="form-inline justify-content-between">
              <input type="text" class="form-control" id="place_city" placeholder="Ciudad" name="" value="" autocomplete="new-password" required>
              <input type="text" class="form-control" id="place_state" placeholder="Estado" name="" value="" autocomplete="new-password" required>
              <input type="text" class="form-control" id="place_zip_code" placeholder="Codigo Postal" name="" value="" autocomplete="new-password" required>
            </div>
          </div>
          <div class="form-group">
            <label for="">Horario de Recepción</label>
            <div class="form-inline">
              <select class="custom-select" id="receiving_hours_from" name="">
                <option value="">Desde</option>
                <option value="1:00">1:00</option>
                <option value="2:00">2:00</option>
                <option value="3:00">3:00</option>
                <option value="4:00">4:00</option>
                <option value="5:00">5:00</option>
                <option value="6:00">6:00</option>
                <option value="7:00">7:00</option>
                <option value="8:00">8:00</option>
                <option value="9:00">9:00</option>
                <option value="10:00">10:00</option>
                <option value="11:00">11:00</option>
                <option value="12:00">12:00</option>
                <option value="13:00">13:00</option>
                <option value="14:00">14:00</option>
                <option value="15:00">15:00</option>
                <option value="16:00">16:00</option>
                <option value="17:00">17:00</option>
                <option value="18:00">18:00</option>
                <option value="19:00">19:00</option>
                <option value="20:00">20:00</option>
                <option value="21:00">21:00</option>
                <option value="22:00">22:00</option>
                <option value="23:00">23:00</option>
                <option value="24:00">24:00</option>
              </select>
              -
              <select class="custom-select" id="receiving_hours_from" name="">
                <option value="">Hasta</option>
                <option value="1:00">1:00</option>
                <option value="2:00">2:00</option>
                <option value="3:00">3:00</option>
                <option value="4:00">4:00</option>
                <option value="5:00">5:00</option>
                <option value="6:00">6:00</option>
                <option value="7:00">7:00</option>
                <option value="8:00">8:00</option>
                <option value="9:00">9:00</option>
                <option value="10:00">10:00</option>
                <option value="11:00">11:00</option>
                <option value="12:00">12:00</option>
                <option value="13:00">13:00</option>
                <option value="14:00">14:00</option>
                <option value="15:00">15:00</option>
                <option value="16:00">16:00</option>
                <option value="17:00">17:00</option>
                <option value="18:00">18:00</option>
                <option value="19:00">19:00</option>
                <option value="20:00">20:00</option>
                <option value="21:00">21:00</option>
                <option value="22:00">22:00</option>
                <option value="23:00">23:00</option>
                <option value="24:00">24:00</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="">Contacto<sup>*</sup></label>
            <div class="form-group">
              <input type="text" class="form-control" id="place_contact_name" placeholder="Nombre" name="" value="" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" id="place_contact_email" placeholder="Correo Electrónico" name="" value="" autocomplete="new-password" required>
            </div>
            <div class="form-inline">
              <input type="text" class="form-control mr-3" id="place_contact_phone" placeholder="Teléfono" name="" value="" autocomplete="new-password">
              <input type="text" class="form-control" id="place_contact_other" placeholder="Otro" name="" value="" autocomplete="new-password">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" id="addPlace_btn" name="button">Agregar Destino</button>
      </div>
    </div>
  </div>
</div>
