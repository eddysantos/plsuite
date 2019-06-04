$(document).ready(function(){

  $(function(){

    $(document).trigger('fetch_trip');
    $('#opsDetails_movs').trigger('fetch');

  });//  Initialize trip and all values.

  //Main events
  $(document).on('fetch_trip', function(){

    var data = {
      mx_trip: $('#mx_trip').val()
    }

    var load_trip = $.ajax({
      method: 'POST',
      url: 'actions/operations/details/fetch_trip.php',
      data: data
    });

    load_trip.done(function(r){
      r = JSON.parse(r);

      if (r.code == 1) {
        for (var key in r.data) {
          if (r.data.hasOwnProperty(key)) {
            $(`#${key}`).html(r.data[key]);
          }
        }
      } else {
        console.warn(r.message);
        alertify.message(r.message)
      }

    }).fail(function(x,y,z){
      console.error(x);
      console.error(y);
    })
  })

  //#opsDetails_movs based events
  $('#opsDetails_movs').on('fetch', function(){
    var data = {
      mx_trip: $('#mx_trip').val()
    }
    var tbody = $(this);

    var load_movs = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/operations/details/fetch_movs.php'
    });

    load_movs.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        tbody.html(r.data);
      } else {
        console.warn(r.message);
        alertify.message(r.message);
      }
    }).fail(function(x, y, z){
      console.log(z);
    })

  });
  $('#opsDetails_movs').on('click', '[name=saveDetails_btn]', function(){
    var pk_carta_porte = $(this).data('cp-id');
    var this_tr = $(this).parents('.mov-details-box');
    var data = {
      pk_carta_porte: pk_carta_porte
    }

    this_tr.find('.custom-input').each(function(){
      if (this.value == "") {
        data[this.name] = "";
      } else {
        data[this.name] = this.value
      }
    });

    console.log(data);
    var save_edits = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/operations/details/save_mov_details.php'
    });


    save_edits.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        this_tr.addClass('saved-record');
        setTimeout(function () {
          $('#opsDetails_movs').trigger('fetch');
        }, 750);
        $('.modal').modal('hide');
        alertify.success('Changes saved successfully.');
      } else {
        alertify.message(r.message);
        console.warn(r.message);
      }
    }).fail(function(x, y, z){
      console.error(y);
      alertify.error("Hubo un error al guardar la información, favor de notificar a soporte técnico.");
    });
  });
  $('#opsDetails_movs').on('click', '[data-toggle=document]', function(e){
    var cp = $(this).data('cp');
    var url = "/plsuite/mxportal/operaciones/actions/operations/details/print_cp.php?pk_carta_porte=" + cp

    $('#docs_viewer').find('iframe').attr('src', url);
    $('#docs_viewer').modal('show');
  });
  $('#opsDetails_movs').on('click', '[data-toggle=cancel-cp]', function(){
    var data = {
      pk_mx_carta_porte: $(this).data('cp')
    }

    Swal.fire({
      title: '¿Seguro que deseas cancelar la Carta Porte?',
      text: "Este cambio no se puede deshacer!",
      type: 'warning',
      showCancelButton: true,
      cancelButtonText: "No, Regresar",
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Si, cancélalo!'
    }).then((result) => {
      if (result.value) {
        var cancel_cp = $.ajax({
          method: 'POST',
          url: 'actions/operations/details/cancel_mov.php',
          data: data
        });

        cancel_cp.done(function(r){
          r = JSON.parse(r);
          if (r.code == 1) {
            alertify.success('El viaje fue cancelado exitosamente.')
            $('#opsDetails_movs').trigger('fetch');
          } else {
            alertify.message('El viaje no pudo ser cancelado, favor de reportarlo a Soporte Tecnico');
            alertify.message(r.message);
          }
        }).fail(function(x, y, z){
          alertify.error('Hubo un error crítico al procesar la solicitud, favor de reportarlo a Sistemas.')
          console.error(z);
        });
      }
    })

  });

  //Add movement modal events
  $('#agregarMovimiento_modal').on('show.bs.modal', function(e){

    var data_places = {
      id: $('#pk_mx_client').html()
    }
    var modal_element = $(this);

    var load_places = $.ajax({
      method: 'POST',
      data: data_places,
      url: 'actions/places/fetch_places.php'
    });

    load_places.done(function(r){
      var r = JSON.parse(r);
      modal_element.find('[data-content=places]').html(r.data);
    }).fail(function(x,y,z){
      alertify.error('Hubo un error de sistema. Favor de notificar a soporte técnico.');
      console.log(y);
    });
  });
  $('#addNewMov_btn').on('click', function(){
    var open_modal = $(this).parents('.modal');
    var data = {
      pk_mx_trip: $('#mx_trip').val()
    }

    open_modal.find('.custom-input').each(function(e){
      data[this.id] = this.value;
    })

    var save_edits = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/operations/details/add_mov.php'
    });

    save_edits.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        $('#opsDetails_movs').trigger('fetch');
        $('.modal').modal('hide');
        alertify.success('Se agrego el movimiento correctamente');
      } else {
        alertify.message(r.message);
        console.warn(r.message);
      }
      }).fail(function(x, y, z){
      console.error(y);
      alertify.error("Hubo un error al guardar la información, favor de notificar a soporte técnico.");
    });
  });

  //Edit movement events
  $('#editarMovimiento_modal').on('show.bs.modal', function(e){
    var trigger = $(e.relatedTarget);
    var cp_number = trigger.data('cp-number');
    var pk_carta_porte = trigger.data('cp-id');
    var modal_element = $(this);

    var data_cp = {
      pk_mx_carta_porte: pk_carta_porte
    }

    var data_places = {
      id: $('#pk_mx_client').html()
    }

    var load_cp_data = $.ajax({
      method: 'POST',
      data: data_cp,
      url: 'actions/operations/details/fetch_mov_single.php'
    });

    var load_places = $.ajax({
      method: 'POST',
      data: data_places,
      url: 'actions/places/fetch_places.php'
    });

    $.when(load_cp_data, load_places).done(function(cp, places){
      var cp = JSON.parse(cp[0]);
      var places = JSON.parse(places[0]);

      modal_element.find('[data-content=places]').html(places.data)
      if (cp.code == 1 && places.code == 1) {
        for (var key in cp.data) {
          if (cp.data.hasOwnProperty(key)) {
            $(`#${key}`).val(cp.data[key]);
          }
        }
      } else {
        alertify.message("Carta Porte: " + cp.message);
        alertify.message("Places: " + places.message);
        console.warn("Carta Porte: " + cp.message);
        console.warn("Places: " + places.message);
      }
    }).fail(function(x,y,z){
      alertify.error('Hubo un error de sistema. Favor de notificar a soporte técnico.');
      console.log(y);
    });

    $(this).find('.cp_number').html(cp_number);
    $(this).data('carta-porte', pk_carta_porte);
  }) //Update modal with selected cp information.
  $('#saveEditModal_btn').on('click', function(){
    var pk_carta_porte = $(this).parents('.modal').data('carta-porte');
    var open_modal = $(this).parents('.modal');
    var data = {
      pk_mx_carta_porte: pk_carta_porte
    }

    open_modal.find('.custom-input').each(function(e){
      data[this.id] = this.value;
    })

    var save_edits = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/operations/details/save_mov_edit.php'
    });

    save_edits.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        $('#opsDetails_movs').trigger('fetch');
        $('.modal').modal('hide');
        alertify.success('Changes saved successfully.');
      } else {
        alertify.message(r.message);
        console.warn(r.message);
      }
    }).fail(function(x, y, z){
      console.error(y);
      alertify.error("Hubo un error al guardar la información, favor de notificar a soporte técnico.");
    });

  });

});
