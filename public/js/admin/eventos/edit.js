$( document ).ready(function() {

    function initAutocomplete() {
      let address1Field = document.querySelector("#direccion");
      autocomplete = new google.maps.places.Autocomplete(address1Field, {
        componentRestrictions: { country: ["mx"] },
        fields: ["address_components", "geometry", "formatted_address"],
      });
      autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
      const place = autocomplete.getPlace();

      for (const component of place.address_components) {
        const componentType = component.types[0];

        switch (componentType) {

          case "sublocality_level_1":
            $("#colonia").val(component.long_name);
            break;
          case "sublocality":
            $("#colonia").val(component.long_name);
            break;
        }
      }
      
      $("#direccion").val(place.formatted_address);
      $("#latitud").val(place.geometry.location.lat());
      $("#longitud").val(place.geometry.location.lng());
      $("#colonia").focus();
    }

    $('.tooltip-icon').tooltip();
    $('#direction_form').hide();
    $('#direction_text').show();

    $.fn.select2.defaults.set('language', 'es');

    $('#fecha_inicio').datetimepicker({
      format  : 'Y-m-d H:i',
      timepicker:true,
      onShow:function( ct ){
       this.setOptions({
        maxDate:jQuery('#fecha_final').val() ? jQuery('#fecha_final').val() : false
       })
     }
    });

    $('#fecha_final').datetimepicker({
      format  : 'Y-m-d H:i',
      timepicker:true,
      onShow:function( ct ){
       this.setOptions({
        minDate:jQuery('#fecha_inicio').val() ? jQuery('#fecha_inicio').val() : false
       })
      },
    });

    $('select').select2({
      'width': '100%'
    });

    $('.users').select2({
      tags: true,
      minimumInputLength: 3,
      'width': '100%',
      ajax: {
        url: '/admin/multiple/users',
        type: 'post',
        dataType: 'json',
        data: function (term) {
            //term['term'] = term['term'].toUpperCase();
            return {
                q      : term,
                _token : $('input[name=_token]').val()
            };
        },
        processResults: function (data, params) {
            params.page = params.page || 1;

            return {
                results: data,
                pagination: {
                    more: (params.page * 10) < 50
                }
            };
        }
      }
    });

    $('#edit_direction').click(function(event){
      initAutocomplete();
      $('#direction_form').show();
      $('#direction_text').hide();
    });

    var form = $('#form_edit');
    form.validate();
    
    $('#enviar').click(function(event){
      if(form.valid()) {
        var formData = new FormData(document.getElementsByName('form_eventos')[0]);
        $.ajax({
          type: "POST",
          url: "/admin/eventos/update",
          enctype: 'multipart/form-data',
          data: formData,
          dataType: 'json',
          processData: false,
          contentType: false,

          success: function(response) {
            if(response.error){
              Swal('Error!', response.msg, 'error');
            }else{
              $('form').trigger("reset");
              Swal('Buen Trabajo', 'Se guardó con éxito!', 'success');
            }

          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            Swal(textStatus, errorThrown, 'error');
          }
        });
      } else {
        Swal('Error!', 'Verifica la información del formulario', 'error');
      }
    });
   
});
