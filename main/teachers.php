<?php
//llamamos al file database para que se conecte
require "../sql/database.php";
//llamar a la funcion sesion para identificar las sesiones
session_start();
//si la sesion no existe, mandar al login.php y dejar de ejecutar el resto; se puede hacer un required para ahorra codigo
if (($_SESSION["user"]["user_role"]) && ($_SESSION["user"]["user_role"] == 1)) {
  //llamar los contactos de la base de datos y especificar que sean los que tengan el student_id de la funcion sesion_start
  $teachers = $conn->query("SELECT data.* FROM data JOIN users ON data.user_id = users.id WHERE users.user_role = 2;");} else {
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
            <!-- si el array asociativo $teachers no tiene nada dentro, entonces imprimir el siguiente div -->
            <?php if ($teachers->rowCount() == 0): ?>
              <div class= "col-md-4 mx-auto mb-3">
                <div class= "card card-body text-center">
                  <p>No hay Profesores con datos por el momento</p>
                  <a href="users.php">Mira los usuarios</a>
                </div>
              </div>
            <?php endif ?>
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Apellidos</th>
                  <th scope="col">Nombres</th>
                  <th scope="col">Carrera</th>
                  <th scope="col">Edad</th>
                  <th scope="col">Cedula</th>
                  <th scope="col">Telefono</th>
                  <th scope="col">Direccion</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($teachers as $teacher): ?>
                <tr>
                  <th scope="row"><?= $teacher["id"]?></th>
                  <td><?= $teacher["data_lastname"]?></td>
                  <td><?= $teacher["data_name"]?></td>
                  <td>
                    <?php if ($teacher["data_career"] == 1) : ?>
                    <p>Desarrollo de Software</p>
                    <?php elseif ($teacher["data_career"] == 2) : ?>
                    <p>Administración de Empresas</p>
                    <?php elseif ($teacher["data_career"] == 3) : ?>
                    <p>Diseño Gráfico</p>
                    <?php endif ?>  
                  </td>
                  <td>
                    <?php
                      // Calcular la edad a partir de la fecha de nacimiento
                      $birthdate = new DateTime($teacher["data_birthdate"]);
                      $today = new DateTime();
                      $age = $today->diff($birthdate)->y;
                      echo $age;
                    ?>
                  </td>
                  <td><?= $teacher["data_dni"]?></td>
                  <td><?= $teacher["data_phone"]?></td>
                  <td><?= $teacher["data_address"]?></td>
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
