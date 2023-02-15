$( document ).ready(function() {

  var lang_table = {
    lengthMenu: 'Mostrar: _MENU_ por página',
    zeroRecords: 'No existen registros',
    info: 'Página _PAGE_ de _PAGES_',
    infoEmpty: 'No existen registros',
    infoFiltered: '(Filtrados de _MAX_ totales)',
    paginate:{
      first: 'Primero',
      last: 'Último',
      next: 'Siguiente',
      previous: 'Anterior'
    },
    search: 'Buscar',
  }

  var table = $('#table-list').DataTable({
    buttons: [
          { extend: 'copy', text: 'Copiar'},
          'excel',
          'pdf',
          { extend: 'csv', charset: 'UTF-8', bom: true },
          { extend: 'print', text: 'Imprimir', message: 'Listado Total', footer:true }
      ],
    "lengthMenu": [[15, 30, 50, -1], [15, 30, 25, 50, "Total"]],

    "language": lang_table
  });

  table.buttons().container().appendTo( $('.col-sm-6:eq(0) ', table.table().container() ) );

});
