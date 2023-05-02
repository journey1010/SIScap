let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timerProgressBar: true,
    customClass: {
        container: 'toast-container'
    }
});

function buscarDNI() {
    let dni = $("#dni").val();
    if (dni === "") {
      Toast.fire({
        icon: "info",
        title: "El campo DNI no puede estar vacío."
      });
    } else {
      $.ajax({
        url: "https://dniruc.apisperu.com/api/v1/dni/" + dni+"?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imdpbm9fcGFyZWRlc0BvdXRsb29rLmNvbS5wZSJ9.1rXghi0JQb2I-COt_4J7juPDkIgCBZZbHcixnwGF0mI",
        method: "GET",
        beforeSend: function () {
          $("#searchDNI").html("Buscando ...");
        },
        success: function (data) {
          $("#searchDNI").html("Buscar");
          if (data.success == false) {
            Toast.fire({
              icon: "error",
              title:
                "Ha ocurrido un error en la solicitud! En este momento no se puede Consultar a la API.",
            });
          } else {
            $("#nombre").val(data.nombres);
            $("#apellidoPaterno").val(data.apellidoPaterno);
            $("#apellidoMaterno").val(data.apellidoMaterno);
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#searchDNI").html("Buscar");
          Toast.fire({
            icon: "error",
            title: `Ha ocurrido un error en la solicitud! Código: ${jqXHR.status}, Estado: ${textStatus}, Error: ${errorThrown}`,
          });
        },
      });
    }
}

function enviarDatos () {
    let cursosSeleccionados = $('#cursos').val(); 
    let formData = new FormData();
    formData.append('dni', $('#dni').val()); 
    formData.append('nombre',$('#nombre').val()); 
    formData.append('apellidoPaterno',$('#apellidoPaterno').val()); 
    formData.append('apellidoMaterno',$('#apellidoMaterno').val());
    formData.append('correo',$('#correo').val());
    formData.append('celular', $('#celular').val());
    formData.append('oficina', $('#oficina').val());
    formData.append('cargo', $('#cargo').val());
    formData.append('archivo', $('#foto').prop("files")[0]);
    for (var i = 0; i < cursosSeleccionados.length; i++) {
        formData.append('cursos[]', cursosSeleccionados[i]);
    }
    

    $.ajax({
        url: '/registro-capacitacion',
        method: 'POST',
        data : formData, 
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        success: function(response) {
            let resp = JSON.parse(response);
            if (resp.status==='success') {
                Toast.fire({
                    icon: "success",
                    title: resp.message
                  });
            } else {
                Toast.fire({
                    icon: "warning",
                    title: resp.message
                });
            }
        }, 
        error: function (jqXHR, textStatus, errorThrown) {
            $("#searchDNI").html("Buscar");
            Toast.fire({
              icon: "error",
              title: `Ha ocurrido un error en la solicitud! Código: ${jqXHR.status}, Estado: ${textStatus}, Error: ${errorThrown}`,
            });
        }
    });
}

$(document).ready( function () {
    $(".select2").select2({
        closeOnSelect: true,
      });
    $(document).on('click', '#searchDNI', buscarDNI);
    $(document).on('click', '#enviar',  enviarDatos);
});