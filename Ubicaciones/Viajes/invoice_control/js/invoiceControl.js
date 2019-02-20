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

  $(function(){ //onload executables.
    $('#apply-filter').click();
  })

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

        if (data.payment_due_date == undefined) {
          $('[due-days=30]').click();
        }
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
      payment_due: $('#payment_due_date').val(),
      invoice_date: $('#invoice_date').val()
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
    $('#due-date-button').html(btn_label);
    $('#payment_due_date').val(now.yyyymmdd());
  })

  $('#invoice_amount').change(function(){
    var num = parseFloat($(this).val());
    $(this).val(num.toFixed(2));
  })

  $('#invoice_number').change(function(){
    if ($(this).val() == "") {
      return false;
    }

    if ($('#invoice_date').val() == "") {
      var today = new Date();
      var today_f = today.yyyymmdd();
      $('#invoice_date').val(today_f);
    }


  })

  $('.dropdown.invoice-export-dropdown').on({
    "shown.bs.dropdown" : function(){this.closable = false;},
    // "click"             : function(){this.closable = true;},
    "hide.bs.dropdown"  : function(e){
      return this.closable
    }
  });
  $('.dropdown.invoice-export-dropdown').on('click', '.close-filter', function(e){
    e.delegateTarget.closable = true;
    // e.delegateTarget.dropdown('hide');
  });
  $('#export-invoices').click(function(){
    var $inputs = $(this).parents('.dropdown-menu').find('input');
    var data = {
      trips:{
        from: "",
        to: "",
        client:{
          name: "",
          id: "",
        },
        trailer:{
          name: "",
          id: "",
        }
      },
      invoice:{
        from: "",
        to: "",
        no_invoice: ""
      },
      payment:{
        from: "",
        to: "",
        no_payment: ""
      },
    };

    $inputs.each(function(){
      if (this.value != "") {
        switch (this.name) {
          case "trip-from":
          data.trips.from = this.value;
          data.trips.to = this.value;
          break;

          case "trip-to":
            data.trips.to = this.value;
            break;

          case "invoice-from":
            data.invoice.from = this.value;
            data.invoice.to = this.value;
            break;

          case "invoice-to":
            data.invoice.to = this.value;
            break;

          case "payment-from":
            data.payment.from = this.value;
            data.payment.to = this.value;
            break;

          case "payment-to":
            data.payment.to = this.value;
            break;

          case "client-name":
            data.client.name = this.value;
            data.client.id = $(this).attr('db-id');
            break;

          case "trailer-number":
            data.trailer.name = this.value;
            data.trailer.id = $(this).attr('db-id');
          default:
          //Ignore!
        }
      }
    })
    console.log(data);
  })
  $('.show-filter').click(function(){
    $('.filter-tab').animate({
      right: 0
    }, 300);
  });
  $('.hide-filter').click(function(){
    $('.filter-tab').animate({
      right: "-25%"
    }, 300);
  });
  $('#apply-filter').click(function(){
    var data = {
      trips:{
        from: "",
        to: "",
        client:{
          name: "",
          id: "",
        },
        trailer:{
          name: "",
          id: "",
        }
      },
      invoice:{
        from: "",
        to: "",
        no_invoice: ""
      },
      payment:{
        from: "",
        to: "",
        no_payment: ""
      },
    };
    $('#filter-form').find('input').each(function(){
      if (this.value != "") {
        switch (this.name) {
          case "trip-from":
          data.trips.from = this.value;
          data.trips.to = this.value;
          break;

          case "trip-to":
            data.trips.to = this.value;
            break;

          case "invoice-from":
            data.invoice.from = this.value;
            data.invoice.to = this.value;
            break;

          case "invoice-to":
            data.invoice.to = this.value;
            break;

          case "no-invoice":
            if (this.checked) {
              data.invoice.no_invoice = this.value;
            }
            break;

          case "payment-from":
            data.payment.from = this.value;
            data.payment.to = this.value;
            break;

          case "payment-to":
            data.payment.to = this.value;
            break;

          case "no-payment":
            if (this.checked) {
              data.payment.no_payment = this.value;
            }
            break;

          case "client-name":
            data.trips.client.name = this.value;
            data.trips.client.id = $(this).attr('db-id');
            break;

          case "trailer-number":
            data.trips.trailer.name = this.value;
            data.trips.trailer.id = $(this).attr('db-id');
          default:
          //Ignore!
        }
      }
    });
    console.log(data);

    var pull_data = $.ajax({
      method: 'POST',
      url: 'actions/fetchTripsInvoiceControl.php',
      data: data
    });

    pull_data.done(function(r){
      r = JSON.parse(r);
      console.log(r);
      if (r.code == 1) {
        $('#trip-invoice-search').html(r.data);
      } else {
        $('#trip-invoice-search').html("<tr><td colspan='4'>No trips found</td></tr>");
        console.log("There was an error");
        console.error(r.message);
      }
      $('.hide-filter').click();
    }).fail(function(x){
      console.error(x);
    })


  });
  $('#table-search').keyup(function(){
    var $rows = $('#trip-invoice-search tr');
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    $rows.show().filter(function() {
      var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
      return !~text.indexOf(val);
    }).hide();
  })


  $('.popup-input').keydown(function(e){
    if (e.keyCode === 13 || e.keyCode === 9) {
      e.preventDefault();
      var targetFocus = $(document.activeElement).attr('id-display') + " p" + ".hovered";


      var dbid = $(targetFocus).attr('db-id');
      var inputTarget = $(targetFocus).parent().attr('id');
      var type = $(targetFocus).parent().attr('type');
      var target = $(targetFocus).parent().attr('target');
      var name = $(targetFocus).html();
      var plates = $(targetFocus).attr('plates');

      switch (type) {
        case 'multiple':
        add_driver(name, dbid);
        break;
        default:
        if (plates) {
          $("[id-display='#" + inputTarget+ "']").attr('plates', plates);
        }
        $("[id-display='#" + inputTarget+ "']").attr("value", $(targetFocus).html()).attr('db-id', $(targetFocus).attr('db-id'));
        $("[id-display='#" + inputTarget+ "']").prop("value", $(targetFocus).html()).change();
        $('.popup-list').slideUp();
      }


    }
  });
  $('.popup-input').keyup(function(e){
    if (e.keyCode === 38 || e.keyCode === 40 || e.keyCode === 13 || e.keyCode === 9){return false;}
    data = {}
    pop = $(this).attr('id-display');
    data.txt = $(this).val();


    if (data.txt == "") {
      $('.popup-list').slideUp();
      return false;
    } else {

      if (pop.indexOf('trailer') >= 0){
        url = "../actions/fetchTrailersPopup.php"
      }
      if (pop.indexOf('truck') >= 0){
        url = "../actions/fetchTrucksPopup.php"
      }
      if (pop.indexOf('driver') >= 0){
        url = "../actions/fetchDriversPopup.php"
      }
      if (pop.indexOf('broker') >= 0) {
        url = "../actions/fetchBrokersPopup.php"
      }

      $.ajax({
        method: 'POST',
        data: data,
        url: url,
        success: function(result){
          resp = JSON.parse(result);

          switch (resp.code) {
            case 1:
              $(pop).html(resp.data).slideDown();
              break;
            case 2:
            $(pop).html("<p>No se encontraron resulados...</p>").slideDown();
              break;
            default:
            console.error(resp.message);
            $(pop).html("").slideUp();

          }
        },
        error: function(exception){
          console.error(exception);
        }
      })
    }
  })
  $('.popup-list').on('click', 'p', function(){
    var dbid = $(this).attr('db-id');
    var inputTarget = $(this).parent().attr('id');
    var name = $(this).html();
    if (inputTarget == "driver-popup-list-modal") {
        add_driver(name, dbid);
        return false;
    }

    if (inputTarget == "trailer-popup-list") {
      $("[id-display='#" + inputTarget+ "']").attr('plates', $(this).attr('plates'));
    }
    $("[id-display='#" + inputTarget+ "']").attr("value", $(this).html()).attr('db-id', $(this).attr('db-id')).blur();
    $("[id-display='#" + inputTarget+ "']").prop("value", $(this).html()).blur();
    $('.popup-list').slideUp();

  });
  $('.popup-list').on('mouseenter', 'p', function(){
    $('.hovered').attr('class', '');
    $(this).attr('class', 'hovered');
  });
  $('.popup-list').on('mouseleave', 'p', function(){
    $(this).attr('class', '')
  });

  $(document).keydown(function(e){
    if (e.keyCode == 38 || e.keyCode == 40){
      if ($(document.activeElement).attr('id-display') !== undefined) {
        var target = $(document.activeElement).attr('id-display') + " p";
        var targetFocus = $(document.activeElement).attr('id-display') + " p" + ".hovered";

        if ($(targetFocus).length == 0) {
          $(target).first().addClass('hovered');
        } else {
          if (e.keyCode == 40) {
            $(targetFocus).removeClass('hovered').next().addClass('hovered');
          }

          if (e.keyCode == 38) {
            $(targetFocus).removeClass('hovered').prev().addClass('hovered');
          }
        }

      }
    }

    if (e.keyCode === 13 || e.keyCode === 9) {
      var targetFocus = $(document.activeElement).attr('id-display') + " p" + ".hovered";

      var dbid = $(targetFocus).attr('db-id');
      var inputTarget = $(targetFocus).parent().attr('id');
      $("[id-display='#" + inputTarget+ "']").attr("value", $(targetFocus).html()).attr('db-id', $(targetFocus).attr('db-id'));
      $("[id-display='#" + inputTarget+ "']").prop("value", $(targetFocus).html());
      $('.popup-list').slideUp();

    }


  });

})
