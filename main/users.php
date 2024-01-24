<?php
//llamamos al file database para que se conecte
require "../sql/database.php";
//llamar a la funcion sesion para identificar las sesiones
session_start();
//si la sesion no existe, mandar al login.php y dejar de ejecutar el resto; se puede hacer un required para ahorra codigo
if ($_SESSION["user"]["user_role"] == 1 || $_SESSION["user"]["user_role"] == 2) {
  //llamar los contactos de la base de datos y especificar que sean los que tengan el user_id de la funcion sesion_start
  $users = $conn->query("SELECT * FROM users");
} elseif ($_SESSION["user"]["user_role"] == 3 ) {
  header("Location: home.php");
  return;
} else {
  header("Location: ../index.php");
  return;
}


?>



<?php require ("partials/header.php"); ?>

<div class="container pt-4">
  <div class="card shadow-lg">
  <div class="card-header bg-secondary text-white">
    Usuarios
  </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card  p-4">
          <div class="table-responsive">
            <!-- si el array asociativo $teachers no tiene nada dentro, entonces imprimir el siguiente div -->
            <?php if ($users->rowCount() == 0): ?>
              <div class= "col-md-4 mx-auto">
                <div class= "card card-body text-center">
                  <p>No hay usuarios por el momento</p>
                  <a href="usersAdd.php">Agrega uno!</a>
                </div>
              </div>
            <?php endif ?>

            <div class="m-3 row ">
              <div class="">
                <button type="submit" class="btn btn-success"><a class="link-light" href="usersAdd.php">Nuevo Usuario</a></button>
              </div>
            </div>
            <table class="table table-striped table-bordered" id="tbl">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Correo</th>
                  <th scope="col">Rol</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              
              <tbody>
              <?php foreach ($users as $user): ?>
                <tr>
                  <td scope="row"><?= $user["id"]?></td>
                  <td><?= $user["user_email"]?></td>
                  <td>
                    <?php if ($user["user_role"] == 1) : ?>
                    <p>Administrador</p>
                    <?php elseif ($user["user_role"] == 2) : ?>
                    <p>Profesor</p>
                    <?php elseif ($user["user_role"] == 3) : ?>
                    <p>Estudiante</p>
                    <?php endif ?>
                  </td>
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
