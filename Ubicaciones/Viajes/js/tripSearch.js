function validateForm(form_inputs, current_target){
  var isValid = true;
  var iter = form_inputs.length;
  form_inputs.each(function(){
    if ($(this).attr('db-id') === "") {
      $(this).addClass('is-invalid');
      $(this).siblings('.invalid-feedback').html('Must select option from dropwdown list');
      $('.popup-list').fadeOut();
      return false;
    } else if ($(this).attr('db-id') !== undefined) {
      $(this).removeClass('is-invalid').addClass('is-valid');
      $(this).siblings('.invalid-feedback').html('');
      $('.popup-list').fadeOut();
    }


    if ($(this).val() === "") {
      $(this).addClass('is-invalid');
      $(this).siblings('.invalid-feedback').html('This field must be filled out');
      return false;
    } else {
      $(this).removeClass('is-invalid').addClass('is-valid');
      $(this).siblings('.invalid-feedback').html('');
    }
    if ($(this).is(current_target)) {
      return false;
    }
  });

  form_inputs.each(function(i){
    if ($(this).attr('db-id') === "") {
      if ($(this).attr('db-id') !== undefined) {
      }
      isValid = false;
      return isValid;
    }

    if ($(this).val() === "") {
      isValid = false;
      return isValid;
    }
  });

  return isValid;
}

function toggle_view(element_object){
  $.each(element_object, function(index, value){
    value.toggle();
  })
  // for (var element_object in object) {
  //   if (object.hasOwnProperty(element_object)) {
  //     object.toggle();
  //   }
  // }
}

