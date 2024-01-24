<?php
//llamamos al file database para que se conecte
require "../sql/database.php";
//llamar a la funcion sesion para identificar las sesiones
session_start();
if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  return;
}

//declaramos la variable error que nos ayudara a mostrar errores, etc.
$error = null;
//obtenemos el id para trabajar con ese row
$id = $_GET["id"];


// Si la sesión no existe, mandar al login.php y dejar de ejecutar el resto
if (($_SESSION["user"]["user_role"]) && ($_SESSION["user"]["user_role"] == 1 || $_SESSION["user"]["user_role"] == 2 || $_SESSION["user"]["user_role"] == 3)) {
  $statement = $conn->prepare("SELECT * FROM data WHERE user_id = :id AND user_id = {$_SESSION['user']['id']} LIMIT 1");
  $statement->execute([":id" => $id]);

  // Comprobamos que el ID exista
  // if ($statement->rowCount() == 0) {
  //   http_response_code(404);
  //   echo("HTTP 404 NOT FOUND");
  //   return;
  // }

  // Asignamos la tarea a una variable y usamos el metodo fetch para que se pueda leer en formato de array asociativo
  $data = $statement->fetch(PDO::FETCH_ASSOC);

  // Identifica el método que usa el server, en este caso si el método es POST procesa el if
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validamos que los campos no se manden vacíos
    if (empty($_POST["name"]) || empty($_POST["lastname"]) || empty($_POST["date"]) || empty($_POST["dni"]) || empty($_POST["phone"]) || empty($_POST["address"]) || empty($_POST["career"])) {
      $error = "Por favor llene todos los campos.";
    } else {
      // Mandar los datos a la base de datos
      $statement = $conn->prepare("INSERT INTO data (user_id, data_name, data_lastname, data_birthdate, data_dni, data_phone, data_address, data_career, data_rm) 
                                    VALUES (:user_id, :name, :lastname, :date, :dni, :phone, :address, :career, 1)
                                    ON DUPLICATE KEY UPDATE 
                                    data_name = VALUES(data_name),
                                    data_lastname = VALUES(data_lastname),
                                    data_birthdate = VALUES(data_birthdate),
                                    data_dni = VALUES(data_dni),
                                    data_phone = VALUES(data_phone),
                                    data_address = VALUES(data_address),
                                    data_career = VALUES(data_career)");

      // Sanitizamos valores para inyecciones SQL y lo mandamos directo en el execute
      $statement->execute([
        ":user_id" => $_SESSION['user']['id'],
        ":name" => $_POST["name"],
        ":lastname" => $_POST["lastname"],
        ":date" => $_POST["date"],
        ":dni" => $_POST["dni"],
        ":phone" => $_POST["phone"],
        ":address" => $_POST["address"],
        ":career" => $_POST["career"],
      ]);

      // Redirige al home.php
      header("Location: home.php");
    }
  }
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
        <div class="card p-4">
          <!-- si hay un error mandar un danger -->
          <?php if ($error): ?> 
            <p class="text-danger">
              <?= $error ?>
            </p>
          <?php endif ?>
          <form action="data.php?id=<?= $_SESSION["user"]["id"] ?>" method="post">

            <div class="input-group mb-3">
              <span class="input-group-text">Nombres</span>
              <input type="text" id="name" class="form-control" placeholder="" name="name">
              <span class="input-group-text">Apellidos</span>
              <input type="text" id="lastname" class="form-control" placeholder="" name="lastname">
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon2">Fecha de Nacimiento</span>
              <input type="date" id="date" class="form-control" placeholder="" aria-label="Recipient's username" name="date">
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Cédula</span>
              <input type="text" id="dni" class="form-control" name="dni">
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Celular</span>
              <input type="text" id="phone" class="form-control" name="phone">
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Direccion</span>
              <input type="text" id="address" class="form-control" name="address">
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Carrera</span>
              <select id="carrer" class="form-select" aria-label="Default select example" name="career">
                <option value="1">Desarrollo de Software</option>
                <option value="2">Administración de Empresas</option>
                <option value="3">Diseño Gráfico</option>
              </select>
            </div>

            <!-- Botón de envío del formulario -->
            <button type="submit" class="btn btn-block btn-secondary">Registrar</button>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require ("partials/footer.php"); ?>
