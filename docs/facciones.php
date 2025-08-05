<?php
session_start();

// Solo para usuarios normales
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'user') {
    header("Location: no-autorizado.php");
    exit();
}

$url = "http://127.0.0.1:8082/facciones";
$facciones = [];
$error = null;

// Obtener todas las facciones
$rawData = @file_get_contents($url);
if ($rawData !== false) {
    $facciones = json_decode($rawData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = "Error al leer las facciones.";
    }
} else {
    $error = "No se pudo conectar con la API.";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Facciones - HCF</title>
    <link rel="stylesheet" href="./style/facciones.css">
</head>

<body>
    <header>
        <h1>Facciones HCF</h1>
        <nav>
            <a href="./facciones.php">Facciones</a>
            <a href="./miembros.php">Miembros</a>
            <a href="./claims.php">Claims</a>
            <a href="./invitaciones.php">Invitaciones</a>
            <a href="./eventos.php">Eventos</a>
        </nav>
    </header>

    <main>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <h2>Facciones Registradas</h2>
        <?php if (empty($facciones)): ?>
            <p>No hay facciones registradas.</p>
        <?php else: ?>
            <div class="tarjetas-grid">
                <?php foreach ($facciones as $item): ?>
                    <div class="tarjeta">
                        <h3>ID: <?= $item['id'] ?></h3>
                        <p><strong>Nombre:</strong> <?= $item['nombre'] ?></p>
                        <p><strong>Descripcion:</strong> <?= $item['descripcion'] ?></p>
                        <p><strong>Lider ID:</strong> <?= $item['lider_id'] ?></p>
                        <p><strong>Fecha de Creacion:</strong> <?= $item['fecha_creacion'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>
