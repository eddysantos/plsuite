$(document).ready(function(){

  $(function(){
    $('#table_mx_operations').trigger('fetch');
  }) //Init function...

  //Add Client modal
  $('#addClient_btn').click(function(){
      var form = $('#agregarCliente_modal').find('form');
      var inputs = form.find('input');
      var validate = true;
      var data = {};

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
        var add_client = $.ajax({
          method: 'POST',
          url: 'actions/clients/add.php',
          data: data
        });

        add_client.done(function(r){
          r = JSON.parse(r);

          if (r.code == 1) {
            $('#table_mx_operations').trigger('fetch');
            $('.modal.fade.show').modal('hide');
            alertify.success("Client was added successfully");
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

  //Main events
  $('#table_mx_operations').on('fetch', function(e, data = {}){

    var fetch_clients = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/clients/fetch.php',
    });

    fetch_clients.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        $('#table_mx_operations').html(r.data);
      } else {
        alertify.notify(r.message);
      }
    })
  })
  $('#table_mx_operations').on('click', 'tr', function(){
      var id = $(this).data('id');
      window.location.replace('detalles.php?client_id=' + id);
  })
  $('#clientSearch_box').keyup(function(){
    var data = {
      text: this.value
    }
    $('#table_mx_operations').trigger('fetch', data);
  })
})
