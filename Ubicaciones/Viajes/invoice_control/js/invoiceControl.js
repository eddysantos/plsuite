function fetch_pending_invoice(){
  var pull_data = $.ajax({
    method: 'POST',
    url: 'actions/fetchPendingInvoice.php'
  });

  pull_data.done(function(r){
    r = JSON.parse(r);
    if (r.code == 1) {
      $('#pending-invoice-trips-table').html(r.data);
    } else {
      console.log('There was an error loading the trips with pending invoice.');
      console.error(r.message);
    }
  })
}

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

Date.prototype.yyyymmdd = function() {
  var mm = this.getMonth() + 1; // getMonth() is zero-based
  var dd = this.getDate();

  return [this.getFullYear(),
          (mm>9 ? '' : '0') + mm,
          (dd>9 ? '' : '0') + dd
        ].join('-');
};

$(document).ready(function(){
  $('#pending-invoice-trip-search').keyup(function(){

    var text = $(this).val();
    var pp_active = $('#pending-payments-toggle').attr('active');
    console.log(pp_active);
    if (text == "" && pp_active == 0) {
      $('#trip-invoice-search').html("<tr><td colspan='4'>Type a trip or trailer number</td></tr>");
      return false;
    }

    var pull_data = $.ajax({
      method: 'POST',
      url: 'actions/fetchTripsInvoiceControl.php',
      data: {text: text, pp_active: pp_active}
    });

    pull_data.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        $('#trip-invoice-search').html(r.data);
      } else {
        $('#trip-invoice-search').html("<tr><td colspan='4'>No trips found</td></tr>");
        console.log("There was an error");
        console.error(r.message);
      }
    }).fail(function(x){
      console.error(x);
    })

  });

  $('.tab-change').click(function(){
    var target = $(this).attr('href');
    $(this).parents('.tab-display').fadeOut(function(){
      $(target).fadeIn();
    })
  })

  $('#trip-invoice-search').on('click', 'tr', function(){
    var target = $(this).attr('target');
    var tripno = $(this).find('td:first').html();
    var $this = $(this);
    var data = {
      dbid: $(this).attr('dbid')
    };

    var pull_data = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/fetchSingleTripIc.php'
    });

    pull_data.done(function(r){
      r = JSON.parse(r);
      console.log(r);
      if (r.code == 1) {
        for (var key in r.data) {
          if ($('#' + key).is('select')) {
            continue;
          }
          if (r.data.hasOwnProperty(key)) {
            $('#' + key).html(r['data'][key]).val(r['data'][key]).attr('value', r['data'][key]);
          }
        }
        $this.parents('.tab-display').fadeOut(function(){
          $('#trip-number').html("Trip: " + tripno);
          $(target).fadeIn();
        })
      } else {
        swal('Oops', 'There was an error pulling the trip. Please inform IT', 'error');
        console.error(r.message);
      }
    }).fail(function(x){
      console.error(x);
      swal('Oops', 'There was an error pulling the trip. Please inform IT', 'error');
    });

  })

  $('#save-invoice-info').click(function(){
    var data = {
      invoice_number: $('#invoice_number').val(),
      invoice_amount: $('#invoice_amount').val(),
      payment_date: $('#payment_date').val(),
      check_number: $('#check_number').val(),
      bank_name: $('#bank_name').val(),
      check_comments: $('#check_comments').val(),
      dbid: $('#linehaulid').val(),
      payment_due: $('#payment_due_date').val()
    }

    var update_data = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/updateInvoiceInfo.php'
    });

    update_data.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        alertify.success('Record was updated correctly!');
        $('#pending-invoice-trip-search').keyup();
      } else {
        alertify.error('Record was not updated correctly!');
        console.error(r.message);
      }
    });

  })

  $('[active]').click(function(){
    $(this).toggleClass('btn-outline-secondary btn-secondary');
    var active = $(this).attr('active');
    if (active == 1) {
      $(this).attr('active', 0);
    } else {
      $(this).attr('active', 1);
    }

    $('#pending-invoice-trip-search').keyup();
  })

  $('.set-due-date').click(function(){
    var days_due = $(this).attr('due-days');
    var btn_label = $(this).html();

    var now = new Date();
    now.setDate(now.getDate() + Number(days_due));

    $('#payment_due_date').val(now.yyyymmdd());
  })

  $('#invoice_amount').change(function(){
    var num = parseFloat($(this).val());
    $(this).val(num.toFixed(2));
  })

})
