$(document).ready(function () {

	obtenerEstadoCaja();
	obtenerReaperturaCaja();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
		}
	});
	function obtenerEstadoCaja() {
		$.ajax({
			url: '/getEstadoCaja',
			type: 'GET',
			dataType: 'json',
			success: function (data) {

				// console.log('Estado caja:'+data);
				if (data === 'true') {
					setEstadoEtiqueta(data);
					$('#divCerrarCaja').attr('display', 'flex');
					$('#divAbrirCaja').attr('display', 'none');
				}
				else {
					setEstadoEtiqueta(data);
					$('#divAbrirCaja').attr('display', 'flex');
					$('#divCerrarCaja').attr('display', 'none');
				}
			},
			error: function (error) {
				console.error(error);
			}
		});
	}
	function obtenerReaperturaCaja() {
		$.ajax({
			url: '/getReaperturaCaja',
			type: 'GET',
			dataType: 'json',
			success: function (data) {
				// console.log('Reapertura caja:'+data);
				var campos = $('#divAbrirCaja :input'); // Obtiene todos los campos dentro de la div
				campos.prop('disabled', data === 'false'); // Habilita o deshabilita los campos según el valor de data
				if (data === 'false') {
					$('#abrirCajaMonto').val(''); // Borra el valor del input si data es "false"
				}
			},
			error: function (error) {
				console.error(error);
			}
		});
	}
	//Abre la caja del dia y lo registra en la base de datos
	$('#abrirCaja').click(function () {
		var monto = $('#abrirCajaMonto').val();
		if (monto == "" || monto < 0)
			return;
		$.post('/setAbrirCaja', { monto: monto }, function (data) {
			// console.log(data.caja_apertura);
			// Cambiar el texto del botón de manera gradual
			$('#abrirCaja')
				.fadeOut(400, function () {
					$(this).val('Caja abierta correctamente...');
				})
				.fadeIn(600);

			$('#total_caja').val(monto);
			$('#totalCaja').val(monto);
			$('#totalEfectivo').val(monto);
			setTimeout(function () {
				$('#divAbrirCaja').hide(); // Oculta el elemento con ID "divAbrirCaja"
				$('#formCajaDiaria').modal('hide'); // Cierra la ventana modal
				$('#divCerrarCaja').show(); // Muestra el elemento con ID "divCerrarCaja"
				setEstadoEtiqueta(true);
				obtenerReaperturaCaja();
			}, 1700);


		}).fail(function (error) {
			console.error(error);
		});
	});
	//Le da cierre a la caja del dia y lo registra en la base de datos
	$('#cerrarCaja').click(function () {
		var confirmar = confirm("Está seguro de cerrar la caja del dia? La operación no podrá deshacerse y no podrá reabrirse hasta mañana.")

		if (confirmar) {
			$.get('/setCerrarCaja', function (data) {
				// console.log(data);
				// Cambiar el texto del botón de manera gradual
				$('#cerrarCaja')
					.fadeOut(400, function () {
						$(this).val('Caja cerrada correctamente...');
					})
					.fadeIn(600);
				setEstadoEtiqueta(false);
				// Esperar 3 segundos y luego recargar la página
				setTimeout(function () {
					$('#divCerrarCaja').hide(); // Oculta el elemento con ID "divCerrarCaja"
					$('#formCajaDiaria').modal('hide'); // Cierra la ventana modal
					obtenerReaperturaCaja();
					$('#divAbrirCaja').show(); // Muestra el elemento con ID "divAbrirCaja"
					location.reload();
				}, 1700);
			}).fail(function (error) {
				console.error(error);
			});
		}
	});

	function setEstadoEtiqueta(estado) {
		if (estado === 'true' || estado == true)
			estado = '<br>Abierta';
		else if (estado === 'false' || estado == false)
			estado = '<br>Cerrada';

		$("#estadoCajaLabel").html(estado);
	}

});
