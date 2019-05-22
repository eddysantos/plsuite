$(document).ready(function(){

  $(function(){
    $('#user-table').trigger('fetch');
  })

  $('#user-table').on('fetch', function(){
    var table = $(this);
    var tbody = $(this).find('tbody');
    var fetch_data = $.ajax({
      method: 'POST',
      url: 'actions/fetch_users.php',
    })

    fetch_data.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        tbody.html(r.data);
      }
    });
  });
  $('#user-table').on('change', '[switcher]', function(){
    var switched = this.checked ? 1 : 0;
    var row = $(this).parents('tr');
    var switch_type = $(this).attr('switcher');
    var url = "";

    // switch (switch_type) {
    //   case 'status':
    //     url = 'actions/status/update.php'
    //     break;
    //
    //   case 'credentials':
    //     url = 'actions/status/update_credentials.php'
    //     break;
    //   default:
    //     alertify.error('Something went wrong, please contact IT');
    // }


    data = {
      id: row.data('id'),
      status: switched,
      credential: switch_type
    }

    var change_status = $.ajax({
      method: 'POST',
      url: 'actions/status/update_credentials.php',
      data: data
    });

    change_status.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        alertify.success(r.message);
      } else {
        alertify.warning(r.message)
      }
    }).fail(function(x, y, z){
      console.error(y);
      console.error(z);
      alertify.error("Something went wrong, please report to IT.")
    });
  })


});
