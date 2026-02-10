/*
function eliminarAlert(event) {
	if (confirm("Está seguro de eliminar este registro? La operación no podrá deshacerse."))
		return true;
	event.preventDefault();
}	
*/

function eliminarAlert(event, formEl) {
    event.preventDefault();

    Swal.fire({
        title: '¿Eliminar registro?',
        text: 'Está seguro de eliminar este registro? La operación no podrá deshacerse.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: false,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Evitar loop con onsubmit: removemos handler y enviamos
            formEl.onsubmit = null;
            formEl.submit();
        }
    });
}
