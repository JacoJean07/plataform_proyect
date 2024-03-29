<?php
//llamamos al file database para que se conecte
require "../sql/database.php";
//llamar a la funcion sesion para identificar las sesiones
session_start();
//si la sesion no existe, mandar al login.php y dejar de ejecutar el resto; se puede hacer un required para ahorra codigo
if (($_SESSION["user"]["user_role"]) && ($_SESSION["user"]["user_role"] == 1 || $_SESSION["user"]["user_role"] == 2 || $_SESSION["user"]["user_role"] == 3)) {
  //llamar los contactos de la base de datos y especificar que sean los que tengan el student_id de la funcion sesion_start
  $dataUser = $conn->query("SELECT data.* FROM data WHERE user_id = {$_SESSION["user"]["id"]} LIMIT 1");
  $user = $dataUser->fetch(PDO::FETCH_ASSOC);
  $dataCv = $conn->query("SELECT cv.* FROM cv WHERE user_id = {$_SESSION["user"]["id"]} LIMIT 1");
  $cv = $dataCv->fetch(PDO::FETCH_ASSOC);
  $skills = $conn->query("SELECT skills.* FROM skills WHERE user_id = {$_SESSION["user"]["id"]}");
  $proyects = $conn->query("SELECT proyects.* FROM proyects WHERE user_id = {$_SESSION["user"]["id"]}");


} else {
  header("Location: ../index.php");
  return;
}


?>



<?php require ("partials/header.php"); ?>


