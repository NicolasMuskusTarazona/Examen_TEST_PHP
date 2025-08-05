<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Database\Connection;
use Illuminate\Database\Capsule\Manager as DB;

$mensaje = "";

// Conectar a la base de datos
$conexion = Connection::init();
if ($conexion !== true) {
    die($conexion);
}

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["registrar"])) {
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $clavePlano = trim($_POST["clave"]);

    if (empty($nombre) || empty($correo) || empty($clavePlano)) {
        $mensaje = "Todos los campos son obligatorios.";
    } elseif (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $clavePlano)) {
        $mensaje = "La contraseña debe contener al menos un carácter especial.";
    }else {
        // Verificar si ya existe el correo
        $existe = DB::table('usuarios')->where('email', $correo)->exists();

        if ($existe) {
            $mensaje = "El correo ya está registrado.";
        } else {
            // Hashear contraseña y guardar
            $claveHasheada = password_hash($clavePlano, PASSWORD_DEFAULT);

            DB::table('usuarios')->insert([
                'nombre' => $nombre,
                'email' => $correo,
                'password' => $claveHasheada
            ]);

            // Redirigir al login
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro | Colombia Coffee</title>
    <link rel="stylesheet" href="/docs/style/register.css" />
    <link rel="icon" type="image/png" href="/docs/img/Coffee.png" />
</head>

<body>
    <div class="form-container">
        <h2>Crear Cuenta</h2>

        <?php if (!empty($mensaje)) : ?>
            <p style="color:red;"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" id="username" name="nombre" required />
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="correo" required />
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="clave" required />
            </div>

            <button class="boton" type="submit" name="registrar">Registrarse</button>

            <p class="login-link">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
            </p>
        </form>
    </div>

    <script src="/docs/commponents/register.js"></script>
</body>

</html>