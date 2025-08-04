<?php
session_start();

// Solo admins
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: no-autorizado.php");
    exit();
}

$url = "http://127.0.0.1:8082/miembros";
$miembros = [];
$error = null;
$success = null;

// Eliminar miembro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $id = $_POST['eliminar_id'];

    $options = ['http' => ['method' => 'DELETE']];
    $context = stream_context_create($options);
    $deleteUrl = $url . '/' . $id;

    $response = @file_get_contents($deleteUrl, false, $context);
    $success = $response !== false ? "Miembro eliminado con éxito." : "Error al eliminar.";
}

// Editar miembro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editando_id']) && !isset($_POST['eliminar_id'])) {
    $id = $_POST['editando_id'];
    $usuario_id = $_POST['usuario_id'] ?? '';
    $faccion_id = $_POST['faccion_id'] ?? '';
    $rango = $_POST['rango'] ?? '';
    $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';

    if ($usuario_id && $faccion_id && $rango && $fecha_ingreso) {
        $data = json_encode([
            'usuario_id' => (int)$usuario_id,
            'faccion_id' => (int)$faccion_id,
            'rango' => $rango,
            'fecha_ingreso' => $fecha_ingreso
        ]);

        $options = [
            'http' => [
                'header'  => "Content-type: application/json",
                'method'  => 'PUT',
                'content' => $data
            ]
        ];
        $context = stream_context_create($options);
        $putUrl = $url . '/' . $id;
        $response = @file_get_contents($putUrl, false, $context);
        $success = $response !== false ? "Miembro actualizado correctamente." : "Error al actualizar.";
    } else {
        $error = "Todos los campos son obligatorios para editar.";
    }
}

// Crear miembro nuevo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['editando_id']) && empty($_POST['eliminar_id'])) {
    $usuario_id = $_POST['usuario_id'] ?? '';
    $faccion_id = $_POST['faccion_id'] ?? '';
    $rango = $_POST['rango'] ?? '';
    $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';

    if ($usuario_id && $faccion_id && $rango && $fecha_ingreso) {
        $data = json_encode([
            'usuario_id' => (int)$usuario_id,
            'faccion_id' => (int)$faccion_id,
            'rango' => $rango,
            'fecha_ingreso' => $fecha_ingreso
        ]);

        $options = [
            'http' => [
                'header'  => "Content-type: application/json",
                'method'  => 'POST',
                'content' => $data
            ]
        ];
        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);
        $success = $response !== false ? "Miembro registrado con éxito." : "Error al registrar.";
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}

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
        <h1>Miembros ADMIN HCF</h1>
        <nav>
            <a href="./faccionesAdmin.php">Facciones</a>
            <a href="./miembrosAdmin.php">Miembros</a>
            <a href="./claimsAdmin.php">Claims</a>
            <a href="./invitacionesAdmin.php">Invitaciones</a>
            <a href="./eventosAdmin.php">Eventos</a>
        </nav>
    </header>

    <main>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <h2>Registrar / Editar Miembro</h2>
        <form method="POST">
            <input type="hidden" name="editando_id" id="editando_id">
            <label>Usuario ID:<br><input type="number" name="usuario_id" id="usuario_id" required></label><br><br>
            <label>Facción ID:<br><input type="number" name="faccion_id" id="faccion_id" required></label><br><br>
            <label>Rango:<br>
                <select name="rango" id="rango" required>
                    <option value="">-- Selecciona un rango --</option>
                    <option value="lider">Líder</option>
                    <option value="oficial">Oficial</option>
                    <option value="miembro">Miembro</option>
                </select>
            </label><br><br>
            <label>Fecha Ingreso:<br><input type="date" name="fecha_ingreso" id="fecha_ingreso" required></label><br><br>
            <button type="submit">Guardar</button>
        </form>

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
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="eliminar_id" value="<?= $item['id'] ?>">
                            <button type="submit" onclick="return confirm('¿Seguro que quieres eliminar este miembro?')">Eliminar</button>
                        </form>
                        <button onclick="editarMiembro(
                            '<?= $item['id'] ?>',
                            '<?= htmlspecialchars($item['usuario_id']) ?>',
                            '<?= htmlspecialchars($item['faccion_id']) ?>',
                            '<?= htmlspecialchars($item['rango']) ?>',
                            '<?= htmlspecialchars($item['fecha_ingreso']) ?>'
                        )">Editar</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function editarMiembro(id, usuario_id, faccion_id, rango, fecha_ingreso) {
            document.getElementById('editando_id').value = id;
            document.getElementById('usuario_id').value = usuario_id;
            document.getElementById('faccion_id').value = faccion_id;
            document.getElementById('rango').value = rango;
            document.getElementById('fecha_ingreso').value = fecha_ingreso;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>
