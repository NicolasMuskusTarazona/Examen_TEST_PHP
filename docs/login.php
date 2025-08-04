<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Database\Connection;
use Illuminate\Database\Capsule\Manager as DB;

$mensaje = "";

$conexion = Connection::init();
if ($conexion !== true) {
    die($conexion);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $correo = trim($_POST["correo"]);
    $clave = trim($_POST["clave"]);

    if (empty($correo) || empty($clave)) {
        $mensaje = "Debes completar todos los campos.";
    } else {
        if ($correo === "adrian@gmail.com" && $clave === "Hola@2020") {
            $_SESSION['usuario_id'] = 0;
            $_SESSION['usuario_nombre'] = "Administrador";
            $_SESSION['usuario_email'] = $correo;
            $_SESSION['usuario_rol'] = "admin";

            header("Location: faccionesAdmin.php");
            exit();
        }

        $usuario = DB::table('usuarios')->where('email', $correo)->first();

        if ($usuario && password_verify($clave, $usuario->password)) {
            $_SESSION['usuario_id'] = $usuario->id;
            $_SESSION['usuario_nombre'] = $usuario->nombre;
            $_SESSION['usuario_email'] = $usuario->email;
            $_SESSION['usuario_rol'] = $usuario->rol;
        
            // Redirigir según el rol
            if ($usuario->rol === 'admin') {
                header("Location: faccionesAdmin.php");
            } else {
                header("Location: facciones.php");
            }
            exit();
        } else {
            $mensaje = "Correo o contraseña incorrectos.";
        }
        
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="/docs/style/register.css" />
    <link rel="icon" type="image/png" href="/docs/img/Coffee.png" />
</head>

<body>
    <div class="form-container">
        <h2>Iniciar Sesión</h2>

        <?php if (!empty($mensaje)) : ?>
            <p style="color:red;"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="correo" required />
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="clave" required />
            </div>

            <button class="boton" type="submit" name="login">Iniciar Sesión</button>

            <p class="login-link">
                ¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>
            </p>
        </form>
    </div>

    <script src="/docs/commponents/register.js"></script>
</body>

</html>