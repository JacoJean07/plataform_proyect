<?php
//llamamos al file database para que se conecte
require "../sql/database.php";
//llamar a la funcion sesion para identificar las sesiones
session_start();
//si la sesion no existe, mandar al login.php y dejar de ejecutar el resto; se puede hacer un required para ahorra codigo
if (($_SESSION["user"]["user_role"]) && ($_SESSION["user"]["user_role"] == 1 || $_SESSION["user"]["user_role"] == 2)) {

  $error = null;
  //identifica el metodo que usa el server, en este caso si el metodo es POST procesa el if
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    //validamos que los campos no se manden vacios
    if ( empty($_POST["email"]) || empty($_POST["password"])){
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
        $statement = $conn->prepare("INSERT INTO users ( user_email, user_password, user_role) VALUES ( :email, :password, 3)");
        //sanitizar valores para inyecciones sql y lo mandamos directo en el execute
        $statement->execute([
          ":email" => $_POST["email"],
          //hash con la funcion password_hash y la libreria PASSWORD_BCRYPT
          ":password" => password_hash($_POST["password"], PASSWORD_BCRYPT),
        ]);
  
        //iniciamos secion con el usuario ya registrado
        //verificamos que el email ingresado ya existe
        $statement = $conn->prepare("SELECT * FROM users WHERE user_email = :email LIMIT 1");
        $statement->bindParam(":email", $_POST["email"]);
        $statement->execute();
        //obtenemos los datos de usuario y asignamos a una variable user y lo pedimos en fetch assoc para que lo mande en un formato asociativo
        $user = $statement->fetch(PDO::FETCH_ASSOC);  
        //borramos por asi decir la contrasenia del usuario en la secion para que no almacene ese valor y por seguridad
        unset($user["password"]);
        //iniciamos una sesion la cual es una cookie que es como un hash almacenado en el pc usuario para que almacene compruebe el usuario, asi la manera  de acceder a la sesion es por medio de la cockie y si alguien intenta hackear necesita el hash para poder hacer peticiones al servidor en lugar de solo necesitas el id
        session_start();
        //asignamos el usuario que se logueo a la secion iniciada
        $_SESSION["user"] = $user;
  
        //redirige al home.php
        header("Location: students.php");
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
    Nuevo usuario tipo estudiante
  </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card  p-4">
          
          <form action="studentsAdd.php" method="post">
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

            <button type="submit" class="btn btn-block btn-secondary">Registrar</button>
          
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require ("partials/footer.php"); ?>
