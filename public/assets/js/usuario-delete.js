function eliminarAlert(event) {
	if (confirm("Está seguro de eliminar este registro? La operación no podrá deshacerse."))
		return true;
	event.preventDefault();
}	