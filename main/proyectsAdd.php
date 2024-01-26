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
  $proyects = $conn->query("SELECT * FROM proyects WHERE user_id = {$_SESSION['user']['id']}");


  // Comprobamos que el ID exista
  // if ($statement->rowCount() == 0) {
  //   http_response_code(404);
  //   echo("HTTP 404 NOT FOUND");
  //   return;
  // }

  
  //verificamos el metodo que usa el form con un if
  // ... código anterior ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //validamos que no se manden datos vacíos
  if (empty($_POST["name"]) || empty($_POST["category"]) || empty($_POST["description"]) || empty($_POST["date"])) {
      $error = "POR FAVOR LLENAR LOS CAMPOS SOLICITADOS";
  } else {
      // Manejar la imagen
      if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
          $temp_name = $_FILES["image"]["tmp_name"];
          $image_name = "../imgs/proyects/" . $_FILES["image"]["name"];
          move_uploaded_file($temp_name, $image_name);

          // Resto de tu código para la inserción en la base de datos...
          $name = $_POST["name"];
          $category = $_POST["category"];
          $description = $_POST["description"];
          $date = $_POST["date"];

          $statement = $conn->prepare("INSERT INTO proyects (user_id, proyect_name, proyect_category, proyect_date, proyect_description, proyect_image) VALUES (:user_id, :name, :category, :date, :description, :image)");
          $statement->bindParam(":user_id", $_SESSION['user']['id']);
          $statement->bindParam(":name", $name);
          $statement->bindParam(":category", $category);
          $statement->bindParam(":date", $date);
          $statement->bindParam(":description", $description);
          $statement->bindParam(":image", $image_name); // Utiliza la ruta completa de la imagen

          $statement->execute();

          //redirigimos a el home.php
          header("Location: proyectsAdd.php");
          return;
      } else {
          $error = "Error al cargar la imagen.";
      }
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
      <form method="POST" action="proyectsAdd.php?id=<?= $_SESSION["user"]["id"] ?>" enctype="multipart/form-data">
        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Nombre del Proyecto</span>
          <input type="text" class="form-control" placeholder="" id="name" name="name" required autocomplete="proyect_name" autofocus>
        </div>
        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Fecha</span>
          <input type="date" class="form-control" placeholder="" id="date" name="date" required autocomplete="date" autofocus>
        </div>
        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Categoría</span>
          <input type="text" class="form-control" placeholder="Sitio Web" id="category" name="category" required autocomplete="proyect_category" autofocus>
        </div>
        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Descripcion</span>
          <input type="text" class="form-control" placeholder="" id="description" name="description" required autocomplete="proyect_description" autofocus>
        </div>
        <div class="input-group mb-3">
          <input type="file" class="form-control" placeholder="" id="image" name="image" accept="image/*" required autocomplete="image" autofocus>
        </div>
        <!-- Botón de envío del formulario -->
        <button type="submit" class="btn btn-block btn-secondary">Registrar</button>
      </form>
    </div>
  </div>

  <div class="container pt-4 p-3">
    <div class="row">
      
  
    <!-- si el array asociativo $proyects no tiene nada dentro, entonces imprimir el siguiente div -->
    <?php if ($proyects->rowCount() == 0): ?>

    <?php else : ?>


    <!-- sirve para hacer una targeta por cada valor que tenga el array asociativo $proyects -->
    <?php foreach ($proyects as $proyect): ?>
      <div class="col-md-4 mb-3">
          <div class="card" style="width: 18rem;">
              <div class="card-header d-flex" style="background-color: #afeeee;">
                  <h5 class="me-auto"> <?= $proyect["proyect_name"]?> </h5>
                  <p class="card-text"> <?= $proyect["proyect_date"]?></p>
                  <a href="deleteProyect.php?id=<?= $proyect["id"]?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                    </svg>
                  </a>
              </div>
              <div class="card-body">
                  <h6 class="card-subtitle mb-2 text-body-secondary"> <?= $proyect["proyect_category"]?> </h6>
                  <p class="card-text"> <?= $proyect["proyect_description"]?></p>
                  <!-- Agregar la imagen -->
                  <img src="<?= $proyect["proyect_image"] ?>" class="card-img-top" alt="Proyecto Image">
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
