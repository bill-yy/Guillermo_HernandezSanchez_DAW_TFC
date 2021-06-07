<!-- Inludes PHP -->
<?php
      require_once "/var/www/Proyectos/prometeo/imp/db.php";
      require_once "sesiones.php";
      $error = false;
      
      if ((sesionIniciada()) === true) {
          header("Location: https://prometeo.sytes.net/inicio.php");
          return;
      }

      if (isset($_POST["user"]) && isset($_POST["password"])) {
          if ((comprobarUsuario($_POST["user"], $_POST["password"]) === false)) {
              $error = true;
          } else {
              //Usuario existe, crea sesión con una variables que será su correo y lo redirige a inicio.php
              $cod_app = obtenerCodApp($_POST["user"]);
              $rol = obtenerRol($_POST["user"]);

              session_start();
              $_SESSION["user"] = $_POST["user"];
              $_SESSION["cod_app"] = $cod_app[0];
              $_SESSION["rol"] = $rol[0];
              header("Location: https://prometeo.sytes.net/inicio.php");
              return;
          }
      }
    ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Prometeo</title>
    <link rel="shortcut icon" type="image/jpg" href="./res/img/prometeoLogo.png" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/inicio.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
        <title>Prometeo</title>
        <link rel="stylesheet" href="bootstrap.min.css" />
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" />
        <link rel="stylesheet" href="index.css" />
    </head>
</head>

<body>
    <header style="width: 100%;height: 6vh;background-color: #3f3f42;">
        <div class="container" style="width: 100%;height: 100%;">
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <h1 class="text-center d-xl-flex justify-content-xl-center align-items-xl-center"
                        style="width: 100%;height: 100%;font-family: Nunito;color: rgb(255,255,255);">PROMETEO</h1>
                </div>
                <div class="col"></div>
            </div>
        </div>
    </header>
    <div class="rounded shadow-lg"
        style="width: 400px;margin-bottom: auto;height: 300px;margin-right: auto;margin-left: auto;background-color: #3f3f42;margin-top: 200px;filter: blur(0px) contrast(100%);padding: 10px;">
        <h3 class="text-center" style="height: 10%;color: rgb(255,255,255);margin-top: 10px;">Iniciar Sesión</h3>
        <form
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"
            method="POST" style="height: 85%;margin-top: 5%;">
            <div class="form-group d-xl-flex justify-content-xl-center"><i
                    class="fas fa-envelope-square d-xl-flex justify-content-xl-end align-items-xl-center"
                    style="margin-right: 1%;font-size: 21px;"></i>
                <input type="email" class="rounded-0 form-control"
                    style="width: 90%;color: white;background-color: rgba(255,255,255,0);border-bottom: 1px solid grey"
                    placeholder="Correo electrónico" autocomplete="on" name="user" required />
            </div>
            <div class="form-group d-xl-flex justify-content-xl-center"><i
                    class="fas fa-lock d-xl-flex align-items-xl-center" style="margin-right: 1%;font-size: 21px;"></i>
                <input type="password" class="rounded-0 form-control"
                    style="  width: 90%;color: white;background-color: rgba(255,255,255,0);border-bottom: 1px solid grey"
                    placeholder="Contraseña" name="password" required />
            </div>
            <div class="form-group d-xl-flex justify-content-xl-end">
                <button class="btn btn-primary d-xl-flex" type="submit" style="margin-right: 5%;">Entrar</button>
            </div>
            <center>
            <?php
                if ($error === true) {
                    echo "<p>Revise correo y contraseña</p>";
                }
            ?>
            </center>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>