<section style="background-color: #eee;">
  <div class="container py-5">
  <?php if ($dataUser->rowCount() == 0):  ?>
    <div class= "mt-3 mx-auto">
      <div class= "card card-body text-center">
        <p>Agrega tus Datos Personales :D</p>
        <a href="data.php?id=<?= $_SESSION["user"]["id"] ?>">PRESS HERE!</a>
      </div>
    </div>
  <?php else : ?>

    <div class="row">
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
            <img src="" alt="avatar"
              class="rounded-circle img-fluid" style="width: 150px;">
            <h5 class="my-3"><?= $user["data_name"]?></h5>
            <p class="text-muted mb-1">
              <?php if ($user["data_career"] == 1) : ?>
              <p>Desarrollador de Software</p>
              <?php elseif ($user["data_career"] == 2) : ?>
              <p>Administrador de Empresas</p>
              <?php elseif ($user["data_career"] == 3) : ?>
              <p>Diseñador Gráfico</p>
              <?php endif ?> 
            </p>
            <p class="text-muted mb-4"><?= $user["data_address"]?></p>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?= $_SESSION["user"]["user_email"]?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Telefono</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?= $user["data_phone"]?></p>
              </div>
            </div>
          </div>
        </div>

        <?php if ($dataCv->rowCount() == 0):  ?>
        <?php else : ?>
        <div class="card mb-4 mb-lg-0">
          <div class="card-body p-0">
            <h3 class="card-header" align="center">Social Media</h3>
            <ul class="list-group list-group-flush rounded-3 d-flex flex-row">
              <?php if($cv["cv_twitter"] != NULL) :?>
              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="<?= $cv["cv_twitter"]?>"  target="_blank">
                  <i class="fa-brands fa-twitter" style="color: #74C0FC; font-size: 30px;"></i>
                </a>
              </li>
              <?php else : ?>

              <?php endif ?>
              <?php if($cv["cv_youtube"] != NULL) :?>
              <li class="list-group-item d-flex align-items-center p-3">
                <a href="<?= $cv["cv_youtube"]?>"  target="_blank">
                  <i class="fa-brands fa-youtube" style="color: #eb0a0a; font-size: 30px;"></i>
                </a>
              </li>
              <?php else : ?>

              <?php endif ?>
              <?php if($cv["cv_facebook"] != NULL) :?>
              <li class="list-group-item d-flex align-items-center p-3">
                <a href="<?= $cv["cv_facebook"]?>"  target="_blank">
                  <i class="fa-brands fa-facebook" style="color: #1e4a94; font-size: 30px;"></i>
                </a>
              </li>
              <?php else : ?>

              <?php endif ?>
              <?php if($cv["cv_instagram"] != NULL) :?>
              <li class="list-group-item d-flex align-items-center p-3">
                <a href="<?= $cv["cv_instagram"]?>"  target="_blank">
                  <img src="https://static.cdninstagram.com/rsrc.php/v3/yI/r/VsNE-OHk_8a.png" style="font-size: 30px;" alt="">
                </a>
              </li>
              <?php else : ?>

              <?php endif ?>
              <?php if($cv["cv_github"] != NULL) :?>
              <li class="list-group-item d-flex align-items-center p-3">
                <a href="<?= $cv["cv_github"]?>"  target="_blank">
                  <i class="fa-brands fa-github" style="color: #000000; font-size: 30px;"></i>
                </a>
              </li>
              <?php else : ?>

              <?php endif ?>
              <?php if($cv["cv_linkedin"] != NULL) :?>
              <li class="list-group-item d-flex align-items-center p-3">
                <a href="<?= $cv["cv_linkedin"]?>" target="_blank">
                  <i class="fa-brands fa-linkedin" style="color: #276391; font-size: 30px;"></i>
                </a>
              </li>
              <?php else : ?>

              <?php endif ?>
            </ul>
          </div>
        </div>
        <?php endif ?>

        <?php if ($skills->rowCount() == 0):  ?>
          <div class= "mt-3 mx-auto">
            <div class= "card card-body text-center">
              <p>Agrega tus habilidades :D</p>
              <a href="skillsAdd.php?id=<?= $_SESSION["user"]["id"] ?>">PRESS HERE!</a>
            </div>
          </div>
        <?php else : ?>
        <div class="mt-3">
          <div class="card mb-4 mb-md-0">
            <div class="card-body">
              <h4 class="mb-4">Habilidades</h4>
              <?php foreach ($skills as $skill): ?>
                <p class="mb-1"><?= $skill["skill_name"]?></p>
              <?php endforeach ?>
              <div class="m-3 row ">
                <div class="">
                  <a class="link-light btn btn-success" href="skillsAdd.php?id=<?= $_SESSION["user"]["id"] ?>">Agregar Habilidades</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif ?>
      </div>
      
      <div class="col-lg-8">
        <?php if ($dataCv->rowCount() == 0):  ?>
          <div class= "col-md-4 mx-auto">
            <div class= "card card-body text-center">
              <p>Agrega proyectos, redes sociales y todo sobre ti :D</p>
              <a href="cvAdd.php?id=<?= $_SESSION["user"]["id"] ?>">PRESS HERE!</a>
            </div>
          </div>
        <?php else : ?>
        <div class="row">
          <div class="mb-3">
            <div class="card mb-4 mb-md-0">
              <div class="card-body">
                
                <p class="mb-4"><span class="text-primary font-italic me-1">Presentación</span></p>
                <p><?= $cv["cv_presentation"]?></p>

                <p class="mb-4"><span class="text-primary font-italic me-1">Sobre mí</span></p>
                <p><?= $cv["cv_about"]?></p>

                <p class="mb-4"><span class="text-primary font-italic me-1">Sobre mi Profesión</span></p>
                <p><?= $cv["cv_profesionAbout"]?></p>

                

                <div class="m-3 row ">
                  <div class="">
                    <a class="link-light btn btn-success" href="cvAdd.php?id=<?= $cv["id"] ?>">Editar CV</a>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

        <div class="container">
          
          <div class="row">
            <div class="card mb-4 ">
              <div class="card-body">
                <p class="mb-4"><span class="text-primary font-italic me-1">Portafolio</span></p>
                <p><?= $cv["cv_portfolio"]?></p>

                <?php if ($proyects->rowCount() == 0):  ?>
                  <div class= "mt-3 mx-auto">
                    <div class= "card card-body text-center">
                      <p>Agrega tus proyectos :D</p>
                      <a href="proyectsAdd.php?id=<?= $_SESSION["user"]["id"] ?>">PRESS HERE!</a>
                    </div>
                  </div>
                <?php else : ?>
                
                  <div class="row">
                  <?php foreach ($proyects as $proyect): ?>
                    <div class="col-md-6 mb-3">
                      <div class="card" style="width: 18rem;">
                        <div class="card-header d-flex">
                          <h5 class="me-auto"> <?= $proyect["proyect_name"]?> </h5>
                          <p class="card-text"> <?= $proyect["proyect_date"]?></p>
                        </div>
                        <div class="card-body">
                          <h6 class="card-subtitle mb-2 text-body-secondary"> <?= $proyect["proyect_category"]?> </h6>
                          <p class="card-text"> <?= $proyect["proyect_description"]?></p>
                          <!-- Agregar la imagen -->
                          <img src="<?= $proyect["proyect_image"] ?>" class="card-img-top rounded" alt="Proyecto Image">
                        </div>
                      </div>
                    </div>
                  <?php endforeach ?>
                  </div>

                  <div class="m-3 row ">
                    <div class="">
                      <a class="link-light btn btn-success" href="proyectsAdd.php?id=<?= $_SESSION["user"]["id"] ?>">Agregar mas Proyectos</a>
                    </div>
                  </div>
                <?php endif ?>
              </div>
            </div>
          </div>
        </div>
        <?php endif ?>
      </div>
    </div>
  <?php endif ?>
  </div>
</section>

<?php require ("partials/footer.php"); ?>
