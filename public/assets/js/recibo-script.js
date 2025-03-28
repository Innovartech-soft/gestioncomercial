$(document).ready(function() {

  //-------- Bloque de codigo utilizado en indexByCliente de cuentas corrientes ------//
  idCliente = $('#id_cliente_form').val();
  if(idCliente!==undefined){
    getVentas(idCliente);
  }
  //------FIN------//
  //----------------- Calculo de monto total (metodo de pago) ------------//
    // Obtener los campos de entrada y el campo "monto"
    var $inputs = $('.montopago-input');
    var $monto = $('#monto');
    var $limpiarBtn = $('#limpiarBtn');

    // Escuchar el evento de cambio en los campos de entrada
    $inputs.on('input', function() {
      // Sumar los valores de los campos de entrada
      var total = 0;
      $inputs.each(function() {
        var valor = parseFloat($(this).val()) || 0; // Obtener el valor numérico del campo de entrada
        total += valor;
      });

      // Actualizar el valor del campo "monto"
      $monto.val(total.toFixed(1)); // Establecer el total con 2 decimales
    });

    function recalcularMontoTotal(){
       // Sumar los valores de los campos de entrada
       var total = 0;
       $inputs.each(function() {
         var valor = parseFloat($(this).val()) || 0; // Obtener el valor numérico del campo de entrada
         total += valor;
       });

       // Actualizar el valor del campo "monto"
      if(total>0)
        $monto.val(total.toFixed(1)); // Establecer el total con 2 decimales
      else
        $monto.val('');
    }

    function limpiarCamposMetodoPago(){
      $inputs.val(''); // Borrar el contenido de los campos de entrada
      $monto.val(''); // Vaciar el campo "monto"
      $('#cheques_seleccionados').val('')
    }
    //----------------- FIN ------------//

    //------------------- INICIO COMPORTAMIENTO Y DATOS GENERAL ------------//
    document.getElementById('toggleBtn').addEventListener('click', function() {
    var div = document.getElementById('divPago');
    if (div.style.display === 'none') {
      div.style.display = 'block';
    } else {
      div.style.display = 'none';
    }
    });
     // Escuchar el evento de clic en el botón "Limpiar Campos"
    $limpiarBtn.on('click', function() {
      limpiarCamposMetodoPago();
    });

    $('#id_proveedor, #id_cliente').on('change', function() {
      if ($('#id_proveedor').val() !== "") {
          // Si se selecciona un proveedor, deshabilitamos el select de Cliente
          $('#id_cliente').prop("disabled", true);
          $('#id_venta').prop("disabled", true);
          $('#div_cliente_saldo').hide();
          $('#div_afectar_caja').hide();
      } else if ($('#id_cliente').val() !== "") {
          // Si se selecciona un cliente, deshabilitamos el select de Proveedor
          $('#id_proveedor').prop("disabled", true);
          getVentas($('#id_cliente').val());
          $('#div_cliente_saldo').show();
          $('#div_afectar_caja').show();
      } else {
          // Si no se selecciona ninguno, habilitamos ambos selects
          $('#id_proveedor').prop("disabled", false);
          $('#id_cliente').prop("disabled", false);
          $('#id_venta').prop("disabled", true);
          $('#div_cliente_saldo').hide();
          $('#div_afectar_caja').hide();
          $('#div_venta').hide();
          $('#id_venta').prop('selectedIndex', 0);

      }
  });

    //Obtiene el listado de ventas y saldo de la cuenta corriente
  function getVentas(idCliente){

     $.ajax({
        url: '/venta/getVentasByCliente/'+idCliente,
        type: 'GET',
        success: function(response) {
          console.log(response);
          var select = $('#id_venta');
          $('#cliente_saldo').text(0);
          // Limpia todas las opciones, excepto la primera
          select.find('option:not(:first)').remove();
          $('#cliente_saldo').text(response.saldo);
          if (response.ventas.length === 0) {
            // Si no hay ventas, agrega una opción especial
            select.empty();
            var noVentasOption = $('<option>', {
                value: '',
                text: 'No existen ventas abiertas...'
            });
            select.append(noVentasOption);
          } else {

            select.empty();
            var option = $('<option>', {
                    value: '', // Asigna el valor de la venta
                    text: 'Seleccione...' // Asigna el texto de la venta
                });
            $('#id_venta').append(option);
            $.each(response.ventas, function(index, venta) {
                option = $('<option>', {
                    value: venta.id, // Asigna el valor de la venta
                    text: 'Nº '+venta.numero_venta+' - $'+venta.total.toFixed(1) // Asigna el texto de la venta
                });
                // Agrega la opción al select

                $('#cliente_saldo').text(venta.saldo);
                $('#id_venta').append(option);
            });
          }
        },
        error: function(xhr, status, error) {
            alert("error");
            console.error(error);
        }
    });

  }
  //----------------- Manejo del boton modal cheques (metodo de pago) ------------//
  $('#btnBuscarCheque').click(function () {//Desplegar el modal de cheques
    $('#modalListadoCheques').modal('show');
  });

  setBotonCheques(); //Habilita el boton ver modal cheque

  $("#id_tipo_recibo, #id_cliente, #id_proveedor").change(function() {
      setRequiredFields();
      setBotonCheques();
    });

  function setBotonCheques() { //Habilita o deshabilita el boton para el modal de metodo de pago "cheque"
      const tipoReciboSeleccionado = $("#id_tipo_recibo").val();
      const clienteSeleccionado = $("#id_cliente").val();
      const proveedorSeleccionado = $("#id_proveedor").val();
      limpiarCamposMetodoPago();

      if (tipoReciboSeleccionado !== "" && (clienteSeleccionado !== "" || proveedorSeleccionado !== "")) {
        $("#btnBuscarCheque").removeClass("disabled").removeAttr("aria-disabled");
      } else {
        $("#btnBuscarCheque").addClass("disabled").attr("aria-disabled", "true");
      }
    }
  //----------------- FIN ------------//

  //-------------------------------INICIO CHEQUES ----------------------------//
  //--------------- Inicio funciones para sumar los cheques seleccionados y enviarlos en el form ----------//
  let sumaTotal = 0;
  let chequesSeleccionados = [];
  $('.sumar-cheque').click(function() {//Añade cheques al total
      const $row = $(this).closest('tr'); // Obtiene la fila actual
      const valorCheque = parseFloat($(this).attr('data-monto'));
      const chequeID = $(this).attr('data-id-cheque');
      const clienteID = $(this).attr('data-id-cliente');
      const reciboID = $(this).attr('data-id-recibo');
      if($('#id_tipo_recibo').find(":selected").val()==2 //Hardcode - ID 2 es tipo COBRO
            &&$('#id_cliente').find(":selected").val()!=clienteID)
      {
        alert('Advertencia: No se  puede asignar como pago un cheque de un cliente distinto al seleccionado.');
        return;
      }else if($('#id_tipo_recibo').find(":selected").val()==2 //Hardcode - ID 2 es tipo COBRO
            &&$('#id_cliente').find(":selected").val()==clienteID
            && reciboID!="")
      {
        alert('Advertencia: No se puede asignar como pago un cheque ya utilizado.');
        return;
      }
      if (!isNaN(valorCheque)) {
          sumaTotal += valorCheque;
          $('#Cheque').val(sumaTotal);

          // Agregar el ID del cheque seleccionado al input hidden
          const chequesSeleccionados = $('#cheques_seleccionados').val() || ''; // Obtener valores actuales
          if (chequesSeleccionados) {
              $('#cheques_seleccionados').val(chequesSeleccionados + ',' + chequeID);
          } else {
              $('#cheques_seleccionados').val(chequeID.toString());
          }

          $(this).addClass('disabled').attr('disabled', 'disabled');
          $row.find('.cancelar-cheque').show(); // Muestra el botón "cancelar-cheque"
          $row.find('.sumar-cheque').hide();
          $row.addClass('fila-color-verde');
          recalcularMontoTotal();
      } else {
          alert('Por favor, seleccione un cheque para sumar.');
      }
  });

  $('.cancelar-cheque').click(function() {//Elimina cheques del total
      const $row = $(this).closest('tr'); // Obtiene la fila actual
      const valorCheque = parseFloat($(this).attr('data-monto'));
      const chequeID = $(this).attr('data-id-cheque');
      const clienteID = $(this).attr('data-id-cliente');
      if (!isNaN(valorCheque)) {
          sumaTotal -= valorCheque;
          $('#Cheque').val(sumaTotal);

          // Eliminar el ID del cheque cancelado del input hidden
          const chequesSeleccionados = $('#cheques_seleccionados').val().split(',');
          const index = chequesSeleccionados.indexOf(chequeID.toString());
          if (index !== -1) {
              chequesSeleccionados.splice(index, 1);
              $('#cheques_seleccionados').val(chequesSeleccionados.join(','));
          }

          $(this).hide(); // Oculta el botón "cancelar-cheque"
          $row.find('.sumar-cheque').removeClass('disabled').removeAttr('disabled');
          $row.find('.sumar-cheque').show();
          $row.removeClass('fila-color-verde');
          recalcularMontoTotal();
      } else {
          alert('Por favor, seleccione un cheque para cancelar.');
      }
    });
    //----------------- FIN ------------//

    //Verificaciones del form antes del Submit
  /*
    $("#formRecibo").submit(function(event) {
      // Verifica si el campo "monto" está vacío o no es un número válido
      const monto = $("#monto").val();
      if (monto === "" || isNaN(parseFloat(monto))) {
        // Si el campo "monto" está vacío o no es un número válido, muestra un mensaje de alerta
        alert("Por favor, ingrese un monto válido.");
        // Cancela el envío del formulario
        event.preventDefault();
      }
      // Si el campo "monto" tiene un valor válido, el formulario se enviará normalmente
    });*/
    $("#crearRecibo").click(function() {
        // Obtén el valor del campo monto
        var monto = $("#monto").val().trim();

        // Verifica si el monto es un número positivo
        if (!isNaN(monto) && parseFloat(monto) > 0) {
            // Deshabilita el botón
            $(this).prop("disabled", true);

            // Envía el formulario
            $("#formRecibo").submit();
        } else {
            // Muestra un mensaje de error o realiza otra acción si es necesario
            alert("El campo monto debe tener al menos un valor positivo.");
            event.preventDefault();
        }
    });

    function setRequiredFields(){
      var tipoRecibo = $('#id_tipo_recibo').find(":selected").val();
      var cliente = $('#id_cliente').find(":selected").val();
      var proveedor = $('#id_proveedor').find(":selected").val();

      if(tipoRecibo == 2){
        $('#id_cliente').prop("disabled", false);
        $('#id_venta').prop("disabled", false);
        $('#id_venta').prop("required", true);
        $('#id_venta').prop('selectedIndex', 0);
        $('#id_proveedor').prop("disabled", true);
        $('#id_proveedor').prop('selectedIndex', 0);
        $('#div_venta').show();
      }else if(tipoRecibo == 1){
        $('#id_cliente').prop("disabled", false);
        $('#id_proveedor').prop("disabled", false);
        $('#id_venta').prop('selectedIndex', 0);
        $('#div_cliente_saldo').hide();
        $('#cliente_saldo').text('');
        $('#id_venta').prop("disabled", true);
        $('#div_venta').hide();
        if(cliente){
          $('#id_cliente').prop("disabled", false);
          $('#id_proveedor').prop("disabled", true);
          $('#div_cliente_saldo').show();
        }else if(proveedor){
          $('#id_cliente').prop("disabled", true);
          $('#id_venta').prop("disabled", true);
          $('#id_cliente').prop('selectedIndex', 0);
          $('#id_venta').prop('selectedIndex', 0);
          $('#id_proveedor').prop("disabled", false);
        }

      }else{
        $('#id_cliente').prop("disabled", true);
        $('#id_venta').prop("disabled", true);
        $('#id_proveedor').prop("disabled", true);
        $('#id_proveedor').prop('selectedIndex', 0);
        $('#id_venta').prop('selectedIndex', 0);
        $('#id_cliente').prop('selectedIndex', 0);
        $('#div_cliente_saldo').hide();
        $('#div_afectar_caja').hide();
        $('#cliente_saldo').text('');
      }
    }
});
