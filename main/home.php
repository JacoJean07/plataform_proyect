<?php
//llamamos al file database para que se conecte
require "../sql/database.php";
//llamar a la funcion sesion para identificar las sesiones
session_start();
//si la sesion no existe, mandar al login.php y dejar de ejecutar el resto; se puede hacer un required para ahorra codigo
if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  return;
}

//llamar los contactos de la base de datos y especificar que sean los que tengan el user_id de la funcion sesion_start
// $contacts = $conn->query("SELECT * FROM contacts WHERE user_id = {$_SESSION['user']['id']}");

?>




<?php require ("partials/header.php"); ?>
<?php require ("partials/footer.php"); ?>
