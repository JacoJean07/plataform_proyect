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
  $statement = $conn->prepare("SELECT * FROM cv WHERE id = :id AND user_id = {$_SESSION['user']['id']} LIMIT 1");
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
    if (empty($_POST["presentation"]) || empty($_POST["about"]) || empty($_POST["profesionAbout"]) || empty($_POST["portfolio"])) {
      $error = "Por favor llene todos los campos.";
    } else {
      // Verificamos si ya existe un registro para el usuario actual
      $existingStatement = $conn->prepare("SELECT id FROM cv WHERE user_id = :user_id");
      $existingStatement->execute([":user_id" => $_SESSION['user']['id']]);
      $existingData = $existingStatement->fetch(PDO::FETCH_ASSOC);

      if ($existingData) {
          // Si existe, actualizamos el registro existente
          $statement = $conn->prepare("UPDATE cv SET
              cv_twitter = :twitter,
              cv_facebook = :facebook,
              cv_youtube = :youtube,
              cv_github = :github,
              cv_linkedin = :linkedin,
              cv_instagram = :instagram,
              cv_presentation = :presentation,
              cv_about = :about,
              cv_profesionAbout = :profesionAbout,
              cv_portfolio = :portfolio
              WHERE id = :id");

          $statement->execute([
              ":id" => $existingData["id"],
              ":twitter" => ($_POST["twitter"] !== "") ? $_POST["twitter"] : null,
              ":facebook" => ($_POST["facebook"] !== "") ? $_POST["facebook"] : null,
              ":youtube" => ($_POST["youtube"] !== "") ? $_POST["youtube"] : null,
              ":github" => ($_POST["github"] !== "") ? $_POST["github"] : null,
              ":linkedin" => ($_POST["linkedin"] !== "") ? $_POST["linkedin"] : null,
              ":instagram" => ($_POST["instagram"] !== "") ? $_POST["instagram"] : null,
              ":presentation" => $_POST["presentation"],
              ":about" => $_POST["about"],
              ":profesionAbout" => $_POST["profesionAbout"],
              ":portfolio" => $_POST["portfolio"],
          ]);
      } else {
          // Si no existe, insertamos un nuevo registro
          $statement = $conn->prepare("INSERT INTO cv (user_id, cv_twitter, cv_facebook, cv_youtube, cv_github, cv_linkedin, cv_instagram, cv_presentation, cv_about, cv_profesionAbout, cv_portfolio, cv_status, cv_rm) 
                                      VALUES (:user_id, :twitter, :facebook, :youtube, :github, :linkedin, :instagram, :presentation, :about, :profesionAbout, :portfolio, 0, 1)");

          $statement->execute([
              ":user_id" => $_SESSION['user']['id'],
              ":twitter" => ($_POST["twitter"] !== "") ? $_POST["twitter"] : null,
              ":facebook" => ($_POST["facebook"] !== "") ? $_POST["facebook"] : null,
              ":youtube" => ($_POST["youtube"] !== "") ? $_POST["youtube"] : null,
              ":github" => ($_POST["github"] !== "") ? $_POST["github"] : null,
              ":linkedin" => ($_POST["linkedin"] !== "") ? $_POST["linkedin"] : null,
              ":instagram" => ($_POST["instagram"] !== "") ? $_POST["instagram"] : null,
              ":presentation" => $_POST["presentation"],
              ":about" => $_POST["about"],
              ":profesionAbout" => $_POST["profesionAbout"],
              ":portfolio" => $_POST["portfolio"],
          ]);
      }
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
      Mi CV
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
          <form action="cvAdd.php?id=<?= $id ?>" method="post">

            <div class="input-group mb-3">
              <span class="input-group-text">Presentación Corta</span>
              <textarea type="text" id="presentation" class="form-control custom-textarea" placeholder="Soy juan :D tengo 20 años :D" name="presentation"><?= $data["cv_presentation"] ?></textarea>
            </div>

            <div class="input-group mb-3 ">
              <span class="input-group-text span_cv px-5">Biografía</span>
              <textarea type="text" id="about" class="form-control custom-textarea" placeholder="Biografía" name="about"><?= $data["cv_about"] ?></textarea>
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Sobre mi Profesión</span>
              <textarea type="text" id="profesionAbout" class="form-control custom-textarea" placeholder="Me dedico a..." name="profesionAbout"><?= $data["cv_profesionAbout"] ?></textarea>
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Sobre tu Portafolio</span>
              <textarea type="text" id="portfolio" class="form-control custom-textarea" placeholder="Me dedico a..." name="portfolio"><?= $data["cv_portfolio"] ?></textarea>
            </div>

            <h3 class="" align="center">TUS REDES SOCIALES :D</h3>

            <div class="input-group mb-3">
              <span class="input-group-text">Twitter</span>
              <input value="<?= $data["cv_twitter"] ?>" type="text" id="twitter" class="form-control" name="twitter">
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Facebook</span>
              <input value="<?= $data["cv_facebook"] ?>" type="text" id="facebook" class="form-control" name="facebook">
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Youtube</span>
              <input value="<?= $data["cv_youtube"] ?>" type="text" id="youtube" class="form-control" name="youtube">
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Github</span>
              <input value="<?= $data["cv_github"] ?>" type="text" id="github" class="form-control" name="github">
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Linkedin</span>
              <input value="<?= $data["cv_linkedin"] ?>" type="text" id="linkedin" class="form-control" name="linkedin">
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Instagram</span>
              <input value="<?= $data["cv_instagram"] ?>" type="text" id="instagram" class="form-control" name="instagram">
            </div>

            <!-- Botón de envío del formulario -->
            <button type="submit" class="btn btn-block btn-secondary">Guardar</button>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require ("partials/footer.php"); ?>
