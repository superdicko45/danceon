$( document ).ready(function() {

  var lang = $('#lang').val();
  var lang_success = 'Buen Trabajo';
  var lang_message = 'Se borró con éxito!';
  var lang_danger = 'Inténtalo más tarde!';
  var lang_question = '¿Realmente lo quieres cancelar?';
  var lang_q_msg = 'Una vez eliminado, no podrás recuperar el registro!';
  var lang_confirm = 'Si, Continuar';
  var lang_cancel = 'Cancelar';
  var lang_inactivo = 'Inactivo';
  var lang_print = 'Imprimir';
  var lang_p_msg = 'Listado Total';
  var lang_copy = 'Copiar';

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

  if( lang == 'en' ) {

    lang_success = 'Good job!';
    lang_message = 'It was deleted with success!';
    lang_danger = 'Try it later!';
    lang_question = 'Are you sure?';
    lang_q_msg = "You wont't recover it later";
    lang_confirm = 'Yes, Continue';
    lang_cancel = 'Cancel';
    lang_inactivo = 'Inactive';
    lang_copy = 'Copy';
    lang_print = 'Print';
    lang_p_msg = 'Total list';

    lang_table = {
      lengthMenu: 'show: _MENU_ by page',
      zeroRecords: 'No records',
      info: 'Page _PAGE_ of _PAGES_',
      infoEmpty: 'No records',
      infoFiltered: '(Filtered from _MAX_ total entries)',
      paginate:{
        first: 'First',
        last: 'Last',
        next: 'Next',
        previous: 'Previous'
      },
      search: 'Search',
    }
  }

  var table = $('#parque-table').DataTable({
    buttons: [
          { extend: 'copy', text: lang_copy},
          'excel',
          'pdf',
          { extend: 'csv', charset: 'UTF-8', bom: true },
          { extend: 'print', text: lang_print, message: lang_p_msg, footer:true }
      ],
    "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "Total"]],

    "language": lang_table
  });

  table.buttons().container().appendTo( $('.col-sm-6:eq(0) ', table.table().container() ) );

  $(document).on("click",".cancel",function(event) {
    event.preventDefault();
    Swal({
      title: lang_question,
      text: lang_q_msg,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: lang_confirm,
      cancelButtonText: lang_cancel
    }).then((result) => {
      if (result.value) {
        var id = $(this).attr('data-id');
        $.ajax({
            type: "post",
            url: "/parque/asignacion/operador/cancel",
            data: {
                _token: $('input[name=_token]').val(),
                itemId: id
            },
            success: function(response) {
              if(response.error){
                Swal('Error!', lang_danger, 'error');
              }else{
                $('#active-td-' + id).html('<span class="pull-right badge bg-warning">'+ lang_inactivo +'</span>');
                $('#button-td-' + id).empty();
                Swal(lang_success, lang_message, 'success');
              }

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              Swal(textStatus, errorThrown, 'error');
            }
        });
      }
    });
  });

});
