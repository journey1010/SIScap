import { Toast } from "./Toast.js";

$(document).ready(select2);
function select2() {
  $(".select2").select2({
    closeOnSelect: true,
  });
}

$(document).on("click", "#searchDNI", buscarDNI);
function buscarDNI() {
  let dni = $("#dni").val();
  if (dni == "") {
    Toast.fire({
      background: "#86FFD3",
      icon: "info",
      title: "El campo DNI no puede estar vacío",
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
          $("#apellido_paterno").val(data.apellidoPaterno);
          $("#apellido_materno").val(data.apellidoMaterno);
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

//registrar usuarios
$(document).on("submit", "#registrarUsuario", registrarUsuario);
function registrarUsuario(e) {
  e.preventDefault();
  if (
    $("#dni").val() === "" ||
    $("#nombre_usuario").val() === "" ||
    $("#nombre").val() === "" ||
    $("#apellido_paterno").val() === "" ||
    $("#apellido_materno").val() === "" ||
    $("#contrasena").val() === "" ||
    $("#tipoUsuario").val() === "" ||
    $('#oficinaUsuario').val()
  ) {
    Toast.fire({
      background: "#E8EC14",
      icon: "warning",
      iconColor: "#000000",
      title: "Debe llenar todos los campos obligatorios",
    });
  } else {
    let formData = {
      dni: $("#dni").val(),
      nombre_usuario: $("#nombre_usuario").val(),
      nombre: $("#nombre").val(),
      apellido_paterno: $("#apellido_paterno").val(),
      apellido_materno: $("#apellido_materno").val(),
      contrasena: $("#contrasena").val(),
      numero_telefono: $("#numero_telefono").val(),
      tipo_usuario: $("#tipoUsuario").val(),
      oficina: $('#oficinaUsuario').val()
    };
    $.ajax({
      method: "POST",
      url: "/administrador/resgistrarusuarios",
      data: formData,
      beforeSend: function () {
        $("#btn btn-primary").html("Guardando...");
      },
      success: function (response) {
        $("#btn btn-primary").html("Guardar");
        let resp = JSON.parse(response);
        let indice = Object.keys(resp)[0];
        switch (indice) {
          case "error":
            Toast.fire({
              background: "#E75E15",
              iconColor: "#000000",
              icon: "error",
              title: resp[indice],
            });
            break;
          case "success":
            Toast.fire({
              icon: "success",
              title: resp[indice],
            });
            break;
          case "user":
            Toast.fire({
              background: "#E8EC14",
              icon: "warning",
              iconColor: "#000000",
              title: resp[indice],
            });
            break;
          case "dni":
            Toast.fire({
              background: "#E8EC14",
              icon: "warning",
              iconColor: "#000000",
              title: resp[indice],
            });
            break;
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        Toast.fire({
          icon: "error",
          title: `Ha ocurrido un error en la solicitud! Código: ${jqXHR.status}, Estado: ${textStatus}, Error: ${errorThrown}`,
          background: "#ff0000",
        });
      },
    });
  }
}
