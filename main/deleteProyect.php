<?php
require "../sql/database.php";

session_start();

if (!isset($_SESSION["user"])) {
  header("Location: ../login.php");
  return;
}

$id = $_GET["id"];

$statement = $conn->prepare("SELECT * FROM proyects WHERE id = :id AND user_id = {$_SESSION['user']['id']}");
$statement->execute([":id" => $id]);

if ($statement->rowCount() == 0) {
  http_response_code(404);
  echo("HTTP 404 NOT FOUND");
  return;
}

// Obtén la ruta de la imagen antes de eliminar el registro
$statement = $conn->prepare("SELECT proyect_image FROM proyects WHERE id = :id");
$statement->execute([":id" => $id]);
$proyect = $statement->fetch(PDO::FETCH_ASSOC);
$imagePath = $proyect["proyect_image"];

// Elimina el registro de la base de datos
$conn->prepare("DELETE FROM proyects WHERE id = :id")->execute([":id" => $id]);

// Elimina la imagen asociada al proyecto
if ($imagePath && file_exists($imagePath)) {
    unlink($imagePath);
}

// Redirigimos al usuario a la página de proyectsAdd
header("Location: proyectsAdd.php");
return;
?>
