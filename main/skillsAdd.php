<?php 

require "../sql/database.php";

session_start();
//si la sesion no existe, mandar al login.php y dejar de ejecutar el resto; se puede hacer un required para ahorra codigo
if (!isset($_SESSION["user"])) {
  header("Location: ../login.php");
  return;
}

//declaramos la variable error que nos ayudara a mostrar errores, etc.
$error = null;
//validar user
if (($_SESSION["user"]["user_role"]) && ($_SESSION["user"]["user_role"] == 1 || $_SESSION["user"]["user_role"] == 2 || $_SESSION["user"]["user_role"] == 3)) {
  $skills = $conn->query("SELECT * FROM skills WHERE user_id = {$_SESSION['user']['id']}");


  // Comprobamos que el ID exista
  // if ($statement->rowCount() == 0) {
  //   http_response_code(404);
  //   echo("HTTP 404 NOT FOUND");
  //   return;
  // }

  
  //verificamos el metodo que usa el form con un if
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //validamos que no se manden datos vacios
    if (empty($_POST["skill"])) {
      $error = "POR FAVOR El CAMPO SOLICITADO";
    } else {
      //sdeclaramos variables y las asignamos a los valores recibidos del input
      $skill = $_POST["skill"];

      //preparamos una sentencia SQL
      $statement = $conn->prepare("INSERT INTO skills (user_id, skill_name) VALUES ({$_SESSION['user']['id']}, :skill)");
      //sanitizamos los datos para evitar inyecciones SQL
      $statement->bindParam(":skill", $_POST["skill"]);
      //ejecutamos
      $statement->execute();
      //redirigimos a el home.php
      header("Location: skillsAdd.php");
      return;
    }
  }
} else {
  header("Location: ../index.php");
  return;
}

?>

<?php require("./partials/header.php"); ?>


<div class="container pt-3 p-4">

  <div class="card">
    <div class="card-header text-center">Nueva Habilidad</div>
    <div class="card-body">
      <!-- si hay un error mandar un danger -->
      <?php if ($error): ?> 
        <p class="text-danger">
          <?= $error ?>
        </p>
      <?php endif ?>
      <form method="POST" action="skillsAdd.php">
        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Nombre de la Habilidad</span>
          <input type="text" class="form-control" placeholder="Javascript" id="skill" name="skill" required autocomplete="skill" autofocus>
        </div>
        <!-- Botón de envío del formulario -->
        <button type="submit" class="btn btn-block btn-secondary">Registrar</button>
      </form>
    </div>
  </div>

  <div class="container pt-4 p-3">
    <div class="row">
      
  
    <!-- si el array asociativo $skills no tiene nada dentro, entonces imprimir el siguiente div -->
    <?php if ($skills->rowCount() == 0): ?>

    <?php else : ?>


    <!-- sirve para hacer una targeta por cada valor que tenga el array asociativo $skills -->
    <?php foreach ($skills as $skill): ?>
    <div class="col-md-4 mb-3">
      <div class="card" style="width: 18rem;">
      <div class="card-header d-flex">
        <h5 class="me-auto"> <?= $skill["skill_name"]?> </h5>
        <a href="deleteSkill.php?id=<?= $skill["id"]?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
          </svg>
        </a>
      </div>
      </div>
    </div>
    <?php endforeach ?>
    <?php endif ?>
    </div>
  </div>
</div>


</div>

<?php require('partials/footer.php'); ?>
