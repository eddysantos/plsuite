$(document).ready(function(){

  $(function(){
      $('#table_mx_operations').trigger('fetch');
  }) //Init all required devents.

  //Eventos principales
  $('#table_mx_operations').on('fetch', function(e, data){
    var tbody = $(this);
    var data = {
      status: $('#status-filter-btns').find('.active').data('status-filter'),
      date_from: $('#date-filter-from').val(),
      date_to: $('#date-filter-to').val()
    }
    var fetch_trips = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/operations/fetch.php'
    });

    fetch_trips.done(function(r){
      r = JSON.parse(r);
      console.log(r);
      if (r.code == 1) {
        tbody.html(r.data);
      } else {
        tbody.html('<tr><td>No se encontraron viajes</td></tr>');
        alertify.message('No se encontraron viajes!');
        // alertify.message(r.message);
      }
    }).fail(function(x,y,z){
      alertify.error("Hubo un error crítico al cargar los viajes. Favor de reportarlo a soporte técnico.");
      console.error(y);
      console.error(z);
    })
  });
  $('#table_mx_operations').on('click', 'tr', function(){
    tripid = $(this).attr('trip-id');
    if (typeof tripid === 'undefined') {
      return false;
    }
    window.location.href = "ops_details.php?id=" + tripid
  });
  $('#tripSearch_box').keyup(function(){
    var $rows = $('#table_mx_operations tr');
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    $rows.hide();
    $filtered = $rows.filter(function() {
      var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
      // console.log(text);
      // console.log(val + " -> " +  !~text.indexOf(val));
      return !~text.indexOf(val);
    })

    console.log($filtered);
    $filtered.each(function(){
      $(this).hide();
    });

    $rows.addClass('d-flex').show().filter(function() {
      var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
      // console.log(text);
      // console.log(val + " -> " +  !~text.indexOf(val));
      return !~text.indexOf(val);
    }).hide().removeClass('d-flex');



  });

  //Eventos de filtrado
  $('[data-status-filter]').click(function(){
    if ($(this).hasClass('active')) {
      return false;
    }
    $('[data-status-filter]').removeClass('active');
    $(this).addClass('active');
      $('#table_mx_operations').trigger('fetch');
  });
  $('[data-date-filter]').click(function(){
    if ($(this).hasClass('active')) {
      return false;
    }

    $('[data-date-filter]').removeClass('active');
    $(this).addClass('active');
    var action = $(this).data('date-filter');

    if (action == "month") {
      var date = new Date(), y = date.getFullYear(), m = date.getMonth();
      var firstDay = new Date(y, m, 1);
      var lastDay = new Date(y, m + 1, 0);
    }

    if (action == "week") {
      var days = 7; // Days you want to subtract
      var lastDay = new Date();
      var firstDay = new Date(lastDay.getTime() - (days * 24 * 60 * 60 * 1000));
      var day =firstDay.getDate();
      var month=firstDay.getMonth()+1;
      var year=firstDay.getFullYear();
    }

    firstDay = firstDay.toISOString().substr(0, 10)
    lastDay = lastDay.toISOString().substr(0, 10)

    $('#date-filter-from').val(firstDay);
    $('#date-filter-to').val(lastDay);

    $('#table_mx_operations').trigger('fetch');
  })


  // Nueva Operacion modal
  $('#addMovement_btn').click(function(){
    var movement_line = $(`<div class="form-inline mb-1 justify-content-end">
      <select class="custom-select mr-1" name="movimiento_tipo" required>
        <option value="">Tipo</option>
        <option value="Viaje">Viaje</option>
        <option value="Arrastre">Arrastre</option>
        <option value="Cruce">Cruce</option>
      </select>
      <select class="custom-select mr-1" data-content="places" name="movimiento_origen" required disabled>
        <option value="">Origenes no Cargados</option>
      </select>
      <select class="custom-select mr-1" data-content="places" name="movimiento_destino" required disabled>
        <option value="">Destinos no Cargados</option>
      </select>

      <select class="custom-select" name="movimiento_clase" required>
        <option value="">Clase</option>
        <option value="Trompo">Trompo</option>
        <option value="Vacio">Vacio</option>
        <option value="Cargado">Cargado</option>
      </select>
      <i class="far fa-times-circle ml-2 text-danger remove-movement"></i>
    </div>`);
    var remolques = $('#select_remolques').clone();
    remolques.val('');
    movement_line.find('[data-content=places]').last().after(remolques);
    movement_line.appendTo($('#movements_div'));
    $('#movements_div').trigger('load_places', movement_line);
  }); //Agrega un movimiento nuevo al modal.
  $('#movements_div').on('click', '.remove-movement', function(){
    var line = $(this).parents('.form-inline');
    line.remove();
  }) //Elimina un movimiento del modal - solo existe en movimientos agregados (no en el original)
  $('#movements_div').on('load_places', function(e, select_targets = ""){
    if (select_targets == "") {
      var select_targets = $(this).find('[data-content=places]');
    } else {
      select_targets = $(select_targets).find('[data-content=places]');
    }


    data = {
      id: $(this).data('client')
    }

    if (data.id == "" || typeof data.id === 'undefined') {
      return false;
    }

    var fetch_places = $.ajax({
      method: 'POST',
      url: 'actions/places/fetch_places.php',
      data: data
    });

    fetch_places.done(function(r){
      r = JSON.parse(r);

      if (r.code == 1) {
        select_targets.each(function(){
          $(this).html(r.data);
          $(this).attr('disabled', false);
        });
      } else {
        alertify.message(r.message);
      }
    }).fail(function(x, y, z){
      alertify.error('Hubo un error al obtener mx_places, porfavor contacte a Soporte Técnico');
      console.warn(x);
      console.warn(y);
    });
  }) // Evento para cargar lista de lugares en los dropdowns del modal.
  $('#viaje_cliente').on('change', function(){
    alertify.message('Cambió el cliente!');
    console.log(this.value);
    if (this.value != "") {
      data = {
        id: this.value
      }
      $('#movements_div').data('client', data.id);
      $('#movements_div').trigger('load_places',);
    }
  }) //Ejecutar 'load_places' al cambiar/seleccionar el cliente.
  $('#addOperation_btn').click(function(){

    var inputs = $('#nuevaOperacion_modal').find('select');
    var validate = true;
    var movimientos = $('#movements_div').children();


    inputs.each(function(){

      if (this.required) {
        if (this.value == "") {
          $(this).addClass('is-invalid');
          validate = false;
        } else {
          $(this).removeClass('is-invalid');
        }
      }
    })

    if (!validate) {
      alertify.warning('Todos los campos deben estar llenos.');
      return false;
    }

    var data = {
      generales: {
        cliente: $('#viaje_cliente').val(),
        operador: $('#viaje_operador').val(),
        tractor: $('#viaje_tractor').val()
      },
      movimientos: {}
    }

    movimientos.each(function(i){
      data.movimientos[i] = {};
      $(this).children().each(function(){
        var key = this.name;
        var value = this.value;
        data.movimientos[i][key] = value;
      })
    })

    var add_trip = $.ajax({
      method: 'POST',
      url: 'actions/operations/add.php',
      data: data
    });

    add_trip.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        window.location.href = "ops_details.php?id=" + r.tripid
        alertify.success('Viaje agregado exitosamente.');
      } else {
        alertify.warning(r.message)
      }
    }).fail(function(x, y, z){
      console.warn(y);
    });

  })
})
