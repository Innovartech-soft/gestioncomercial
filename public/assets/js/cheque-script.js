$(document).ready(function () {
	document.getElementById("id_cliente_cheque").disabled = true;
	$('.btnVerDetalle').on('click', function () {
		var chequeId = $(this).data('id-cheque');

		$.ajax({
			url: '/cheque/findChequeById/' + chequeId,
			type: 'GET',
			success: function (response) {
				// console.log(response);
				//vaciar campos
				$('#serie').val(response.id);
				$('#numero').val(response.numero);
				$('#banco_emisor').val(response.banco_emisor);
				$('#titular_librador').val(response.titular_librador);
				$('#fecha_emision').val(response.fecha_emision);

				if (response.id_cliente != null) {
					$("#modalListadoCheques #id_cliente_cheque").val(response.id_cliente);
				} else {
					$('#modalListadoCheques #id_cliente_cheque').prop('selectedIndex', 0);
				}
				$('#importe').val(response.importe);

			},
			error: function (xhr, status, error) {
				alert(error);
				console.error(error);
			}
		});
	});

});