$(document).ready(function(){

  // $('.popup-input').keyup(function(e){
  //   if (e.keyCode === 38 || e.keyCode === 40 || e.keyCode === 13 || e.keyCode === 9){return false;}
  //   data = {}
  //   pop = $(this).attr('id-display');
  //   data.txt = $(this).val();
  //
  //
  //   if (data.txt == "") {
  //     $('.popup-list').slideUp();
  //     return false;
  //   } else {
  //
  //     if (pop.indexOf('trailer') >= 0){
  //       url = "actions/fetchTrailersPopup.php"
  //     }
  //     if (pop.indexOf('truck') >= 0){
  //       url = "actions/fetchTrucksPopup.php"
  //     }
  //     if (pop.indexOf('driver') >= 0){
  //       url = "actions/fetchDriversPopup.php"
  //     }
  //     if (pop.indexOf('broker') >= 0) {
  //       url = "actions/fetchBrokersPopup.php"
  //     }
  //
  //     $.ajax({
  //       method: 'POST',
  //       data: data,
  //       url: url,
  //       success: function(result){
  //         resp = JSON.parse(result);
  //
  //         switch (resp.code) {
  //           case 1:
  //             $(pop).html(resp.data).slideDown();
  //             break;
  //           case 2:
  //           $(pop).html("<p>No se encontraron resulados...</p>").slideDown();
  //             break;
  //           default:
  //           console.error(resp.message);
  //           $(pop).html("").slideUp();
  //
  //         }
  //       },
  //       error: function(exception){
  //         console.error(exception);
  //       }
  //     })
  //   }
  // })
  //
  // $('.popup-list').delegate('p', 'click', function(){
  //   var dbid = $(this).attr('db-id');
  //   var inputTarget = $(this).parent().attr('id');
  //   $("[id-display='#" + inputTarget+ "']").attr("value", $(this).html()).attr('db-id', $(this).attr('db-id')).change();
  //   $("[id-display='#" + inputTarget+ "']").prop("value", $(this).html()).change();
  //   $('.popup-list').slideUp();
  //
  // });
  //
  // $('.popup-list').on('mouseenter', 'p', function(){
  //   $('.hovered').attr('class', '');
  //   $(this).attr('class', 'hovered');
  // });
  //
  // $('.popup-list').on('mouseleave', 'p', function(){
  //   $(this).attr('class', '')
  // });
  //
  // $('.zipInput').blur(function(){
  //
  //   el = $(this)
  //   var txt = el.val();
  //   console.log(el);
  //   $.ajax({
  //     method: 'POST',
  //     data: {txt: txt},
  //     url: 'actions/fetchCityState.php',
  //     success: function(result){
  //       rsp = JSON.parse(result);
  //       console.log(rsp);
  //       if (rsp.code == 1) {
  //         el.parent().siblings().find('.stateInput').val(rsp.data.state).change();
  //         el.parent().siblings().find('.cityInput').val(rsp.data.city).change();
  //       } else {
  //         console.log(rsp.message);
  //       }
  //     },
  //     error: function(exception){
  //       console.error(exception);
  //     }
  //
  //   })
  //
  // })
  //
  // $('#addTripButton').click(function(){
  //   var data = {};
  //   data.truckid = $('[id-display="#truck-popup-list"]').attr('db-id');
  //   data.trailerid = $('[id-display="#trailer-popup-list"]').attr('db-id');
  //   data.driverid = $('[id-display="#driver-popup-list"]').attr('db-id');
  //
  //   data.broker = {
  //     brokerid: $('[id-display="#broker-popup-list"]').attr('db-id'),
  //     broker_reference: $('#broker-reference').val()
  //   }
  //
  //   data.trip = {
  //     origin: {
  //       state: $('#oState').val(),
  //       city: $('#oCity').val(),
  //       zip: $('#oZip').val()
  //     },
  //     destination: {
  //       state: $('#dState').val(),
  //       city: $('#dCity').val(),
  //       zip: $('#dZip').val()
  //     },
  //     rate: $('#tRate').val(),
  //     conveyance: {
  //       driver: $('.driverid').attr('db-id'),
  //       truck: $('.truckid').attr('db-id'),
  //       team: $('.teamdriverid').attr('db-id')
  //     }
  //   }
  //
  //   var toggle_buttons = [
  //     $(this),
  //     $($(this).attr('loading'))
  //   ]
  //
  //   toggle_view(toggle_buttons);
  //
  //   $.ajax({
  //     method: 'POST',
  //     data: data,
  //     url: 'actions/addNewTrip.php',
  //     success: function(result){
  //       resp = JSON.parse(result);
  //       console.log(resp);
  //       if (resp.query.code == 1) {
  //         window.location.href = "tripDetails.php?tripid=" + resp.query.insertid + "&tripyear=" + resp.query.tripyear
  //       } else {
  //         swal({
  //           title: "Trip not added :(",
  //           text: resp.query.message,
  //           icon: 'error'
  //         })
  //         toggle_view(toggle_buttons);
  //       }
  //     },
  //     error: function(exception){
  //       toggle_view(toggle_buttons);
  //       console.error(exception);
  //     }
  //   })
  //
  // })

  $('#tripDashTable').on('click', 'tr', function(){
    tripid = $(this).attr('db-id');
    tripyear = $(this).attr('ty');
    if (typeof tripid === 'undefined' || typeof tripyear === 'undefined') {
      return false;
    }
    window.location.href = "tripDetails.php?tripid=" + tripid + "&tripyear=" + tripyear
  });

  $('')

  // $('#newTripForm').find('input').on('blur', function(e){
  //   // console.log(e);
  //   var current_target = $(this);
  //   // console.log("Current Target: -> ");
  //   // console.log(current_target);
  //   var form_inputs = $('#newTripForm').find('input').not($('[type=checkbox], [style="display: none"], .disabled'));
  //   valid = validateForm(form_inputs, current_target);
  //   if (valid) {
  //     $('#addTripButton').removeClass('disabled').attr('disabled', false);
  //   } else {
  //     $('#addTripButton').addClass('disabled').attr('disabled', true);
  //   }
  // });
  //
  // $('.teamDriverCheck').change(function(){
  //   valid = $(this).prop('checked');
  //   target = $(this).attr('target');
  //
  //   if(valid){
  //     $(target).fadeIn();
  //   } else {
  //     $(target).fadeOut();
  //   }
  // })
  //
  // $('[id-display="#driver-popup-list-modal"]').change(function(){
  //   data = {
  //     driver_id: $(this).attr('db-id')
  //   }
  //
  //   var defaultTruck = $.ajax({
  //     method: 'POST',
  //     url: 'actions/fetchDefaultTruck.php',
  //     data: data
  //   });
  //
  //   defaultTruck.done(function(result){
  //     rsp = JSON.parse(result);
  //
  //     if (rsp.code == 1) {
  //       $('[id-display="#truck-popup-list-modal"]').attr('db-id', rsp.data.truck_id).val(rsp.data.truck_number);
  //     } else {
  //       console.log(rsp.message);
  //     }
  //   })
  // })
  //
  // $('[data-toggle="popover"]').popover({
  //    trigger: 'click',
  //    container: 'body',
  //    title: 'Quick Broker Add',
  //    placement: 'bottom',
  //    html: true,
  //    content: function(){
  //      return $('#addBrokerQuick').html()
  //    },
  //    template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
  //  }).on('shown.bs.popover', function(){
  //
  //    $('.qa-broker-submit').click(function(){
  //      var data = {
  //        name: $('.popover').find('.qa-broker-name').val(),
  //        contact: $('.popover').find('.qa-broker-contact').val()
  //      }
  //
  //      if (data.name == "" || data.contact == "") {
  //        swal({
  //          title: "Oops! Name and contact must be specified!",
  //          text: "Please verify information and try again. If the problem persists, please contact support.",
  //          icon: 'error'
  //        });
  //        return false;
  //      }
  //
  //      console.log(data);
  //      var quick_add_broker = $.ajax({
  //        method: 'POST',
  //        url: 'actions/quickAddBroker.php',
  //        data: data
  //      });
  //
  //      quick_add_broker.done(function(result){
  //        console.log(result);
  //        rsp = JSON.parse(result);
  //        if (rsp.code == 1) {
  //          $('.popover').popover('hide');
  //          $('#brokerName').attr('db-id', rsp.data).val(data.name);
  //        } else {
  //          swal({
  //            title: "Oops! There was an issue adding the broker. :(",
  //            text: rsp.message,
  //            icon: 'error'
  //          });
  //        }
  //      });
  //    });
  //  })




});

