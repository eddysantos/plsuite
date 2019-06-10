$(document).ready(function(){

  //Eventos principales
  $('#table_mx_cartas_porte').on('fetch', function(e, data){
    var tbody = $(this);
    var data = {
      status: $('#status-filter-btns').find('.active').data('status-filter'),
      date_from: $('#date-filter-from').val(),
      date_to: $('#date-filter-to').val()
    }
    console.log(data);
    return false;
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
  $('#tripSearch_box').keyup(function(){
    var $rows = $('#table_mx_cartas_porte tr');
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    $rows.parents('tr').show().filter(function() {
      var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
      return !~text.indexOf(val);
    }).hide();
  });
  $('[data-toggle=slide-panel]').click(function(){
    // console.log($(this).data('target'));
    $($(this).data('target')).addClass('show').css('display', 'block');
  });
  $('[data-dismiss=slide-panel]').click(function(){
    // console.log($(this).data('target'));
    var slide_panel = $('.left-slide-panel.show');
    slide_panel.removeClass('show');
    slide_panel.css('display', 'none')
  });

  //Eventos de filtrado
  $('[data-status-filter]').click(function(){
    if ($(this).hasClass('active')) {
      return false;
    }
    $('[data-status-filter]').removeClass('active');
    $(this).addClass('active');
      $('#table_mx_cartas_porte').trigger('fetch');
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

    $('#table_mx_cartas_porte').trigger('fetch');
  })

});
