<?php
//llamamos al file database para que se conecte
require "../sql/database.php";
//llamar a la funcion sesion para identificar las sesiones
session_start();
//si la sesion no existe, mandar al login.php y dejar de ejecutar el resto; se puede hacer un required para ahorra codigo
if (($_SESSION["user"]["user_role"]) && ($_SESSION["user"]["user_role"] == 1 || $_SESSION["user"]["user_role"] == 2 || $_SESSION["user"]["user_role"] == 3)) {
  //llamar los contactos de la base de datos y especificar que sean los que tengan el user_id de la funcion sesion_start
  //$students = $conn->query("SELECT * FROM students");
} else {
  header("Location: ../index.php");
  return;
}


?>



<?php require ("partials/header.php"); ?>

<div class="container pt-4">
  <div class="card shadow-lg">
  <div class="card-header bg-secondary text-white">
    Estudiantes
  </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card  p-4">
          
          <form action="studentsAdd.php" method="post">

            <div class="input-group mb-3">
              <span class="input-group-text">Nombres</span>
              <input type="text" id="name" class="form-control" placeholder="" >
              <span class="input-group-text">Apellidos</span>
              <input type="text" id="lastname" class="form-control" placeholder="" >
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon2">Fecha de Nacimiento</span>
              <input type="date" id="date" class="form-control" placeholder="" aria-label="Recipient's username" >
            </div>


            <div class="input-group mb-3">
              <span class="input-group-text">Cédula</span>
              <input type="text" id="dni" class="form-control" >
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Carrera</span>
              <select id="profesion" class="form-select" aria-label="Default select example">
                <option value="1">Desarrollo de Software</option>
                <option value="2">Administración de Empresas</option>
                <option value="3">Diseño Gráfico</option>
              </select>
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Estado</span>
              <select id="state" class="form-select" aria-label="Default select example">
                <option value="1">Estudiando</option>
                <option value="2">Egresado</option>
              </select>
            </div>
          </form>
          
          
        </div>
      </div>
    </div>
  </div>
</div>

<?php require ("partials/footer.php"); ?>
