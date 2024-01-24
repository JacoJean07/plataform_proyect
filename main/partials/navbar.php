

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: rgba(0, 0, 0, 0.8);">
  <div class="container-fluid">
    <!-- mostrar el siguiente nav para las secciones existentes-->
    <?php if($_SESSION["user"]["user_role"] == 1 || $_SESSION["user"]["user_role"] == 2 || $_SESSION["user"]["user_role"] == 3) : ?>
        <ul class="nav nav-underline px-3">
            <li class="nav-item">
                <a class="nav-link active logo mr-auto" aria-current="page" href="home.php" style="color: white;" ><img src="../assets/img/logo.png" width="90px" alt=""></a>
            </li>
        </ul>
        
    

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="d-flex justify-content-center w-100">
        <ul class="nav nav-underline mb-2 mb-lg-0">
          <!-- si existe una sesion iniciada pon los siguientes hipervinculos (home y add contacts) -->
          <?php if($_SESSION["user"]["user_role"] == 1) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="students.php">Estudiantes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="teachers.php">Verificadores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users.php">Usuarios</a>
                </li>
                
            
            <?php elseif ($_SESSION["user"]["user_role"] == 2) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="studentList.php">Estudiantes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="groupList.php">Validar Perfil</a>
                </li>
            
            <?php elseif($_SESSION["user"]["user_role"] == 3): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Perfil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="groupList.php">Explorar Perfiles</a>
                </li>
          <?php endif ?>
        </ul>
        <?php if (isset($_SESSION["user"])): ?>
        <!-- si existe la variable global sesion con el valor user, entonces mostrar el siguiente div -->
            <ul class="nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" ><?= $_SESSION["user"]["user_email"] ?></a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="data.php?id=<?= $_SESSION["user"]["id"] ?>">Ajustes de cuenta</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="nav-item">
                            <!-- llamamos al logout.php -->
                            <a class="nav-link active" aria-current="page" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        <?php endif ?>
      </div>      
    </div>
    <!-- si no, mandar al index o tambien llamado welcome page -->
    <?php else : ?>
        <?php header("Location: ../index.php"); ?>
    <?php endif ?>
    <!-- end if -->
  </div>
</nav>

<main>
  