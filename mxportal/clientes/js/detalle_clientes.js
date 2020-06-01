$(document).ready(function(){

  $(function(){
    $('#table_mx_places').trigger('fetch');
  }) // Initializers...

  $('.edit-input-btn').click(function(){
    $(this).find('svg').attr('data-icon', 'save');
    $(this).siblings('input').attr('disabled', false).select();
  })
  $('.editable-input').blur(function(){

    $(this).attr('disabled', true);
    $(this).siblings('i').find('svg').attr('data-icon', 'edit');

    var current_value = this.value;
    var old_value = $(this).attr('value');

    if (current_value != old_value) {
      console.log("Edit value!");
    }
  });
  $('#addPlace_btn').click(function(){
      var form = $('#agregarDestino_modal').find('form');
      var inputs = form.find('input');
      var validate = true;
      var data = {};

      data.fk_mx_client = $('#pk_mx_client').val();

      inputs.each(function(){
        var value = this.value;
        var required = this.required;
        data[this.id] = this.value;

        if (required && value == "") {
          $(this).addClass('is-invalid').removeClass('is-valid');
          validate = false;
        } else {
          if (required) {
            $(this).addClass('is-valid').removeClass('is-invalid');
          }
        }
      });



      if (validate) {
        var add_place = $.ajax({
          method: 'POST',
          url: 'actions/client_details/places/add.php',
          data: data
        });

        add_place.done(function(r){
          r = JSON.parse(r);
          console.log(r);
          if (r.code == 1) {
            $('#table_mx_places').trigger('fetch');
            $('.modal.fade.show').modal('hide');
            alertify.success("El destino fue agregado exitosamente.");
          } else {
            alertify.message(r.message)
          }
        }).fail(function(x,y,z){
          alertify.error(z);
          console.log(x);
          console.log(y);
        })
      }

    });

  $('.edit-client-info').click(function(){

    var icon =   $(this).find('svg').attr('data-icon');

    if (icon == 'edit') {
      $(this).find('svg').attr('data-icon', 'save');
      $('#client-general-info').removeClass('uneditable-form')
    } else {
      $(this).find('svg').attr('data-icon', 'edit');
      $('#client-general-info').addClass('uneditable-form')
    }


  });

  //Main events
  $('#table_mx_places').on('fetch', function(e, data = {}){

    data.id = $('#pk_mx_client').val();

    var fetch_places = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/client_details/places/fetch.php',
    });

    fetch_places.done(function(r){
      r = JSON.parse(r);
      console.log(r);
      if (r.code == 1) {
        $('#table_mx_places').html(r.data);
      } else {
        alertify.notify(r.message);
      }
    }).fail(function(x,y,z){
      alertify.error(z);
      console.log(x);
      console.log(y);
    })
  })

});
