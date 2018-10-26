$(document).ready(function(){

  $('#user-table').on('click', 'tr', function(e){
    if (e.target.tagName == 'BUTTON') {
      return false;
    } else {
      var user_id = $(this).attr('db-id');
      window.location.href = "userDetails.php?user_id=" + user_id;
    }
  })

  $('#user-table').on('click', '.reset-pwd', function(e){
    swal('An email will be sent to the user with a new password. Do you want to continue?',{
      buttons:{
        cancel: "No",
        yes: true
      }
    }).then((value)=>{

      if (value) {
        swal({
          text: 'An email was sent to the user with the new password.',
          icon: 'success'
        });
      }
    })
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
        url = "actions/fetchTrailersPopup.php"
      }
      if (pop.indexOf('truck') >= 0){
        url = "actions/fetchTrucksPopup.php"
      }
      if (pop.indexOf('driver') >= 0){
        url = "actions/fetchDriversPopup.php"
      }
      if (pop.indexOf('broker') >= 0) {
        url = "actions/fetchBrokersPopup.php"
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

  $('#save-user-details').click(function(){

    var data = {};
    $('#user-details-form').find('select, input, checkbox, radio').each(function(){
      var input_id = $(this).attr('id');
      var value = $(this).val();
      var dbid = $(this).attr('db-id');
      if (typeof input_id !== 'undefined') {
        if (typeof dbid !== 'undefined') {
          data[input_id] = dbid
        } else {
          data[input_id] = value
        }
      }
    });

    var update_user_details = $.ajax({
      method: 'POST',
      url: 'actions/update_user_details.php',
      data: data
    });

    update_user_details.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        alertify.success('User details updated correctly!');
      } else {
        alertify.error('Something went wrong on the update. Please contact tech support.');
        console.warn(r.message);
      }
    })
  })

  $('#user-type').change(function(){
    var value = $(this).val();
    if (value == 'Broker') {
      $('#broker-field').fadeIn();
    } else {
      $('#broker-field').fadeOut();
    }
  })

  $('#permissions-lists').on('change', 'input:checkbox', function(){
    var checked = $(this).is(':checked');
    var parent = $(this).data('parent');
    var children = $(this).closest('li').find('input:checkbox');
    // var children = "[data-parent='#" + $(this).attr('id') + "']";

    if (checked) {
      do {
        $(parent).prop('checked', true);
        parent = $(parent).data('parent');
      } while (typeof parent !== 'undefined');
    } else {
      children.prop('checked', false);
    }


  })

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

  });

});
