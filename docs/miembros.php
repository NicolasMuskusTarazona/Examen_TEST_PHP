<?php
session_start();

// Solo admins
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'user') {
    header("Location: no-autorizado.php");
    exit();
}

$url = "http://127.0.0.1:8082/miembros";
$miembros = [];
$error = null;

// Obtener todos los miembros
$rawData = @file_get_contents($url);
if ($rawData !== false) {
    $miembros = json_decode($rawData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = "Error al leer los miembros.";
    }
} else {
    $error = "No se pudo conectar con la API.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Miembros - HCF</title>
    <link rel="stylesheet" href="./style/facciones.css">
</head>
<body>
    <header>
        <h1>Miembros HCF</h1>
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

        <h2>Miembros Registrados</h2>
        <?php if (empty($miembros)): ?>
            <p>No hay miembros registrados.</p>
        <?php else: ?>
            <div class="tarjetas-grid">
                <?php foreach ($miembros as $item): ?>
                    <div class="tarjeta">
                        <h3>ID: <?= $item['id'] ?></h3>
                        <p><strong>Usuario ID:</strong> <?= htmlspecialchars($item['usuario_id']) ?></p>
                        <p><strong>Facción ID:</strong> <?= htmlspecialchars($item['faccion_id']) ?></p>
                        <p><strong>Rango:</strong> <?= htmlspecialchars($item['rango']) ?></p>
                        <p><strong>Fecha de Ingreso:</strong> <?= htmlspecialchars($item['fecha_ingreso']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
