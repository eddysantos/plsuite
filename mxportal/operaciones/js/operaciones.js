$(document).ready(function(){







  // Nueva Operacion modal
  $('#addMovement_btn').click(function(){
    var movement_line = $(`<div class="form-inline justify-content-end mb-1">
    <select class="custom-select mr-1" name="">
      <option value="">Tipo</option>
      <option value="Viaje">Viaje</option>
      <option value="Arrastre">Arrastre</option>
    </select>
    <input type="text" class="form-control mr-1" placeholder="Origen" name="" value="">
    <input type="text" class="form-control mr-1" placeholder="Destino" name="" value="">
    <input type="text" class="form-control mr-1" placeholder="Remolque" name="" value="">
    <select class="custom-select" name="">
      <option value="">Clase</option>
      <option value="Trompo">Trompo</option>
      <option value="Vacio">Vacio</option>
      <option value="Cargado">Cargado</option>
    </select>
      <i class="far fa-times-circle ml-2 text-danger remove-movement"></i>
    </div>`);
    movement_line.appendTo($('#movements_div'));
  });
  $('#movements_div').on('click', '.remove-movement', function(){
    var line = $(this).parents('.form-inline');
    line.remove();
  })


})
