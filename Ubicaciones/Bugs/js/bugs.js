function fetchBugs(){
  var fetchBugs = $.ajax({
    method: 'POST',
    url: 'actions/fetchBugs.php'
  });

  fetchBugs.done(function(result){
    var rsp = JSON.parse(result);

    if (rsp.code == 1) {
      $('#bugDash').html(rsp.data);
    } else {
      $('#bugDash').html(rsp.data);
      console.log(rsp.message);
    }
  })
}

$(document).ready(function(){

  fetchBugs();

  // $('#bugReportModal').modal('show');

  $('#submitBugReport').click(function(){

    var data = {
      user: $('#bug_reported_by').val(),
      subject: $('#bugReportSubject').val(),
      type: $('#bugReportType').val(),
      area: $('#bugReportArea').val(),
      description: $('#bugReportDescription').val()
    }

    var addBug = $.ajax({
      method: 'POST',
      url: 'actions/addBug.php',
      data: data
    });

    addBug.done(function(result){
      rsp = JSON.parse(result);

      if (rsp.data == "1") {
        fetchBugs();
        ('.modal').modal('hide');
      } else {
        swal({
          title: "There was a problem adding the bug :(",
          text: rsp.message,
          icon: 'error'
        })
      }
    })


  });

  $('#bugDash').on('click', 'tr', function(){

    $('#bugReportDetails').find('.bug.type').val($(this).find('.type').html());
    $('#bugReportDetails .bug.subject').val($(this).find('.subject').html());
    $('#bugReportDetails .bug.description').html($(this).find('.description').html());
    $('#bugReportDetails').modal('show');
  })

});
