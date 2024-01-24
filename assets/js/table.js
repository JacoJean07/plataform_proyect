new DataTable('#tbl');


// para data.php
document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('registrarBtn').addEventListener('click', function (event) {
    // Validar los campos antes de enviar el formulario
    var name = document.getElementById('name').value.trim();
    var lastname = document.getElementById('lastname').value.trim();
    var date = document.getElementById('date').value.trim();
    var dni = document.getElementById('dni').value.trim();
    var phone = document.getElementById('phone').value.trim();
    var address = document.getElementById('address').value.trim();
    var career = document.getElementById('carrer').value;

    if (name === '' || lastname === '' || date === '' || dni === '' || phone === '' || address === '' || career === '') {
      // Si algún campo está vacío, evita enviar el formulario
      alert('Por favor, llene todos los campos.');
      event.preventDefault();
    }
  });
});
