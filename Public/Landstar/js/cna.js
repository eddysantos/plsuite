$(document).ready(function(){

  $("#POTable").on('fetch', function(){
    var data = {};
    var tbody = $(this)

    data.status = $('[data-status-filter].active').data('status-filter');
    data.date_from = $('#date-filter-from').val();
    data.date_to = $('#date-filter-to').val();

    var getData = $.ajax({
      method: 'POST',
      url: 'actions/fetchPOS.php',
      data: data
    })

    getData.done(function(r){
      r = JSON.parse(r);
      console.log(r);
      if (r.code == 1) {
        tbody.html(r.data);
      } else {
        tbody.html("<tr><td colspan='7'>No Data Available<td><tr>")
        alertify.error(r.message);
      }
    });


    console.log(data);
  });

  $('#addPoForm').on('change', '.po-input', function(e){
    var theForm = $(e.delegateTarget);
    var inputs = theForm.find('.po-input');
    var removeBtn = $(`<i class="fas fa-times align-self-center text-danger btn p-0 remove-line"></i>`);

    var addLine  = true;


    inputs.each(function(){
      if (this.value == "") {
        addLine = false;
      }
    });

    if (addLine) {
      var oldLine = $(this).parents('.po-line');
      var newLine = oldLine.clone();
      oldLine.find('.po-input').attr('disabled', true);
      oldLine.addClass('has-data');
      removeBtn.prependTo(oldLine);
      newLine.find('.po-input').each(function(){
        $(this);
        $(this).val("").attr('disabled');;
      })
      newLine.prependTo(theForm).focus();
      $('.po-line').first().find('input').first().focus();
      // theForm.append(newLine);
    }
  })
  $('#addPoForm').on('click', '.remove-line', function(){
    var line = $(this).parents('.po-line');
    line.remove();
  });

  $('#savePosBtn').on('click', function(){
    var data = {}
    var modal = $(this).parents('.modal');

    var lines = modal.find('.po-line.has-data');
    lines.each(function(i){
      data[i] = {};
      $(this).find('.po-input').each(function(){
        data[i][this.name] = this.value;
      });
    });

    var insertPOs = $.ajax({
      data: data,
      url: 'actions/addPOs.php',
      method: 'post'
    });

    insertPOs.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        $('.modal.show').find('.po-line.has-data').remove();
        $('.modal.show').modal('hide');
        alertify.message("POs Added successfuly!");
        $('#POTable').trigger('fetch');
      }
    }).fail(function(x,y,z){
      console.error(z);
    })

  });

  $('[data-status-filter]').on('click', function(){
    var siblings = $(this).siblings();

    siblings.removeClass("active");
    $(this).addClass('active');

    $("#POTable").trigger('fetch');
  })
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

    $('#POTable').trigger('fetch');
  });
  $('#date-filter-from, #date-filter-from').change(function(){
    var dFrom = new Date($('#date-filter-from').val());
    var dTo = new Date($('#date-filter-to').val());

    if (dFrom < dTo) {
      $("#POTable").trigger('fetch');
    }
  });

  $('.table-filter').keyup(function(){
    var table = $(this).data('target-table');
    var rows = $(table).find('tr');
    var input = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    rows.show().filter(function() {
      var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
      return !~text.indexOf(input);
    }).hide();
  })

});
