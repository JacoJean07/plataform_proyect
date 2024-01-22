<?php
//llamamos al file database para que se conecte
require "../sql/database.php";
//llamar a la funcion sesion para identificar las sesiones
session_start();
//si la sesion no existe, mandar al login.php y dejar de ejecutar el resto; se puede hacer un required para ahorra codigo
if (($_SESSION["user"]["user_role"]) && ($_SESSION["user"]["user_role"] == 1 || $_SESSION["user"]["user_role"] == 2)) {
  //llamar los contactos de la base de datos y especificar que sean los que tengan el user_id de la funcion sesion_start
  $students = $conn->query("SELECT data.* FROM data JOIN users ON data.user_id = users.id WHERE users.user_role = 3;");
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
          <div class="table-responsive">
            <div class="m-3 row ">
              <div class="">
                <button type="submit" class="btn btn-success"><a class="link-light" href="studentsAdd.php">Nuevo estudiante</a></button>
              </div>
            </div>
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Apellidos</th>
                  <th scope="col">Nombres</th>
                  <th scope="col">Carrera</th>
                  <th scope="col">Estado</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($students as $student): ?>
                <tr>
                  <th scope="row">1</th>
                  <td>example</td>
                  <td>test</td>
                  <td>desarrollo</td>
                  <td>Egresado</td>
                  <td>ver perfil</td>
                </tr>
              <?php endforeach ?>
              </tbody>
            </table>
            
          </div>  
        </div>
      </div>
    </div>
  </div>
</div>

<?php require ("partials/footer.php"); ?>
