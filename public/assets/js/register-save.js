function saveAlertNoBack(event) {
	if (confirm("¿Está seguro de guardar este registro? La operación no podrá deshacerse."))
		return true;
	event.preventDefault();
}
function saveAlert(event) {
	if (confirm("¿Está seguro de guardar este registro?"))
		return true;
	event.preventDefault();
}
function changeAlert(event) {
	if (confirm("¿Está seguro de realizar los cambios?"))
		return true;
	event.preventDefault();
}
function changeAlertNoBack(event) {
	if (confirm("¿Está seguro de realizar los cambios? La operación no podrá deshacerse."))
		return true;
	event.preventDefault();
}