// $(document).keydown(function(e){
//   if (e.keyCode == 38 || e.keyCode == 40){
//     if ($(document.activeElement).attr('id-display') !== undefined) {
//       var target = $(document.activeElement).attr('id-display') + " p";
//       var targetFocus = $(document.activeElement).attr('id-display') + " p" + ".hovered";
//
//       if ($(targetFocus).length == 0) {
//         $(target).first().addClass('hovered');
//       } else {
//         if (e.keyCode == 40) {
//           $(targetFocus).removeClass('hovered').next().addClass('hovered');
//         }
//
//         if (e.keyCode == 38) {
//           $(targetFocus).removeClass('hovered').prev().addClass('hovered');
//         }
//       }
//
//     }
//   }
//
//   if (e.keyCode === 13 || e.keyCode === 9) {
//     var targetFocus = $(document.activeElement).attr('id-display') + " p" + ".hovered";
//
//     var dbid = $(targetFocus).attr('db-id');
//     var inputTarget = $(targetFocus).parent().attr('id');
//     $("[id-display='#" + inputTarget+ "']").attr("value", $(targetFocus).html()).attr('db-id', $(targetFocus).attr('db-id'));
//     $("[id-display='#" + inputTarget+ "']").prop("value", $(targetFocus).html());
//     $('.popup-list').slideUp();
//
//   }
// });
