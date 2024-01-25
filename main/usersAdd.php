<?php
//llamamos al file database para que se conecte
require "../sql/database.php";
//llamar a la funcion sesion para identificar las sesiones
session_start();
//si la sesion no existe, mandar al login.php y dejar de ejecutar el resto; se puede hacer un required para ahorra codigo
if (($_SESSION["user"]["user_role"]) && ($_SESSION["user"]["user_role"] == 1))  {

  $error = null;
  //identifica el metodo que usa el server, en este caso si el metodo es POST procesa el if
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    //validamos que los campos no se manden vacios
    if ( empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["rol"])){
      $error = "Por favor llene todos los campos.";
    // validamos que el email contenga @, hay que hacer una mejor validacion en caso de app verdadera y en caso de que el cliente o usuario no sea un navegador
    } else if (!str_contains($_POST["email"], "@")|| !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
      $error = "Formato de email incorrecto.";
    } else if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()-_+=])[A-Za-z0-9!@#$%^&*()-_+=]{8,}$/', $_POST["password"])) {
      $error = "La contraseña debe tener al menos 8 caracteres y contener al menos una letra mayúscula, un número y un carácter especial.";
    } else {
      //verificamos que el email no se repita
      $statement = $conn->prepare("SELECT * FROM users WHERE user_email = :email");
      $statement->bindParam(":email", $_POST["email"]);
      $statement->execute();
      //COMPROBAMOS QUE EL ID EXISTA, EN CASO DE QUE EL USUARIO NO SEA UN NAVEGADOR, Y SI NO EXISTE EL ID MANDAMOS UN ERROR
      if ($statement->rowCount() > 0) {
        $error = "Este correo ya existe";
      } else {
        //mandar los datos a la base de datos
        $statement = $conn->prepare("INSERT INTO users ( user_email, user_password, user_role) VALUES ( :email, :password, :rol)");
        //sanitizar valores para inyecciones sql y lo mandamos directo en el execute
        $statement->execute([
          ":email" => $_POST["email"],
          //hash con la funcion password_hash y la libreria PASSWORD_BCRYPT
          ":password" => password_hash($_POST["password"], PASSWORD_BCRYPT),
          ":rol" => $_POST["rol"],
        ]);
  
  
        //redirige al home.php
        header("Location: users.php");
      }
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
    Nuevo usuario 
  </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card  p-4">
          
          <form action="usersAdd.php" method="post">
            <!-- si hay un error mandar un danger -->
            <?php if ($error): ?> 
              <p class="text-danger">
                <?= $error ?>
              </p>
            <?php endif ?>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">E-mail</span>
              <input type="email" id="email" class="form-control" placeholder="@" name="email" >
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">Contraseña</span>
              <input type="password" id="password" class="form-control" name="password" >
            </div>

            <div class="input-group mb-3">
              <select id="rol" name="rol" class="form-select" aria-label="Default select example">
                <option value="2">Profesor</option>
                <option value="3">Estudiante</option>
                <option value="1">Admin</option>
              </select>
            </div>

            <button type="submit" class="btn btn-block btn-secondary">Registrar</button>
          
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require ("partials/footer.php"); ?>
