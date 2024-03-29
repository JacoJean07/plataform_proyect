<?php

require "../sql/database.php";


$error = null;
//identifica el metodo que usa el server, en este caso si el metodo es POST procesa el if
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  //validamos que los campos no se manden vacios
  if ( empty($_POST["email"]) || empty($_POST["password"])){
    $error = "Please fill all fields(llena todo los campos).";
  // validamos que el email contenga @, hay que hacer una mejor validacion en caso de app verdadera y en caso de que el cliente o usuario no sea un navegador
  } else if (!str_contains($_POST["email"], "@")) {
    $error = "Email format is incorrect.";
  } else {
    //verificamos que el email no se repita
    $statement = $conn->prepare("SELECT * FROM users WHERE user_email = :email");
    $statement->bindParam(":email", $_POST["email"]);
    $statement->execute();
    //COMPROBAMOS QUE EL ID EXISTA, EN CASO DE QUE EL USUARIO NO SEA UN NAVEGADOR, Y SI NO EXISTE EL ID MANDAMOS UN ERROR
    if ($statement->rowCount() > 0) {
      $error = "This email is taken (este correo ya existe).";
    } else {
      //mandar los datos a la base de datos
      $statement = $conn->prepare("INSERT INTO users ( user_email, user_password, user_role) VALUES ( :email, :password, 1)");
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
      header("Location: home.php");
    }
  }
}


?>



<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- Style -->
    <link rel="stylesheet" href="css/style.css">

    <title>Login</title>
  </head>
  <body>
  



<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card text-center" style="background-color: #363636;">
        <div class="card-header" style="background-color: #ff9900;;">Register</div>
        <div class="card-body">
          <!-- si hay un error mandar un danger -->
          <?php if ($error): ?> 
            <p class="text-danger">
              <?= $error ?>
            </p>
          <?php endif ?>
          <form method="POST" action="register.php">

            <div class="mb-3 row">
              <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

              <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required autocomplete="password" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


</body>
</html>
